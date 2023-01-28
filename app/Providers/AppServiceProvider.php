<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::preventLazyLoading(!app()->isProduction());
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());
        DB::whenQueryingForLongerThan(500, function (Connection $connection) {
            \Illuminate\Support\Facades\Log::channel('telegram')
                ->debug('whenQueryingForLongerThan: '.$connection->query()->toSql());
        });
        /** @var Kernel $kernel */
        $kernel = app(Kernel::class);
        $kernel->whenRequestLifecycleIsLongerThan(
            CarbonInterval::seconds(4),
            function () {
                \Illuminate\Support\Facades\Log::channel('telegram')
                    ->debug('whenRequestLifecycleIsLongerThan: '.request()->url());
            }
        );
    }
}
