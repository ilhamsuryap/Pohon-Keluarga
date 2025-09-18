<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Barryvdh\DomPDF\ServiceProvider as DomPDFServiceProvider;

class PDFServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(DomPDFServiceProvider::class);
    }

    public function boot()
    {
        //
    }
}
