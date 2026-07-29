<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CmsDownload extends Model
{
    use HasFactory;

    protected $table = 'cms_downloads';

    // source_type constants
    const SOURCE_LOCAL      = 0;
    const SOURCE_DIRECT_URL = 1;
    const SOURCE_ENV_S3     = 2;
    const SOURCE_CUSTOM_S3  = 3;

    protected $fillable = [
        'uuid',
        'internal_name',
        'link_label',
        'is_active',
        'expires_at',
        'force_download',
        'open_in_new_tab',
        'show_icon',
        'custom_css',
        'source_type',
        // Local
        'file_path',
        // Direct URL
        'cdn_url',
        // Env S3
        's3_file_key',
        's3_expiration_seconds',
        // Custom S3
        's3_custom_key',
        's3_custom_secret',
        's3_custom_region',
        's3_custom_bucket',
        's3_custom_file_key',
        's3_custom_expiration_seconds',
        // Poster image
        'poster_image_path',
        'poster_image_cdn_url',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'force_download'  => 'boolean',
        'open_in_new_tab' => 'boolean',
        'show_icon'       => 'integer',
        'expires_at'      => 'datetime',
        'source_type'     => 'integer',
    ];

    /**
     * Auto-generate a UUID for every new CmsDownload record.
     */
    protected static function booted(): void
    {
        static::creating(function (CmsDownload $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Use UUID as the route key instead of numeric id.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Whether this download's access window has passed.
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Resolve the display label from: shortcode override → link_label → internal_name.
     */
    public function resolvedLinkLabel(string $override = ''): string
    {
        if ($override !== '') return $override;
        if (!empty($this->link_label)) return $this->link_label;
        return $this->internal_name;
    }

    /**
     * Extract the file extension from whichever source field is configured.
     * Used for file-icon-vectors icon rendering and media type detection.
     */
    public function fileExtension(): ?string
    {
        $ext = match ((int) $this->source_type) {
            self::SOURCE_LOCAL      => $this->file_path
                                        ? pathinfo($this->file_path, PATHINFO_EXTENSION)
                                        : null,
            self::SOURCE_DIRECT_URL => $this->cdn_url
                                        ? pathinfo(parse_url($this->cdn_url, PHP_URL_PATH), PATHINFO_EXTENSION)
                                        : null,
            self::SOURCE_ENV_S3     => $this->s3_file_key
                                        ? pathinfo($this->s3_file_key, PATHINFO_EXTENSION)
                                        : null,
            self::SOURCE_CUSTOM_S3  => $this->s3_custom_file_key
                                        ? pathinfo($this->s3_custom_file_key, PATHINFO_EXTENSION)
                                        : null,
            default                 => null,
        };

        return ($ext && $ext !== '') ? strtolower($ext) : null;
    }

    /**
     * Returns the MIME type string for the file's extension.
     * Used by the shortcode renderer to decide which player/tag to use.
     */
    public function mimeType(): string
    {
        $ext = $this->fileExtension() ?? '';
        $map = [
            // Images
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpe'  => 'image/jpeg',
            'gif'  => 'image/gif',
            'bmp'  => 'image/bmp',
            'webp' => 'image/webp',
            'svg'  => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            'tif'  => 'image/tiff',
            'tiff' => 'image/tiff',
            'ico'  => 'image/vnd.microsoft.icon',
            // Video
            'mp4'  => 'video/mp4',
            'webm' => 'video/webm',
            'mov'  => 'video/quicktime',
            'qt'   => 'video/quicktime',
            // Audio
            'mp3'  => 'audio/mpeg',
            // Archives
            'zip'  => 'application/zip',
            'rar'  => 'application/x-rar-compressed',
            'exe'  => 'application/x-msdownload',
            'msi'  => 'application/x-msdownload',
            'cab'  => 'application/vnd.ms-cab-compressed',
            // Adobe
            'pdf'  => 'application/pdf',
            'psd'  => 'image/vnd.adobe.photoshop',
            'ai'   => 'application/postscript',
            'eps'  => 'application/postscript',
            'ps'   => 'application/postscript',
            // MS Office
            'doc'  => 'application/msword',
            'docx' => 'application/msword',
            'xls'  => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.ms-excel',
            'ppt'  => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.ms-powerpoint',
            'rtf'  => 'application/rtf',
            // Open Office
            'odt'  => 'application/vnd.oasis.opendocument.text',
            'ods'  => 'application/vnd.oasis.opendocument.spreadsheet',
            // Web
            'txt'  => 'text/plain',
            'htm'  => 'text/html',
            'html' => 'text/html',
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'json' => 'application/json',
            'xml'  => 'application/xml',
        ];
        return $map[$ext] ?? 'application/octet-stream';
    }

    /** True when the file is an image type (renders inline as <img>). */
    public function isImage(): bool
    {
        return in_array($this->fileExtension(), ['png','jpg','jpeg','jpe','gif','bmp','webp','svg','svgz','tif','tiff']);
    }

    /** True when the file is a playable video type. */
    public function isVideo(): bool
    {
        return in_array($this->fileExtension(), ['mp4','webm','mov','qt']);
    }

    /** True when the file is a playable audio type. */
    public function isAudio(): bool
    {
        return in_array($this->fileExtension(), ['mp3']);
    }

    /**
     * Resolved poster image URL (CDN takes priority over local storage).
     */
    public function posterImageUrl(): ?string
    {
        if (!empty($this->poster_image_cdn_url)) {
            return $this->poster_image_cdn_url;
        }
        if (!empty($this->poster_image_path)) {
            return Storage::disk('public')->url($this->poster_image_path);
        }
        return null;
    }

    /**
     * Human-readable source type label.
     */
    public function sourceTypeLabel(): string
    {
        return match ((int) $this->source_type) {
            self::SOURCE_LOCAL      => 'Local',
            self::SOURCE_DIRECT_URL => 'Direct URL',
            self::SOURCE_ENV_S3     => 'Env S3',
            self::SOURCE_CUSTOM_S3  => 'Custom S3',
            default                 => 'Unknown',
        };
    }

    /**
     * Tailwind badge color classes for source type.
     */
    public function sourceTypeBadgeColor(): string
    {
        return match ((int) $this->source_type) {
            self::SOURCE_LOCAL      => 'bg-slate-100 text-slate-700 border-slate-200',
            self::SOURCE_DIRECT_URL => 'bg-sky-100 text-sky-700 border-sky-200',
            self::SOURCE_ENV_S3     => 'bg-amber-100 text-amber-700 border-amber-200',
            self::SOURCE_CUSTOM_S3  => 'bg-violet-100 text-violet-700 border-violet-200',
            default                 => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
