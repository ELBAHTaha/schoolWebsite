<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('app:health', function () {
    $this->info('JEFAL Prive backend is running.');
});
