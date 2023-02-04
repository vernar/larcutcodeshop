<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string slug
 * @property string title      blog title
 * @property string thumbnail  thumb image
 */
class Brand extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'slug',
        'title',
        'thumbnail',
    ];


    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Brand $brand) {
            $brand->slug = $brand->slug ?? str($brand->title)->slug();
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

}
