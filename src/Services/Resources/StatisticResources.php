<?php

namespace MrWolfGb\Traccar\Services\Resources;

use Illuminate\Support\Collection;
use MrWolfGb\Traccar\Exceptions\TraccarException;
use MrWolfGb\Traccar\Models\Statistic;

class StatisticResources extends BaseResource
{
    /**
     * @throws TraccarException
     */
    public function fetchServerStatistics(string $from, string $to): Collection
    {
        return $this->getModelCollection('statistics', Statistic::class, compact('from', 'to'));
    }
}
