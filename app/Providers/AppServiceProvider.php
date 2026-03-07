<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\Rfq;
use App\Policies\InvoicePolicy;
use App\Policies\PurchaseOrderPolicy;
use App\Policies\PurchaseRequisitionPolicy;
use App\Policies\RfqPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
        // Force HTTPS in production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Model-level authorization policies
        Gate::policy(PurchaseRequisition::class, PurchaseRequisitionPolicy::class);
        Gate::policy(PurchaseOrder::class, PurchaseOrderPolicy::class);
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(Rfq::class, RfqPolicy::class);
    }
}
