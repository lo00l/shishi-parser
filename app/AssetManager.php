<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class AssetManager
{
    protected static $imgPath;

    protected static $imgRelativePath;

    /**
     * @var Client
     */
    protected static $client;

    public static function init($domain, $imgPath, $imgRelativePath)
    {
        self::$imgPath = $imgPath;
        self::$imgRelativePath = $imgRelativePath;
        self::$client = new Client([
            'base_uri' => "$domain",
            'verify' => false,
        ]);
    }

    public static function saveImg($url)
    {
        $info = pathinfo($url);
        if (!isset($info['extension'])) {
//            Log::error('Couldn\'t get image extension: ' . json_encode($info));
            return false;
        }

        $fileName = uniqid('img') . '.' . $info['extension'];
        $filePath = self::$imgPath . DIRECTORY_SEPARATOR . $fileName;
        try {
            self::$client->get($url, [
                'sink' => $filePath,
            ]);
        } catch (GuzzleException $e) {
//            Log::error("Couldn't download image from $url to $filePath. Error: " . $e->getMessage());
            return false;
        }

        return self::$imgRelativePath . DIRECTORY_SEPARATOR . $fileName;
    }
}
