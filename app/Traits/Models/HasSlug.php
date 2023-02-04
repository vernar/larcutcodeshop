<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Model $model) {
            $slugName          = $model->slug ?? str($model->{self::slugFrom()})->slug();
            $itemsWithSameSlug = self::where('slug', 'like', "$slugName%")->get();
            if ($itemsWithSameSlug->count()) {
                $slugSuffix = 2;
                do {
                    $newSlugName = $slugName.'-'.$slugSuffix;
                    $slugSuffix++;
                } while ($itemsWithSameSlug->where('slug', $newSlugName)->count() > 0);
                $slugName = $newSlugName;
            }
            $model->slug = $slugName;
        });
    }

    public static function slugFrom(): string
    {
        return 'title';
    }
}