<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Enums;

enum VideoSource: string
{
    case YOUTUBE = 'youtube';
    case UPLOAD = 'upload';
    case VIMEO = 'vimeo';
    case GDRIVE = 'gdrive';
    case MP4 = 'mp4';
    case MKV = 'mkv';
    case WEBM = 'webm';
    case M3U8 = 'm3u8';
    case EMBED = 'embed';

    public static function all(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }

    public function getUrl(string $url): string
    {
        return match ($this) {
            self::YOUTUBE => $this->getVideoYoutubeUrl($url),
            self::VIMEO => $this->getVideoVimeoUrl($url),
            self::UPLOAD => $this->getVideoUpload($url),
            self::GDRIVE => $this->getVideoGoogleDrive($url),
            default => $this->getVideoUrl($url),
        };
    }

    public function getSourceByUrl(string $url): VideoSource
    {
        $domain = get_domain_by_url($url, true);

        return match ($domain) {
            'youtube.com' => self::YOUTUBE,
            'vimeo.com' => self::VIMEO,
            'drive.google.com' => self::GDRIVE,
            default => self::MP4,
        };
    }

    public function isSourceEmbed(): bool
    {
        $embedSources = ['embed', 'youtube', 'vimeo'];

        if (in_array($this->value, $embedSources)) {
            return true;
        }

        return false;
    }

    public function getMimeType(): string
    {
        return match ($this) {
            self::YOUTUBE => 'video/youtube',
            self::VIMEO => 'video/vimeo',
            self::UPLOAD => 'video/mp4',
            self::GDRIVE => 'embed/google-drive',
            default => 'video/' . $this->value,
        };
    }

    protected function getVideoYoutubeUrl(string $url): string
    {
        return 'https://www.youtube.com/embed/' . get_youtube_id($url);
    }

    protected function getVideoVimeoUrl(string $url): string
    {
        return 'https://player.vimeo.com/video/' . get_vimeo_id($url);
    }

    protected function getVideoGoogleDrive(string $url): string
    {
        return 'https://drive.google.com/file/d/'. get_google_drive_id($url) .'/preview';
    }

    protected function getVideoUpload(string $url): string
    {
        return '';
    }

    public function getVideoUrl(string $url): string
    {
        return $url;
    }

    public function label(): string
    {
        return match($this) {
            self::YOUTUBE => 'Youtube',
            self::UPLOAD => 'Upload Video',
            self::VIMEO => 'Vimeo',
            self::GDRIVE => 'Google Drive',
            self::MP4 => 'MP4 URL',
            self::MKV => 'MKV URL',
            self::WEBM => 'WEBM URL',
            self::M3U8 => 'M3U8 URL',
            self::EMBED => 'Embed URL',
        };
    }
}
