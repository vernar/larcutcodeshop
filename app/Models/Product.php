<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string title Product Title
 * @property string price Product price
 * @property string slug
 * @property integer brand_id reference to Product Brand
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'brand_id',
        'price',
        'thumbnail',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Product $product) {
            $product->slug = $product->slug ?? str($product->title)->slug();
        });
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

}
