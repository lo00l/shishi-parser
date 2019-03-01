<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Log;

class AssetManager implements IAssetManager
{
    protected $imgPath;

    protected $imgRelativePath;

    /**
     * @var Client
     */
    protected static $client;

    public function __construct($domain, $imgPath, $imgRelativePath)
    {
        $this->imgPath = $imgPath;
        $this->imgRelativePath = $imgRelativePath;
        self::$client = new Client([
            'base_uri' => "$domain",
            'verify' => false,
        ]);
    }

    public function saveImg($url)
    {
        $info = pathinfo($url);
        if (!isset($info['extension'])) {
            Log::error('Couldn\'t get image extension: ' . json_encode($info));
            return false;
        }

        $fileName = uniqid('img') . '.' . $info['extension'];
        $filePath = $this->imgPath . DIRECTORY_SEPARATOR . $fileName;
        try {
            self::$client->get($url, [
                'sink' => $filePath,
            ]);
        } catch (GuzzleException $e) {
            Log::error("Couldn't download image from $url to $filePath. Error: " . $e->getMessage());
            return false;
        }

        return $this->imgRelativePath . DIRECTORY_SEPARATOR . $fileName;
    }
}
