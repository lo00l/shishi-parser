<?php

namespace App;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    /**
     * @var string
     */
    protected $url;

    /**
     * Model constructor.
     * @param string $url
     * @param array $attributes
     */
    public function __construct($url = null, array $attributes = [])
    {
        parent::__construct($attributes);
        $this->url = $url;
        if (isset($attributes['original_id'])) {
            $existingModel = static::where('original_id', $attributes['original_id'])->first();
            if (!is_null($existingModel)) {
                $this->setAttribute('id', $existingModel->attributes['id']);
                $this->exists = true;
            }
        }
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return static::class . ': ' . parent::__toString();
    }

    /**
     * @return mixed
     */
    public function getCreateTimeAttribute()
    {
        return $this->getAttribute('created_at')->format('d.m.Y H:i:s');
    }
}
