<?php

namespace App;

class Category extends Model
{
    /**
     * @var string
     */
    protected $table = 'af_category';

    /**
     * @var array
     */
    protected $fillable = [
        'original_id',
        'title',
        'preview_img',
        'russian_title',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
