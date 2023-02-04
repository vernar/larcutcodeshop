<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait HasSlug
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->slug   = $model->slug ?? str($model->{self::slugFrom()})->slug();
        });
    }

    public static function slugFrom(): string
    {
        return 'title';
    }
}