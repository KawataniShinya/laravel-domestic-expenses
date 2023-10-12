<?php

namespace App\Providers;

use App\Http\Repositories\MemberRepositoryImpl;
use App\Http\Services\MemberRepository;
use App\Http\Services\MemberService;
use App\Http\Services\MemberServiceImpl;
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
        $this->app->bind(MemberService::class, function ($app) {
            return new MemberServiceImpl(new MemberRepositoryImpl());
        });
        $this->app->bind(PaymentService::class, function ($app) {return new PaymentServiceImpl();});

        $this->app->bind(MemberRepository::class, function ($app) {return new MemberRepositoryImpl();});
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
