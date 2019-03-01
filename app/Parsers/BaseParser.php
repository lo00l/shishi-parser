<?php

namespace App\Parsers;

use App\IAssetManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Log;

abstract class BaseParser implements \Iterator
{
    const BASE_URL = 'https://catalogue.shishi.ee';

    /**
     * @var
     */
    protected $url;

    /**
     * @var
     */
    protected $items;

    /**
     * @var int
     */
    protected $currentKey;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var IAssetManager
     */
    protected $assetManager;

    /**
     * @var
     */
    protected $currentPage;

    /**
     * @var
     */
    protected $hasNextPage;

    /**
     * @var \DOMDocument
     */
    protected $document;

    /**
     * @var \DOMXPath
     */
    protected $xpath;

    /**
     * @return mixed
     */
    abstract public function getItems();

    /**
     * BaseParser constructor.
     * @param $url
     * @param IAssetManager $assetManager
     */
    public function __construct($url, IAssetManager $assetManager)
    {
        $this->url = $url;
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'verify' => false,
            'timeout' => 10,
        ]);
        $this->assetManager = $assetManager;
        $this->currentKey = 0;
    }

    /**
     * @return bool|mixed
     */
    public function current()
    {
        if (is_null($this->items)) {
            return false;
        }

        return current($this->items);
    }

    /**
     * @throws GuzzleException
     */
    public function next()
    {
        $item = next($this->items);
        if ($item !== false) {
            return;
        }

        if (!$this->nextPageExists()) {
            $this->items = null;
            return;
        }

        $this->currentKey += count($this->items);

        if (!$this->loadNextPage()) {
            $this->items = null;
            return;
        }
        $this->items = $this->getItems();

        reset($this->items);
    }

    /**
     * @return int|mixed|string|null
     */
    public function key()
    {
        if (is_null($this->items)) {
            return null;
        }

        return $this->currentKey + key($this->items);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return !is_null($this->items);
    }

    /**
     * @throws GuzzleException
     */
    public function rewind()
    {
        if ($this->currentPage === 1 && !is_null($this->items)) {
            reset($this->items);
        } else {
            $this->items = null;
            $this->currentPage = null;
            $this->loadNextPage();
        }
    }

    /**
     * @return bool
     * @throws GuzzleException
     */
    protected function loadNextPage()
    {
        if (!$this->nextPageExists()) {
            return false;
        }
        $this->incrementPage();
        try {
            $response = $this->client->get($this->url, [
                'query' => [
                    'page' => $this->currentPage,
                ]
            ]);
            $this->document = new \DOMDocument();
            $this->document->loadHTML($response->getBody(), LIBXML_NOWARNING | LIBXML_NOERROR);
            $this->xpath = new \DOMXPath($this->document);
            $this->items = $this->getItems();
            return true;
        } catch (GuzzleException $e) {
            Log::error("Couldn't load url {$this->url} page {$this->currentPage}. Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     *
     */
    private function incrementPage()
    {
        if (is_null($this->currentPage)) {
            $this->currentPage = 1;
        } else {
            $this->currentPage++;
        }
    }

    /**
     * @return bool
     */
    protected function nextPageExists()
    {
        if (is_null($this->currentPage)) {
            return true;
        }

        return $this->xpath->query('//div[contains(@class, \'product-navigation next\')]/a')->length > 0;
    }

    /**
     * @return bool
     */
    protected function isLoaded()
    {
        return !is_null($this->document);
    }

    /**
     * @param $url
     * @return bool
     */
    protected function extractId($url)
    {
        preg_match('/\d+$/', $url, $match);
        if (count($match) === 0) {
            Log::error("Couldn't extract id from url $url");
            return false;
        }
        return $match[0];
    }

    /**
     * @param $name
     * @return mixed
     */
    protected function normalizeImg($name)
    {
        return explode('?', $name)[0];
    }
}
