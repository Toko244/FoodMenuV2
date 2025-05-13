<?php

namespace App\Providers;

use App\Jobs\Auth\DeleteExpiredToken;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\Tag;
use App\Policies\CategoryPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\ProductPolicy;
use App\Policies\TagPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DeleteExpiredToken::class, function ($app) {
            return new DeleteExpiredToken;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Tag::class, TagPolicy::class);
    }
}
