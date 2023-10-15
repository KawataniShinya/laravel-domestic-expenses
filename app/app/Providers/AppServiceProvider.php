<?php

namespace App\Providers;

use App\Http\Repositories\CommonRepositoryImpl;
use App\Http\Repositories\MemberCategoryRepositoryImpl;
use App\Http\Repositories\MemberRepositoryImpl;
use App\Http\Repositories\PaymentRepositoryImpl;
use App\Http\Services\CommonRepository;
use App\Http\Services\MemberCategoryRepository;
use App\Http\Services\MemberRepository;
use App\Http\Services\PaymentRepository;
use App\Http\Services\PaymentService;
use App\Http\Services\PaymentServiceImpl;
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
        $this->app->bind(PaymentService::class, function ($app) {
            return new PaymentServiceImpl(
                new CommonRepositoryImpl(),
                new MemberRepositoryImpl(),
                new MemberCategoryRepositoryImpl(),
                new PaymentRepositoryImpl()
            );
        });

        $this->app->bind(CommonRepository::class, function ($app) {return new CommonRepositoryImpl();});
        $this->app->bind(MemberRepository::class, function ($app) {return new MemberRepositoryImpl();});
        $this->app->bind(MemberCategoryRepository::class, function ($app) {return new MemberCategoryRepositoryImpl();});
        $this->app->bind(PaymentRepository::class, function ($app) {return new PaymentRepositoryImpl();});
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
