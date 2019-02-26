<?php

namespace App\Parsers;

use App\AssetManager;
use App\Page;

class CategoryParser extends BaseParser
{
    public function getItems()
    {
        $items = [];
        $elements = $this->xpath->query('//div[contains(@class, \'spacer\')]');
        /**
         * @var \DOMElement $element
         */
        foreach ($elements as $element) {
            $a = $this->xpath->query('./a', $element)->item(0);
            $url = $a->getAttribute('href');

            $img = $this->xpath->query('./a/img', $element)->item(0);
            $imgUrl = $this->assetManager->saveImg($this->normalizeImg($img->getAttribute('src')));

            $page = new Page($url, [
                'original_id' => $this->extractId($url),
                'preview_img' => $imgUrl,
            ]);
            $items[] = $page;
        }
        return $items;
    }
}
