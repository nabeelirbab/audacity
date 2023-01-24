<?php

namespace App\Models;

use App\Repositories\PerformanceObjectivesRepository;

class PerformanceWithObjectives extends Performance
{

    protected $appends = [
        'objectives'
    ];

    public function getObjectivesAttribute() : PerformanceObjectivesRepository {
        return $this->getObjectives();
    }

}