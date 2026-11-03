<?php

namespace App\Observers;

use App\Models\Service;
use App\Http\Controllers\Public\ServiceController;

/**
 * Keeps the cached public services index aligned with catalogue mutations.
 * Registered in AppServiceProvider; mass updates bypass observers, so the
 * controller cache TTL remains the bounded fallback for those paths.
 */
class ServiceCatalogueObserver
{
    protected function invalidate(): void
    {
        ServiceController::forgetIndexCache();
    }

    public function saved(Service $service): void
    {
        $this->invalidate();
    }

    public function deleted(Service $service): void
    {
        $this->invalidate();
    }
}
