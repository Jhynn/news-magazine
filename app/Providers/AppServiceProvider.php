<?php

namespace App\Providers;

use App\Models\{
    Article,
    Topic
};
use App\Observers\{
    ArticleObserver,
    TopicObserver
};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Obserservers
        Topic::observe(TopicObserver::class);
        Article::observe(ArticleObserver::class);
    }
}
