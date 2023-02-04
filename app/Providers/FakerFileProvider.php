<?php

namespace App\Providers;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FakerFileProvider extends Base
{
    public function customFile(string $sourceBasePath, string $targetStoragePath)
    {
        Storage::disk('public')->makeDirectory($targetStoragePath);
        $fileName = $this->generator->file(
            base_path('tests/Fixtures/images/products'),
            storage_path('/app/public/'.$targetStoragePath),
            false
        );

        return Str::finish('app/public/'.$targetStoragePath, '/').$fileName;
    }
}
