<?php

namespace App\Models;

use App\Traits\Models\HasSlug;
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
    use HasSlug;

    /**
     * @var string[]
     */
    protected $fillable = [
        'slug',
        'title',
        'thumbnail',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

}
