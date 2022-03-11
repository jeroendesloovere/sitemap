<?php

namespace JeroenDesloovere\Sitemap\Item;

class SitemapItems
{
    /** @var SitemapItem[] */
    private $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function add(SitemapItem $item): void
    {
        $this->items[] = $item;
    }

    public function getAll(): array
    {
        return $this->items;
    }
}
