<?php

namespace InetStudio\Rating\Observers;

use InetStudio\Rating\Contracts\Models\Traits\RateableContract;

class ModelObserver
{
    public function deleted(RateableContract $rateable)
    {
        if (! $this->removeRatesOnDelete($rateable)) {
            return;
        }

        $rateable->removeRates();
    }

    protected function removeRatesOnDelete(RateableContract $rateable)
    {
        return isset($rateable->removeRatesOnDelete) ? $rateable->removeRatesOnDelete : true;
    }
}
