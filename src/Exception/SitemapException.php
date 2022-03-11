<?php

namespace JeroenDesloovere\Sitemap\Exception;

class SitemapException extends \Exception
{
    public static function forEmptyPath(): self
    {
        return new self('The path you have given is empty.');
    }

    public static function forEmptyUrl(): self
    {
        return new self('The url you have given is empty.');
    }

    public static function forNotExistingChangeFrequency(string $changeFrequency): self
    {
        return new self('The given changeFrequency "' . $changeFrequency . '" does not exist.');
    }

    public static function forNotExistingSitemapProvider($sitemapProviderKey): self
    {
        return new self('The requested sitemap provider with key "' . $sitemapProviderKey . '" does not exist.');
    }

    public static function forPriority(): self
    {
        return new self('Priority must be a value between 0 and 10.');
    }
}
