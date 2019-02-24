<?php

namespace Shishi\Parsers;

use Shishi\AssetManager;
use Shishi\Category;

class MainPageParser extends BaseParser
{
    public function getItems()
    {
        $items = [];
        $elements = $this->xpath->query('//div[contains(@class, \'list-container\')]/*/li');
        /**
         * @var \DOMElement $element
         */
        foreach ($elements as $element) {
            $a = $this->xpath->query('./a', $element)->item(0);
            $url = $a->getAttribute('href');

            $img = $this->xpath->query('./*/img', $element)->item(0);
            $imgUrl = AssetManager::saveImg($this->normalizeImg($img->getAttribute('src')));

            $div = $this->xpath->query('.//div[contains(@class, \'category-name\')]', $element)->item(0);
            $title = $div->textContent;

            $category = new Category($url, [
                'original_id' => $this->extractId($url),
                'title' => $title,
                'preview_img' => $imgUrl,
            ]);
            $items[] = $category;
        }
        return $items;
    }
}
