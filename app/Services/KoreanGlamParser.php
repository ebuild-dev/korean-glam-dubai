<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;
use RuntimeException;

class KoreanGlamParser
{
    public function document(string $html): DOMXPath
    {
        $previous = libxml_use_internal_errors(true);
        $doc = new DOMDocument;
        $doc->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        return new DOMXPath($doc);
    }

    public function productIds(string $html): array
    {
        $ids = [];
        foreach ($this->document($html)->query('//a[@href]') as $link) {
            if (preg_match('~^(?:https://korean-glam\.tn)?/produit/(\d+)(?:[/?#]|$)~', $link->getAttribute('href'), $m)) {
                $ids[(int) $m[1]] = (int) $m[1];
            }
        }
        return array_values($ids);
    }

    public function parse(string $html, int $id): array
    {
        $xp = $this->document($html);
        $text = static function (string $query) use ($xp): ?string {
            $node = $xp->query($query)->item(0);
            return $node ? trim(preg_replace('/\s+/u', ' ', $node->textContent)) : null;
        };
        $product = null;
        foreach ($xp->query('//script[@type="application/ld+json"]') as $node) {
            $data = json_decode(html_entity_decode($node->textContent, ENT_QUOTES | ENT_HTML5, 'UTF-8'), true);
            foreach (isset($data['@graph']) ? $data['@graph'] : (isset($data['@type']) ? [$data] : ($data ?? [])) as $entry) {
                if (is_array($entry) && in_array('Product', (array) ($entry['@type'] ?? []), true)) {
                    $product = $entry;
                    break 2;
                }
            }
        }
        $name = $text('//div[contains(@class,"col-md-8")]/h3') ?? ($product['name'] ?? null);
        $offer = $product['offers'] ?? [];
        if (isset($offer[0])) $offer = $offer[0];
        $price = trim((string) ($offer['price'] ?? ''));
        if (!is_numeric($price)) {
            $visiblePrice = $text('//div[contains(@class,"col-md-8")]//b[sup]');
            if (preg_match('/([\d]+(?:[.,]\d+)?)/', $visiblePrice ?? '', $m)) $price = str_replace(',', '.', $m[1]);
        }
        if (!$name || !is_numeric($price)) throw new RuntimeException("Produit {$id}: nom ou prix introuvable.");
        $stock = null;
        $detail = $text('//div[contains(@class,"col-md-8")]') ?? '';
        if (preg_match('/(\d+)\s+products left in stock/i', $detail, $m)) $stock = (int) $m[1];
        elseif (preg_match('/In stock\s*\((\d+)\)/i', $detail, $m)) $stock = (int) $m[1];
        elseif (stripos($detail, 'Out of stock') !== false) $stock = 0;
        $images = [];
        foreach ($xp->query('//img[@id="MainImage"] | //div[contains(@class,"card-produit-img-details-list")]//img') as $img) {
            $url = $img->getAttribute('src');
            if ($url) $images[] = $this->imageUrl($url);
        }
        if (!$images) foreach ((array) ($product['image'] ?? []) as $url) {
            if (is_string($url)) $images[] = $this->imageUrl($url);
        }
        $old = $text('//div[contains(@class,"col-md-8")]//strike');
        $originalPrice = null;
        if (preg_match('/([\d]+(?:[.,]\d+)?)/', $old ?? '', $m)) $originalPrice = str_replace(',', '.', $m[1]);
        $brand = $product['brand']['name'] ?? null;
        // Some source versions serialize the brand relation as JSON inside its name.
        $brandData = is_string($brand) ? json_decode($brand, true) : null;
        if (is_array($brandData)) $brand = $brandData['brandname'] ?? null;
        return [
            'source' => 'korean-glam.tn', 'source_product_id' => $id,
            'source_url' => "https://korean-glam.tn/produit/{$id}",
            'name' => $name,
            'description' => $text('//div[contains(@class,"col-md-8")]/p[contains(@class,"text-muted")]') ?? ($product['description'] ?? null),
            'how_to_use' => $text('//*[@id="howtouse"]'),
            'price' => $price, 'original_price' => $originalPrice,
            'currency' => $offer['priceCurrency'] ?? 'TND', 'brand' => $brand,
            'stock' => $stock, 'availability' => $offer['availability'] ?? ($stock !== null ? ($stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock') : null),
            'images' => json_encode(array_values(array_unique($images)), JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            'structured_data' => $product ? json_encode($product, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) : null,
        ];
    }

    private function imageUrl(string $url): string
    {
        if (str_starts_with($url, '//')) return 'https:'.$url;
        return preg_match('~^https?://~', $url) ? $url : 'https://korean-glam.tn/'.ltrim($url, '/');
    }
}
