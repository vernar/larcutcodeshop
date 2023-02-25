<?php

namespace App\Models;

use App\Traits\Models\HasSlug;
use App\Traits\Models\HasThumbnail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string title
 * @property string slug
 * @method static Builder|self query()
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

    public function scopeHomepage(Builder $query): Builder|self
    {
        return $query->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }
}
