<?php

namespace InetStudio\Rating\Events;

use InetStudio\Rating\Contracts\Models\Traits\RateableContract;

class ModelWasUnRated
{
    public $model;
    public $raterId;

    public function __construct(RateableContract $rateable, $raterId)
    {
        $this->model = $rateable;
        $this->raterId = $raterId;
    }
}
