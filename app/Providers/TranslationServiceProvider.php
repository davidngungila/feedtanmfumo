<?php

namespace App\Providers;

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Lang;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!Schema::hasTable('translations')) {
            return;
        }

        $translations = Cache::rememberForever('translations', function () {
            return Translation::all();
        });

        foreach ($translations as $translation) {
            Lang::addLines([
                $translation->key => $translation->en
            ], 'en', 'portal');

            Lang::addLines([
                $translation->key => $translation->sw
            ], 'sw', 'portal');
        }
    }
}
