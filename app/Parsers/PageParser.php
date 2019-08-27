<?php

namespace App\Parsers;

use App\Product;

class PageParser extends BaseParser
{
    public function getItems()
    {
        $items = [];
        $elements = $this->xpath->query('//a[contains(@data-block, \'product-link\')]');
        /**
         * @var \DOMElement $element
         */
        for ($i = 0; $i < $elements->length / 2; $i++) {
            $placeholderElement = $elements->item($i * 2);
            $url = $placeholderElement->getAttribute('href');
            $div = $this->xpath->query('./div', $placeholderElement)->item(0);
            $placeholderImageWidth = $div->getAttribute('data-imagewidth');
            $placeholderX1 = $div->getAttribute('data-x1');
            $placeholderX2 = $div->getAttribute('data-x2');
            $placeholderY1 = $div->getAttribute('data-y1');
            $placeholderY2 = $div->getAttribute('data-y2');

            $titleElement = $elements->item($i * 2 + 1);
            $div = $this->xpath->query('./div', $titleElement)->item(0);
            $titleImageWidth = $div->getAttribute('data-imagewidth');
            $titleX1 = $div->getAttribute('data-x1');
            $titleX2 = $div->getAttribute('data-x2');
            $titleY1 = $div->getAttribute('data-y1');
            $titleY2 = $div->getAttribute('data-y2');
            $lis = $this->xpath->query('./div/ul/li', $titleElement);
            $vendorCode = $lis->item(0)->textContent;
            $title = $lis->item(1)->textContent;

            $product = new Product($url, [
                'original_id' => $this->extractId($url),
                'title' => $title,
                'placeholder_image_width' => $placeholderImageWidth,
                'placeholder_x1' => $placeholderX1,
                'placeholder_x2' => $placeholderX2,
                'placeholder_y1' => $placeholderY1,
                'placeholder_y2' => $placeholderY2,
                'title_image_width' => $titleImageWidth,
                'title_x1' => $titleX1,
                'title_x2' => $titleX2,
                'title_y1' => $titleY1,
                'title_y2' => $titleY2,
                'vendor_code' => $vendorCode,
            ]);
            $items[] = $product;
        }

        return $items;
    }

    public function getBackgroundUrl()
    {
        if (!$this->isLoaded()) {
            $this->loadNextPage();
        }
        $img = $this->xpath->query('//img[contains(@class, \'map\')]')->item(0);
        return $img->getAttribute('src');
    }

    public function getBackgroundWidth()
    {
        if (!$this->isLoaded()) {
            $this->loadNextPage();
        }
        $img = $this->xpath->query('//img[contains(@class, \'map\')]')->item(0);
        return $img->getAttribute('data-width');
    }

    public function getBackgroundHeight()
    {
        if (!$this->isLoaded()) {
            $this->loadNextPage();
        }
        $img = $this->xpath->query('//img[contains(@class, \'map\')]')->item(0);
        return $img->getAttribute('data-height');
    }

    protected function nextPageExists()
    {
        if (is_null($this->currentPage)) {
            return true;
        }

        return false;
    }
}
