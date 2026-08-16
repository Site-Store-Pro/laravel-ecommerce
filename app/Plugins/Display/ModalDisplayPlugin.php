<?php

namespace App\Plugins\Display;

use App\Models\CmsModal;
use App\Models\Plugin;
use App\Plugins\Contracts\DisplayPlugin;
use Illuminate\Support\Facades\Log;

class ModalDisplayPlugin implements DisplayPlugin
{
    public function slug(): string
    {
        return 'modal';
    }

    public function name(): string
    {
        return 'CMS Modal Display';
    }

    public function render(array $params, Plugin $plugin): string
    {
        try {
            $settings = $plugin->getSettings();

            $id = $params['id'] ?? $params[0] ?? null;

            if (empty($id) || !is_numeric($id)) {
                return '<!-- [plugin:modal] Missing or invalid id parameter -->';
            }

            $modal = CmsModal::where('id', (int) $id)->where('is_active', true)->first();

            if (!$modal) {
                return '<!-- [plugin:modal id=' . (int) $id . '] Modal not found or inactive -->';
            }

            $mid            = (int) $modal->id;
            $cookieKey      = e($modal->cookieKey());
            $cookieLifetime = (int) $modal->cookie_lifetime;
            $autoOpen       = $modal->auto_open ? 'true' : 'false';
            $openDelay      = (int) $modal->open_delay;
            $overlayDismiss = $modal->overlay_dismissible ? 'true' : 'false';
            $triggerSel     = $modal->trigger_selector ? e($modal->trigger_selector) : '';
            $body           = $modal->getTranslated('body');

            [$wrapClass, $panelClass] = match ($modal->position) {
                'left'   => [
                    'h-full w-full flex items-stretch justify-start pointer-events-none',
                    'relative w-full max-w-md bg-white dark:bg-slate-900 shadow-2xl flex flex-col pointer-events-auto',
                ],
                'right'  => [
                    'h-full w-full flex items-stretch justify-end pointer-events-none',
                    'relative w-full max-w-md bg-white dark:bg-slate-900 shadow-2xl flex flex-col pointer-events-auto',
                ],
                'bottom' => [
                    'h-full w-full flex flex-col justify-end pointer-events-none',
                    'relative w-full bg-white dark:bg-slate-900 shadow-2xl rounded-t-3xl flex flex-col pointer-events-auto max-h-[85vh]',
                ],
                default  => [
                    'h-full w-full flex items-center justify-center p-4 pointer-events-none',
                    'relative bg-white dark:bg-slate-900 rounded-3xl shadow-2xl flex flex-col pointer-events-auto',
                ],
            };

            $bgColor = !empty($modal->bg_color) ? e($modal->bg_color) : '#ffffff';
            $panelStyle = 'background-color:' . $bgColor . ';';
            if ($modal->position === 'center' && !empty($modal->max_width)) {
                $panelStyle .= 'max-width:' . e($modal->max_width) . ';width:100%;';
            }

            // ── Plugin-level CSS (default base + custom overrides) ────────────
            $defaultCss = $plugin->getSetting('default_css', '');
            $customCss  = $params['custom_css'] ?? ($settings['custom_css'] ?? '');

            $hasBackdrop = (bool) ($modal->backdrop_blur ?? true);

            // Scoped CSS — per-instance modal rules
            $styleRules = '#cms-modal-outer-' . $mid . ' { display: none; } ';
            if ($hasBackdrop) {
                $styleRules .= '#cms-modal-bd-' . $mid . ' { position: fixed; inset: 0; background: rgba(15,23,42,.65); z-index: 99998; display: none; } ';
            } else {
                $styleRules .= '#cms-modal-bd-' . $mid . ' { display: none !important; } ';
            }
            $styleRules .= '#cms-modal-outer-' . $mid . ' .cms-modal-panel { background-color: ' . $bgColor . '; } ';
            if (!empty($modal->custom_css)) {
                $styleRules .= '#cms-modal-outer-' . $mid . ' .cms-modal-panel {' . $modal->custom_css . '}';
            }

            $html = '';
            // Output plugin-level CSS first (default then custom overrides)
            if (!empty($defaultCss) || !empty($customCss)) {
                $html .= '<style>';
                if (!empty($defaultCss)) {
                    $html .= \App\Services\CssMinifierService::minify($defaultCss);
                }
                if (!empty($customCss)) {
                    $html .= \App\Services\CssMinifierService::minify($customCss);
                }
                $html .= '</style>' . "\n";
            }
            $html .= '<style>' . $styleRules . '</style>' . "\n";

            // Backdrop for center/left/right/bottom (darkened opacity overlay)
            if ($hasBackdrop && in_array($modal->position, ['center', 'left', 'right', 'bottom'])) {
                $html .= '<div id="cms-modal-bd-' . $mid . '" class="fixed inset-0 bg-slate-900/65 z-[99998]" style="display:none;"></div>' . "\n";
            }

            // Close button
            $closeBtn = '';
            if ($modal->show_close_button) {
                $closeBtn = '<button type="button" '
                    . 'onclick="cmsModalClose_' . $mid . '()" '
                    . 'aria-label="Dismiss" '
                    . 'class="absolute top-4 right-4 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition z-10">'
                    . '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                    . 'Dismiss'
                    . '</button>';
            }

            $html .= '<div id="cms-modal-outer-' . $mid . '" class="fixed inset-0 z-[99999] pointer-events-none" style="display:none;" role="dialog" aria-modal="true">' . "\n";
            $html .= '  <div class="' . $wrapClass . '">' . "\n";
            $html .= '    <div class="cms-modal-panel ' . $panelClass . '" style="' . $panelStyle . '">' . "\n";
            $html .= '      <div class="relative flex flex-col h-full">' . "\n";
            $html .= '        ' . $closeBtn . "\n";
            $html .= '        <div class="p-6 overflow-y-auto flex-1 prose prose-slate max-w-none">' . "\n";
            $html .= '          <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-3">' . e($modal->getTranslated('title')) . '</h2>' . "\n";
            $html .= $body . "\n";
            $html .= '        </div>' . "\n";
            $html .= '      </div>' . "\n";
            $html .= '    </div>' . "\n";
            $html .= '  </div>' . "\n";
            $html .= '</div>' . "\n";

            // Trigger selector binding snippet
            $triggerJs = '';
            if (!empty($triggerSel)) {
                $esc = addslashes($triggerSel);
                $triggerJs = 'document.querySelectorAll("' . $esc . '").forEach(function(el){el.addEventListener("click",function(e){e.preventDefault();cmsModalOpen_' . $mid . '();});});';
            }

            // Backdrop & outer overlay click dismiss binding
            $bdOnclick = 'var outer_' . $mid . '=document.getElementById("cms-modal-outer-' . $mid . '");'
                . 'var bd_' . $mid . '=document.getElementById("cms-modal-bd-' . $mid . '");'
                . 'if(' . $overlayDismiss . '){'
                . '  if(outer_' . $mid . '){outer_' . $mid . '.addEventListener("click",function(e){if(!e.target.closest(".cms-modal-panel")){cmsModalClose_' . $mid . '();}});}'
                . '  if(bd_' . $mid . '){bd_' . $mid . '.addEventListener("click",function(){cmsModalClose_' . $mid . '();});}'
                . '}';

            $html .= '<script>
(function(){
  var mid=' . $mid . ';
  var ck="' . $cookieKey . '";
  var ckd=' . $cookieLifetime . ';
  var ao=' . $autoOpen . ';
  var od=' . $openDelay . ';

  function getCk(n){
    if(ckd <= 0) return false;
    return(document.cookie.split("; ").find(function(r){return r.startsWith(n+"=");})||"").split("=")[1];
  }
  function setCk(n,d){
    if(d <= 0) return;
    var x="";
    if(d > 0){var dt=new Date();dt.setTime(dt.getTime()+(d*864e5));x=";expires="+dt.toUTCString();}
    document.cookie=n+"=1;path=/"+x;
  }

  function relocateToBody(){
    var el=document.getElementById("cms-modal-outer-"+mid);
    var bd=document.getElementById("cms-modal-bd-"+mid);
    if(bd && bd.parentElement !== document.body) document.body.appendChild(bd);
    if(el && el.parentElement !== document.body) document.body.appendChild(el);
  }

  window["cmsModalOpen_"+mid]=function(){
    relocateToBody();
    var el=document.getElementById("cms-modal-outer-"+mid);
    var bd=document.getElementById("cms-modal-bd-"+mid);
    if(el)el.style.display="block";
    if(bd)bd.style.display="block";
    document.body.style.overflow="hidden";
  };
  window["cmsModalClose_"+mid]=function(){
    var el=document.getElementById("cms-modal-outer-"+mid);
    var bd=document.getElementById("cms-modal-bd-"+mid);
    if(el)el.style.display="none";
    if(bd)bd.style.display="none";
    document.body.style.overflow="";
    setCk(ck,ckd);
  };

  function initModal(){
    relocateToBody();
    var el=document.getElementById("cms-modal-outer-"+mid);
    if(!el)return;
    ' . $bdOnclick . '
    ' . $triggerJs . '
    el.querySelectorAll("form").forEach(function(f){f.addEventListener("submit",function(){setCk(ck,ckd);});});

    if(ao){
      if(getCk(ck))return;
      setTimeout(function(){
        window["cmsModalOpen_"+mid]();
      },od);
    }
  }

  if(document.readyState === "loading"){
    document.addEventListener("DOMContentLoaded", initModal);
  } else {
    initModal();
  }
})();
</script>' . "\n";

            return $html;

        } catch (\Throwable $e) {
            Log::error('[ModalDisplayPlugin] Render error: ' . $e->getMessage());
            return '<!-- [plugin-error: modal] ' . e($e->getMessage()) . ' -->';
        }
    }
}
