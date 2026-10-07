<?php

namespace Tests\Unit;

use App\Services\KoreanGlamParser;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class KoreanGlamParserTest extends TestCase
{
    public function test_catalogue_links_are_unique_and_exclude_other_hosts(): void
    {
        $html = '<a href="/produit/12?slug=test">a</a><a href="https://korean-glam.tn/produit/12">b</a><a href="/produit/13">c</a><a href="https://other.test/produit/14">d</a>';
        $this->assertSame([12, 13], (new KoreanGlamParser)->productIds($html));
    }

    public function test_full_description_stock_promotion_and_images_are_preserved(): void
    {
        $html = <<<'HTML'
<script type="application/ld+json">{"@type":"Product","name":"Crème éclaircissante","description":"Résumé","brand":{"name":"ANUA"},"offers":{"price":"108","priceCurrency":"TND","availability":"https://schema.org/InStock"}}</script>
<img id="MainImage" src="/storage/main.jpg">
<div class="card-produit-img-details-list"><img src="/storage/second.jpg"><img src="/storage/main.jpg"></div>
<div class="col-md-8"><h3>Crème éclaircissante</h3><div>In stock (7)</div><b>108<sup>DT</sup></b><strike>135.000<sup>DT</sup></strike><p class="text-muted">Description complète &amp; détaillée.</p></div>
HTML;
        $data = (new KoreanGlamParser)->parse($html, 12);
        $this->assertSame('Crème éclaircissante', $data['name']);
        $this->assertSame('Description complète & détaillée.', $data['description']);
        $this->assertSame('108', $data['price']);
        $this->assertSame('135.000', $data['original_price']);
        $this->assertSame(7, $data['stock']);
        $this->assertSame('ANUA', $data['brand']);
        $this->assertCount(2, json_decode($data['images'], true));
    }

    public function test_invalid_structured_data_falls_back_to_visible_fields(): void
    {
        $data = (new KoreanGlamParser)->parse('<script type="application/ld+json">invalid</script><div class="col-md-8"><h3>Serum</h3><b>125.000<sup>DT</sup></b>Out of stock</div>', 13);
        $this->assertSame('125.000', $data['price']);
        $this->assertSame(0, $data['stock']);
        $this->assertNull($data['structured_data']);
    }

    public function test_error_page_cannot_be_imported_as_a_product(): void
    {
        $this->expectException(RuntimeException::class);
        (new KoreanGlamParser)->parse('<h1>Service unavailable</h1>', 14);
    }
}
