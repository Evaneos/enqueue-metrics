<?php

namespace Evaneos\Enqueue\Metric;

use Beberlei\Metrics\Collector\DogStatsD;
use Beberlei\Metrics\Collector\StatsD;
use Beberlei\Metrics\Factory;
use Beberlei\Metrics\MetricsException;

class MetricServiceFactory
{
    /**
     * @param string $type
     * @param array  $options
     *
     * @return MetricService
     * @throws MetricsException
     */
    public static function create($type, $options = [])
    {
        $collector = Factory::create($type, $options);

        switch ($type) {
            case 'dogstatsd':
                /** @var DogStatsD $collector */
                return new DogStatsDMetricService($collector);
            case 'statsd':
                /** @var StatsD $collector */
                return new StatsDMetricService($collector);
        }
    }
}
