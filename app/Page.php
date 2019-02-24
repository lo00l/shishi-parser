<?php

namespace App;

class Page extends Model
{
    /**
     * @var string
     */
    protected $table = 'af_page';

    /**
     * @var array
     */
    protected $fillable = [
        'original_id',
        'preview_img',
        'background_img',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
