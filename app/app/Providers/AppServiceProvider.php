<?php

namespace App\Providers;

use App\Http\Repositories\MemberRepositoryImpl;
use App\Http\Repositories\PaymentRepositoryImpl;
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
                new MemberRepositoryImpl(),
                new PaymentRepositoryImpl()
            );
        });

        $this->app->bind(MemberRepository::class, function ($app) {return new MemberRepositoryImpl();});
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
