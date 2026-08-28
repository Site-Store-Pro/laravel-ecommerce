<?php
namespace App\Plugins\Support;

use App\Models\CmsDownload;
use App\Models\CmsEmbed;
use App\Models\CmsForm;
use App\Models\Plugin;

class ShortcodeProcessor
{
    protected PluginManager $manager;

    public function __construct(PluginManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Process all shortcodes in content.
     * Pass -1: Site URL shortcodes
     * Pass 0:  [cms-form id=N]
     * Pass 1a: [code-embed:N] / [code-embed:N label="..."]
     * Pass 1b: [download:N]  / [download:N label="..."]
     * Pass 2:  [plugin:slug key=value]
     */
    public function process(string $content): string
    {
        // Pass -1 — Site URL shortcodes
        $content = str_replace(['[site_url]', '[site-url]', '[url]'], url('/'), $content);

        // Normalize HTML entities that WYSIWYG editors might inject into shortcodes
        $content = str_replace(['&#91;', '&#93;', '&lsqb;', '&rsqb;'], ['[', ']', '[', ']'], $content);

        // Pass 0 — CMS Form shortcodes
        $content = $this->processCmsForms($content);

        // Pass 1a — CMS Code/Video Embed shortcodes
        $content = $this->processCodeEmbeds($content);

        // Pass 1b — CMS Download shortcodes
        $content = $this->processDownloads($content);

        // Pass 2 — Plugin shortcodes (flexible whitespace around colon and parameters)
        $pattern = '/\[plugin:\s*([a-z0-9\-_]+)([^\]]*)\]/i';

        return preg_replace_callback($pattern, function (array $matches) {
            $slug   = strtolower(trim($matches[1]));
            $params = $this->parseParams(trim($matches[2] ?? ''));

            return $this->renderPlugin($slug, $params);
        }, $content);
    }

    // ── Pass 0: CMS Form shortcodes ──────────────────────────────────────────

    /**
     * Process [cms-form id=N] shortcodes.
     */
    protected function processCmsForms(string $content): string
    {
        // Matches [cms-form id=5] or [cms-form id="5"]
        $pattern = '/\[cms-form([^\]]*)\]/i';

        return preg_replace_callback($pattern, function (array $matches) {
            $params = $this->parseParams(trim($matches[1] ?? ''));
            $id     = isset($params['id']) ? (int) $params['id'] : 0;

            return $this->renderCmsForm($id);
        }, $content);
    }

    /**
     * Render a single [cms-form id=N] shortcode to HTML.
     */
    protected function renderCmsForm(int $id): string
    {
        if ($id <= 0) {
            return '<!-- [cms-form: missing id param] -->';
        }

        try {
            // Eager-load active language translations for form and fields
            $form = CmsForm::with(['fields' => function ($q) {
                $q->withCurrentTranslations();
            }])->withCurrentTranslations()->find($id);

            if (! $form || ! $form->is_active) {
                return '<!-- [cms-form-inactive: ' . $id . '] -->';
            }

            return view('cms.form-embed', compact('form'))->render();

        } catch (\Throwable $e) {
            \Log::error("[ShortcodeProcessor] CmsForm #{$id} render error: " . $e->getMessage());
            return '<!-- [cms-form-error: ' . $id . '] -->';
        }
    }


    /**
     * Process [code-embed:N] and [code-embed:N label="..."] shortcodes.
     */
    protected function processCodeEmbeds(string $content): string
    {
        $pattern = '/\[code-embed:(\d+)([^\]]*)\]/i';

        return preg_replace_callback($pattern, function (array $matches) {
            $id     = (int) $matches[1];
            $params = $this->parseParams(trim($matches[2] ?? ''));

            return $this->renderCodeEmbed($id, $params);
        }, $content);
    }

    /**
     * Tracks whether the embed CSS wrapper has been output in this request.
     * Prevents duplicate <style> tags when multiple embed shortcodes appear on one page.
     */
    private static bool $embedCssLoaded = false;

    /**
     * Render a single [code-embed:N] shortcode to HTML.
     *
     * - YouTube / Vimeo (embed_type 0 or 1) → responsive 16:9 wrapper div
     * - Other HTML (embed_type 2)            → raw code_snippet verbatim
     * - Inactive or not found                → HTML comment
     */
    protected function renderCodeEmbed(int $id, array $params): string
    {
        try {
            $embed = CmsEmbed::find($id);

            if (!$embed || !$embed->is_active) {
                return '<!-- [embed-inactive: ' . $id . '] -->';
            }

            $snippet = $embed->code_snippet ?? '';

            if (empty(trim($snippet))) {
                return '<!-- [embed-empty: ' . $id . '] -->';
            }

            if ($embed->isVideo()) {
                // Emit responsive wrapper CSS once per request
                $css = '';
                if (!static::$embedCssLoaded) {
                    static::$embedCssLoaded = true;
                    $css = '<style id="cms-embed-css">' .
                        '.cms-embed-video-outer{max-width:75%;margin:0 auto;}' .
                        '.cms-embed-video-wrapper{position:relative;padding-bottom:56.25%;height:0;overflow:hidden;}' .
                        '.cms-embed-video-wrapper iframe,' .
                        '.cms-embed-video-wrapper object,' .
                        '.cms-embed-video-wrapper embed{position:absolute;top:0;left:0;width:100%;height:100%;}' .
                        '@media(max-width:1000px){.cms-embed-video-outer{max-width:100%;}}' .
                        '</style>';
                }

                return $css
                    . '<div class="cms-embed-video-outer">'
                    .   '<div class="cms-embed-video-wrapper">'
                    .     $snippet
                    .   '</div>'
                    . '</div>';
            }

            // Other HTML — output verbatim
            return $snippet;

        } catch (\Throwable $e) {
            \Log::error("[ShortcodeProcessor] CodeEmbed #{$id} render error: " . $e->getMessage());
            return '<!-- [embed-error: ' . $id . '] -->';
        }
    }

    /**
     * Process [download:UUID] and [download:UUID label="Custom Label"] shortcodes.
     * Also accepts legacy numeric IDs for backward compatibility (looks up by id).
     */
    protected function processDownloads(string $content): string
    {
        // Matches both UUID format and legacy numeric IDs
        $pattern = '/\[download:([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}|\d+)([^\]]*)\]/i';

        return preg_replace_callback($pattern, function (array $matches) {
            $token  = trim($matches[1]);
            $params = $this->parseParams(trim($matches[2] ?? ''));

            return $this->renderDownload($token, $params);
        }, $content);
    }

    /**
     * Tracks whether Video.js assets have already been output in this request.
     * Prevents duplicate <link>/<script> tags when multiple video/audio shortcodes appear on one page.
     */
    private static bool $videoJsLoaded = false;

    /**
     * Render a single [download:UUID] shortcode to HTML.
     *
     * Rendering strategy based on file type (when force_download is false):
     *   - Image (png/jpg/gif/webp/etc.) → <img> tag inline
     *   - Video (mp4/webm/mov)          → Video.js <video> player
     *   - Audio (mp3)                   → Video.js <audio> player
     *   - Everything else               → <a> download link with optional positioned file-type icon
     */
    protected function renderDownload(string $token, array $params): string
    {
        try {
            // Support both UUID and legacy numeric id
            $download = is_numeric($token)
                ? CmsDownload::find((int) $token)
                : CmsDownload::where('uuid', $token)->first();

            if (!$download || !$download->is_active || $download->isExpired()) {
                return '<!-- [download-inactive: ' . e($token) . '] -->';
            }

            // $id is used for CSS class names and style tag IDs throughout this method
            $id = $download->id;

            $ext        = $download->fileExtension() ?? '';
            $label      = $download->resolvedLinkLabel($params['label'] ?? '');
            $href       = url('/cms-download/' . $download->uuid);
            $target     = $download->open_in_new_tab ? ' target="_blank" rel="noopener noreferrer"' : '';
            $poster     = $download->posterImageUrl() ?? '';
            $randomId   = 'cms-media-' . $download->id . '-' . mt_rand(1000, 99999);
            $packClass  = $this->resolveIconPackClass(); // e.g. 'fiv-viv'

            // Resolve actual file URL for inline image/media players (bypasses download controller)
            // For players we embed the source directly so Video.js can control it.
            // For the download link we always route through the controller for security.
            $mediaUrl = $href; // default: route through controller

            // -----------------------------------------------------------------
            // Branch 1: Inline Image
            // -----------------------------------------------------------------
            if ($download->isImage() && !$download->force_download) {
                $output = '<img src="' . e($href) . '" alt="' . e($label) . '" class="cms-download-image" style="max-width:100%;">';

                if (!empty($download->custom_css)) {
                    $output = '<style id="cms-dl-style-' . $id . '">' . $download->custom_css . '</style>' . $output;
                }
                return $output;
            }

            // -----------------------------------------------------------------
            // Branch 2: Video Player (mp4 / webm / mov)
            // -----------------------------------------------------------------
            if ($download->isVideo() && !$download->force_download) {
                $videoJsAssets = $this->videoJsAssets();
                $mimeType      = $download->mimeType();
                $posterAttr    = $poster ? ' poster="' . e($poster) . '"' : '';

                $output = $videoJsAssets
                    . '<div class="cms-video-display-container">'
                    .   '<div class="cms-video-display">'
                    .     '<video id="' . $randomId . '"'
                    .           ' class="video-js vjs-fluid vjs-default-skin"'
                    .           ' controls'
                    .           ' preload="auto"'
                    .           ' width="100%"'
                    .           ' height="100%"'
                    .           $posterAttr . '>'
                    .       '<source src="' . e($href) . '" type="' . e($mimeType) . '">'
                    .     '</video>'
                    .   '</div>'
                    . '</div>'
                    . '<script>document.addEventListener("DOMContentLoaded",function(){if(typeof videojs!=="undefined"){videojs("' . $randomId . '");}});</script>';

                if (!empty($download->custom_css)) {
                    $output = '<style id="cms-dl-style-' . $id . '">' . $download->custom_css . '</style>' . $output;
                }
                return $output;
            }

            // -----------------------------------------------------------------
            // Branch 3: Audio Player (mp3)
            // -----------------------------------------------------------------
            if ($download->isAudio() && !$download->force_download) {
                $videoJsAssets = $this->videoJsAssets();
                $posterAttr    = $poster ? ' poster="' . e($poster) . '"' : '';

                $output = $videoJsAssets
                    . '<div class="cms-audio-display-container">'
                    .   '<div class="cms-audio-display">'
                    .     '<audio id="' . $randomId . '"'
                    .           ' class="video-js vjs-fluid vjs-default-skin"'
                    .           ' controls'
                    .           ' preload="auto"'
                    .           $posterAttr . '>'
                    .       '<source src="' . e($href) . '" type="audio/mpeg">'
                    .     '</audio>'
                    .   '</div>'
                    . '</div>'
                    . '<script>document.addEventListener("DOMContentLoaded",function(){if(typeof videojs!=="undefined"){videojs("' . $randomId . '");}});</script>';

                if (!empty($download->custom_css)) {
                    $output = '<style id="cms-dl-style-' . $id . '">' . $download->custom_css . '</style>' . $output;
                }
                return $output;
            }

            // -----------------------------------------------------------------
            // Branch 4: Download Link with positioned file-type icon
            // show_icon: 0=none, 1=left, 2=right, 3=top, 4=bottom
            // -----------------------------------------------------------------
            $showIcon = (int) $download->show_icon;
            $iconHtml = '';

            if ($showIcon > 0 && $ext) {
                // Flex layout configuration per position
                [$flexType, $flexDir, $iconOrder, $labelOrder, $iconWidth] = match ($showIcon) {
                    1 => ['inline-flex', 'row',    0, 1, 'auto'],    // icon left
                    2 => ['inline-flex', 'row',    1, 0, 'auto'],    // icon right
                    3 => ['flex',        'column', 0, 1, '100%'],    // icon top
                    4 => ['flex',        'column', 1, 0, '100%'],    // icon bottom
                    default => ['inline-flex', 'row', 0, 1, 'auto'],
                };

                $styleTag = '<style id="cms-dl-style-' . $id . '">'
                    . '.cms-link-container-' . $id . '{'
                    .   'display:' . $flexType . ';'
                    .   'flex-direction:' . $flexDir . ';'
                    .   'align-items:center;gap:6px;'
                    . '}'
                    . '.cms-link-icon-' . $id . '{'
                    .   'order:' . $iconOrder . ';width:' . $iconWidth . ';'
                    . '}'
                    . '.cms-link-label-' . $id . '{'
                    .   'order:' . $labelOrder . ';'
                    . '}'
                    . '</style>';

                $iconHtml = '<div class="cms-link-icon-' . $id . '">'
                    .   '<span class="fiv-cla ' . $packClass . ' fiv-icon-' . e($ext) . '"'
                    .         ' style="font-size:2em;line-height:1;"'
                    .         ' title=".' . strtoupper(e($ext)) . ' file"></span>'
                    . '</div>';

                $output = $styleTag
                    . '<a href="' . e($href) . '" class="cms-link"' . $target . '>'
                    .   '<div class="cms-link-container-' . $id . '">'
                    .     $iconHtml
                    .     '<div class="cms-link-label-' . $id . '">' . e($label) . '</div>'
                    .   '</div>'
                    . '</a>';

                // Custom CSS override (appended to the same block)
                if (!empty($download->custom_css)) {
                    $output = str_replace('</style>', $download->custom_css . '</style>', $output);
                }

                return $output;
            }

            // No icon — plain link
            $output = '<a href="' . e($href) . '" class="cms-download-link"' . $target . '>' . e($label) . '</a>';

            if (!empty($download->custom_css)) {
                $output = '<style id="cms-dl-style-' . $id . '">' . $download->custom_css . '</style>' . $output;
            }

            return $output;

        } catch (\Throwable $e) {
            \Log::error("[ShortcodeProcessor] Download '{$token}' render error: " . $e->getMessage());
            return '<!-- [download-error: ' . e($token) . '] -->';
        }
    }

    /**
     * Returns Video.js CSS + JS assets HTML.
     * Outputs them only once per PHP request using a static flag.
     */
    private function videoJsAssets(): string
    {
        if (static::$videoJsLoaded) {
            return '';
        }
        static::$videoJsLoaded = true;

        return '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/video.js/8.5.0/video-js.min.css" integrity="sha512-Oh3WHEemzP10ZRZNZYLfQ6udAd1U53SJk/yLcjdQ7mneqWXfJ5kVc2cdgZlmGIKbRAnz/VcuLCgtJ8Xi70DeWQ==" crossorigin="anonymous" referrerpolicy="no-referrer">'
             . '<script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/8.5.0/video.min.js" integrity="sha512-ukno7nvJS8h8P+++oaiXPIm/4uyl5PumrJ5w/Wgsf4OgMZHlUneEVmDMzn7srh5kFgkPrmBZ30oPKdCP8fdHkw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>';
    }

    /**
     * Resolves the file-icon-vectors pack CSS class from the site setting.
     *
     *  vivid   → fiv-viv  (colourful filled — default)
     *  classic → fiv-cla  (monochrome)
     *  square  → fiv-sqo  (square outline)
     *
     * Result is cached in a static property so the DB is only hit once per request
     * even when multiple [download:N] shortcodes appear on the same page.
     */
    private static ?string $resolvedPackClass = null;

    private function resolveIconPackClass(): string
    {
        if (static::$resolvedPackClass !== null) {
            return static::$resolvedPackClass;
        }

        $pack = \App\Models\CmsSetting::get('file_icon_pack', 'vivid');

        static::$resolvedPackClass = match ($pack) {
            'classic' => 'fiv-cla',
            'square'  => 'fiv-sqo',
            default   => 'fiv-viv',   // vivid (also the fallback)
        };

        return static::$resolvedPackClass;
    }


    /**
     * Parse space-separated key=value or key="value with spaces" params.
     */
    protected function parseParams(string $paramString): array
    {
        $params = [];
        if (empty($paramString)) return $params;

        // Match: key="value" or key='value' or key=value
        preg_match_all('/([\w_]+)=(?:"([^"]*)"|\x27([^\x27]*)\x27|([^\s\]]+))/', $paramString, $m, PREG_SET_ORDER);
        foreach ($m as $match) {
            $key   = $match[1];
            $value = $match[2] !== '' ? $match[2] : ($match[3] !== '' ? $match[3] : $match[4]);
            $params[$key] = $value;
        }

        return $params;
    }

    /**
     * Render a plugin by slug. Returns empty string on error (silent to end-users).
     */
    protected function renderPlugin(string $slug, array $params): string
    {
        $normalizedSlug = str_replace('_', '-', strtolower(trim($slug)));

        $pluginInstance = $this->manager->getDisplay($normalizedSlug);

        if (!$pluginInstance) {
            if (in_array($normalizedSlug, ['pricing-grid', 'pricing-grid-2026', 'pricing', 'pricing-table', 'visperity-pricing', 'visperity-pricing-grid', 'visperity-plans', 'plans'], true)) {
                $pluginInstance = $this->manager->getDisplay('pricing-grid-2026');
            }
        }

        if (!$pluginInstance) {
            // Plugin not registered — silent for visitors, comment for devs
            return '<!-- [plugin-not-found: ' . e($slug) . '] -->';
        }

        // Load DB record for settings
        $pluginModel = Plugin::where('shortcode', $slug)->first()
            ?? Plugin::where('shortcode', $pluginInstance->slug())->first()
            ?? new Plugin(['shortcode' => $pluginInstance->slug(), 'activation_status' => 1, 'name' => $pluginInstance->name()]);

        if ($pluginModel->exists && !$pluginModel->activation_status) {
            return '<!-- [plugin-inactive: ' . e($slug) . '] -->';
        }

        try {
            return $pluginInstance->render($params, $pluginModel);
        } catch (\Throwable $e) {
            \Log::error("[ShortcodeProcessor] Plugin '{$slug}' render error: " . $e->getMessage());
            return '<!-- [plugin-error: ' . e($slug) . '] -->';
        }
    }
}
