<?php

namespace App\Parsers;

use App\AssetManager;
use App\IAssetManager;
use App\Product;
use App\ProductAttribute;

class ProductParser extends BaseParser
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * ProductParser constructor.
     * @param Product $product
     * @param IAssetManager $assetManager
     */
    public function __construct($product, IAssetManager $assetManager)
    {
        $this->product = $product;
        parent::__construct($product->getUrl(), $assetManager);
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return [];
    }

    /**
     *
     */
    public function setProductDetailData()
    {
        if (!$this->loadNextPage()) {
            return;
        }

        $img = $this->xpath->query('//div[contains(@class, \'image\')]/img')->item(0);
        if (!is_null($img)) {
            $imgUrl = $this->assetManager->saveImg($img->getAttribute('src'));
        } else {
            $imgUrl = '';
        }
        $this->product->setAttribute('img', $imgUrl);

        $parameters = $this->xpath->query('//div[contains(@class, \'parameter\')]');
        $values = $this->xpath->query('//div[contains(@class, \'value\')]');
        for ($i = 0; $i < $parameters->length; $i++) {
            $parameter = $parameters->item($i)->textContent;
            $value = $values->item($i)->textContent;
            $this->product->addAttribute(new ProductAttribute([
                'product_id' => $this->product->getAttribute('id'),
                'title' => $parameter,
                'value' => $value,
            ]));
        }

        $helpUl = $this->xpath->query('//ul[contains(@class, \'help\')]')->item(0);
        $this->product->setAttribute('help_html', $this->document->saveXML($helpUl));

        $vendorCodeH3 = $this->xpath->query('//div[contains(@class, \'product__details-box\')]/h3')->item(0);
        $this->product->setAttribute('available', $vendorCodeH3->getAttribute('class') !== 'unavailable');
    }
}
