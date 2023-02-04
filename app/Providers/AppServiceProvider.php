<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Generator::class, function () {
            $faker = Factory::create();
            $faker->addProvider(new FakerFileProvider($faker));

            return $faker;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::shouldBeStrict();

//        if (app()->isProduction()) {}
//        DB::whenQueryingForLongerThan(500, function (Connection $connection) {
//            Log::channel('telegram')
//                ->debug('whenTotalQueryingForLongerThan: '.$connection->query()->toSql());
//        });
        DB::listen(function ($query) {
            if ($query->time > 1000) {
                $queryString = vsprintf(
                    str_replace(['?'], ['\'%s\''], $query->sql),
                    $query->bindings
                );
                Log::channel('telegram')
                    ->debug('whenQueryingForLongerThan: '.$queryString);
            }
        });

        app(Kernel::class)->whenRequestLifecycleIsLongerThan(
            CarbonInterval::seconds(4),
            function () {
                Log::channel('telegram')
                    ->debug('whenRequestLifecycleIsLongerThan: '.request()->url());
            }
        );
    }
}
