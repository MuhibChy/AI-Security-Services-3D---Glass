<?php

namespace App\Observers;

use App\Models\ServiceCategory;
use App\Http\Controllers\Public\ServiceController;

/**
 * Category changes also alter the grouped services index, so invalidate too.
 */
class ServiceCategoryObserver
{
    protected function invalidate(): void
    {
        ServiceController::forgetIndexCache();
    }

    public function saved(ServiceCategory $category): void
    {
        $this->invalidate();
    }

    public function deleted(ServiceCategory $category): void
    {
        $this->invalidate();
    }
}
