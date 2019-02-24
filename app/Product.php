<?php

namespace App;

class Product extends Model
{
    /**
     * @var string
     */
    protected $table = 'af_product';

    /**
     * @var array
     */
    protected $fillable = [
        'original_id',
        'title',
        'placeholder_image_width',
        'placeholder_x1',
        'placeholder_x2',
        'placeholder_y1',
        'placeholder_y2',
        'title_image_width',
        'title_x1',
        'title_x2',
        'title_y1',
        'title_y2',
        'vendor_code',
        'help_html',
        'img',
    ];

    /**
     * @var ProductAttribute[]
     */
    protected $productAttributes;

    /**
     * Product constructor.
     * @param null $url
     * @param array $attributes
     */
    public function __construct($url = null, array $attributes = [])
    {
        parent::__construct($url, $attributes);
        $this->productAttributes = [];
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if (parent::save($options)) {
            foreach ($this->productAttributes as $productAttribute) {
                $productAttribute->save();
            }
            return true;
        }
        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * @param ProductAttribute $attribute
     */
    public function addAttribute(ProductAttribute $attribute)
    {
        $attribute->product()->associate($this);
        $this->productAttributes[] = $attribute;
    }
}
