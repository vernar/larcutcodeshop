<?php

namespace Domain\Catalog\Models;

use App\Models\Product;
use Database\Factories\CategoryFactory;
use Domain\Catalog\Collection\CategoryCollection;
use Domain\Catalog\QueryBuilders\CategoryQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;

/**
 * @property string title
 * @property string slug
 * @method static CategoryQueryBuilder|Category query()
 * @method void homepage(Builder $query)
 */
class Category extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;

    protected $fillable = [
        'title',
        'slug',
        'on_home_page',
        'sorting',
    ];

    protected function thumbnailDir(): string
    {
        return 'category';
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function newEloquentBuilder($query): CategoryQueryBuilder
    {
        return new CategoryQueryBuilder($query);
    }

    public function newCollection(array $models = []): CategoryCollection
    {
        return new CategoryCollection($models);
    }

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
}
