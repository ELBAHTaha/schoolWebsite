<?php

namespace App\Providers;

use App\Models\Pdf;
use App\Policies\PdfPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Pdf::class => PdfPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
