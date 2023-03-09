<?php

namespace App\Models;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Support\Casts\PriceCast;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;

/**
 * @property int id
 * @property string title Product Title
 * @property string price Product price
 * @property string slug
 * @property integer brand_id reference to Product Brand
 * @method static Builder|self query()
 * @method Builder|self homepage(Builder $query)
 * @method Builder|self filtered(Builder $query)
 * @method Builder|self sorted(Builder $query)
 */
class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;

    protected $fillable = [
        'title',
        'slug',
        'brand_id',
        'price',
        'thumbnail',
        'on_home_page',
        'sorting',
    ];

    protected $casts = [
        'price' => PriceCast::class,
    ];

    protected function thumbnailDir(): string
    {
        return 'products';
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopeHomepage(Builder $query): Builder|self
    {
        return $query->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }

    public function scopeFiltered(Builder $query): Builder|self
    {
        return $query->when(request('filters.brands'), function (Builder $q) {
            $q->whereIn('brand_id', request('filters.brands'));
        })->when(request('filters.price'), function (Builder $q) {
            $q->whereBetween('price', [
                request('filters.price.from', 0) * 100,
                request('filters.price.to', 100000) * 100,
            ]);
        });
    }

    public function scopeSorted(Builder $query): Builder|self
    {
        return $query->when(request('sort'), function (Builder $q) {
            $column = request()->str('sort');
            if ($column->contains(['price', 'title'])) {
                $direction = $column->contains('-') ? 'DESC' : 'ASC';
                $q->orderBy((string) $column->remove('-'), $direction);
            }
        });
    }

    public static function createTestProducts($count = 1): void
    {
        $faker = Faker::create();
        /** @var Collection $brandsIds */
        $brandsIds = Brand::limit(100)->pluck('id');

        for ($i = 0; $i < $count; $i++) {
            $product           = new Product();
            $product->title    = ucfirst($faker->word(2, true));
            $product->brand_id = $brandsIds->count() > 0 ? $brandsIds->random() : null;
            $product->price    = $faker->numberBetween(1000, 100000);
            $product->slug     = 'super-unique-url-key';
            dump($product);
            $product->save();
        }
    }
}
