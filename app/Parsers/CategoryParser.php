<?php

namespace App\Parsers;

use App\AssetManager;
use App\Page;

class CategoryParser extends BaseParser
{
    public function getItems()
    {
        $items = [];
        $elements = $this->xpath->query('//div[contains(@class, \'list-container\')]/a');
        /**
         * @var \DOMElement $element
         */
        foreach ($elements as $element) {
            $url = $element->getAttribute('href');

            $img = $this->xpath->query('./img', $element)->item(0);
            $imgUrl = $this->assetManager->saveImg($img->getAttribute('src'));

            $page = new Page($url, [
                'original_id' => $this->extractId($url),
                'preview_img' => $imgUrl,
            ]);
            $items[] = $page;
        }
        return $items;
    }
}
