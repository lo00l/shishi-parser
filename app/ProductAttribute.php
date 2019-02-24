<?php

namespace App;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class ProductAttribute extends EloquentModel
{
    /**
     * @var string
     */
    protected $table = 'af_product_attribute';

    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'title',
        'value',
    ];

    /**
     * ProductAttribute constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (isset($attributes['product_id']) && isset($attributes['title']) && isset($attributes['value'])) {
            $existingAttribute = static::where('product_id', $attributes['product_id'])
                ->where('title', $attributes['title'])
                ->first();
            if (!is_null($existingAttribute)) {
                $this->setAttribute('id', $existingAttribute->attributes['id']);
                $this->exists = true;
            }
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
