<?php declare(strict_types=1);

namespace Oarkhipov\Whois\Laravel;

use Illuminate\Support\ServiceProvider;
use Oarkhipov\Whois\Facade;

class WhoisServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('whois', function ($app) {
            return Facade::create();
        });
    }
}