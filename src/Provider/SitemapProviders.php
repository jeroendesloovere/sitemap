<?php

namespace JeroenDesloovere\Sitemap\Provider;

use JeroenDesloovere\Sitemap\Exception\SitemapException;

class SitemapProviders
{
    /** @var SitemapProviderInterface[] */
    private $sitemapProviders = [];

    public function add(SitemapProviderInterface $sitemapProvider): void
    {
        $this->sitemapProviders[$sitemapProvider->getKey()] = $sitemapProvider;
    }

    public function exists(string $sitemapProviderKey): bool
    {
        return array_key_exists($sitemapProviderKey, $this->sitemapProviders);
    }

    /**
     * @param string $sitemapProviderKey
     * @return SitemapProviderInterface
     * @throws SitemapException
     */
    public function get(string $sitemapProviderKey): SitemapProviderInterface
    {
        if (!$this->exists($sitemapProviderKey)) {
            throw SitemapException::forNotExistingSitemapProvider($sitemapProviderKey);
        }

        return $this->sitemapProviders[$sitemapProviderKey];
    }

    public function getAll(): array
    {
        return $this->sitemapProviders;
    }

    public function getKeys(): array
    {
        return array_keys($this->sitemapProviders);
    }
}
