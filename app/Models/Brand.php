<?php

namespace App\Models;

use App\Traits\Models\HasSlug;
use App\Traits\Models\HasThumbnail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string slug
 * @property string title      blog title
 * @property string thumbnail  thumb image
 * @method static Builder|self  query()
 * @method Builder|self homepage(Builder $query)
 */
class Brand extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;

    protected function thumbnailDir(): string
    {
        return 'brands';
    }

    /**
     * @var string[]
     */
    protected $fillable = [
        'slug',
        'title',
        'thumbnail',
        'on_home_page',
        'sorting',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeHomepage(Builder $query): Builder|self
    {
        return $query->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }
}
