<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Debtors;
use App\Observers\DebtorObserver;
use App\Models\Uthang;
use App\Observers\UthangsObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
{
    Debtors::observe(DebtorObserver::class);
    Uthang::observe(UthangsObserver::class);
}


    


}
