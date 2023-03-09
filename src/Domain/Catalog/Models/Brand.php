<?php

namespace Domain\Catalog\Models;

use App\Models\Product;
use Database\Factories\BrandFactory;
use Domain\Catalog\QueryBuilders\BrandQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;

/**
 * @property string slug
 * @property string title      blog title
 * @property string thumbnail  thumb image
 * @method static BrandQueryBuilder|self  query()
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


    public function newEloquentBuilder($query): BrandQueryBuilder
    {
        return new BrandQueryBuilder($query);
    }

    protected static function newFactory(): BrandFactory
    {
        return BrandFactory::new();
    }
}
