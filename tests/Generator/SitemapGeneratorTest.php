<?php

declare(strict_types=1);

namespace JeroenDesloovere\Tests\Sitemap\Generator;

use JeroenDesloovere\Sitemap\Generator\SitemapGenerator;
use JeroenDesloovere\Sitemap\Item\ChangeFrequency;
use JeroenDesloovere\Sitemap\Provider\SitemapProvider;
use JeroenDesloovere\Sitemap\Provider\SitemapProviderInterface;
use JeroenDesloovere\Sitemap\Provider\SitemapProviders;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
final class SitemapGeneratorTest extends TestCase
{
    /** @var SitemapProviders */
    private $providers;

    /** @var vfsStreamDirectory - We save the generated sitemaps to a virtual storage */
    private $virtualStorage;

    public function setUp(): void
    {
        $this->providers = new SitemapProviders();
        $this->providers->add(new TestPageSitemapProvider());
        $this->providers->add(new TestBlogArticleSitemapProvider());
        $this->providers->add(new TestBlogCategorySitemapProvider());
        $this->providers->add(new TestEmptySitemapProvider());
        $this->virtualStorage = vfsStream::setup();
    }

    public function testGenerate(): void
    {
        $generator = new SitemapGenerator('http://localhost', __DIR__, $this->providers);

        // Overwrite the path to a virtual one for our tests
        $generator->setPath($this->virtualStorage->url());

        // Test if generate is working
        $generator->generate();
        $this->assertTrue($this->virtualStorage->hasChild('sitemap.xml'));
        $this->assertTrue($this->virtualStorage->hasChild('sitemap_Page.xml'));
        $this->assertTrue($this->virtualStorage->hasChild('sitemap_BlogArticle.xml'));
        $this->assertTrue($this->virtualStorage->hasChild('sitemap_BlogCategory.xml'));
    }

    public function testItems(): void
    {
        $this->assertEquals(4, count($this->providers->getAll()));
    }
}

class TestBlogArticleSitemapProvider extends SitemapProvider implements SitemapProviderInterface
{
    public function __construct()
    {
        parent::__construct('BlogArticle');
    }

    public function createItems(): void
    {
        foreach ($this->getDummyPages() as $page) {
            $this->createItem($page['url'], $page['editedOn'], ChangeFrequency::monthly());
        }
    }

    private function getDummyPages(): array
    {
        return [
            [
                'url' => '/nl/blog/article/my-first-blog-article',
                'editedOn' => new \DateTime(),
            ],
            [
                'url' => '/nl/blog/article/my-second-blog-article',
                'editedOn' => new \DateTime(),
            ]
        ];
    }
}

class TestBlogCategorySitemapProvider extends SitemapProvider implements SitemapProviderInterface
{
    public function __construct()
    {
        parent::__construct('BlogCategory');
    }

    public function createItems(): void
    {
        foreach ($this->getDummyPages() as $page) {
            $this->createItem($page['url'], $page['editedOn'], ChangeFrequency::monthly());
        }
    }

    private function getDummyPages(): array
    {
        return [
            [
                'url' => '/nl/blog/category/sitemap-generator',
                'editedOn' => new \DateTime(),
            ],
            [
                'url' => '/nl/blog/category/symfony-bundle',
                'editedOn' => new \DateTime(),
            ]
        ];
    }
}

class TestPageSitemapProvider extends SitemapProvider implements SitemapProviderInterface
{
    public function __construct()
    {
        parent::__construct('App\\PagesBundle\\Entity\\Page');
    }

    public function createItems(): void
    {
        foreach ($this->getDummyPages() as $page) {
            $this->createItem($page['url'], $page['editedOn'], ChangeFrequency::monthly());
        }
    }

    private function getDummyPages(): array
    {
        return [
            [
                'url' => '/nl/first-page',
                'editedOn' => new \DateTime(),
            ],
            [
                'url' => '/nl/second-page',
                'editedOn' => new \DateTime(),
            ]
        ];
    }
}

class TestEmptySitemapProvider extends SitemapProvider implements SitemapProviderInterface
{
    public function __construct()
    {
        parent::__construct('Empty');
    }

    public function createItems(): void
    {
        // no items
    }
}

namespace App\PagesBundle\Entity;

class Page
{
    // Empty class for testing
}
