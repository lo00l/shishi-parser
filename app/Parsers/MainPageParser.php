<?php

namespace App\Parsers;

use App\AssetManager;
use App\Category;
use App\IAssetManager;

class MainPageParser extends BaseParser
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
