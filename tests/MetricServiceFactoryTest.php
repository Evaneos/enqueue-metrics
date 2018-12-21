<?php

namespace Evaneos\Enqueue\Metric\Test;

use Evaneos\Enqueue\Metric\MetricServiceFactory;
use Evaneos\Enqueue\Metric\DogStatsDMetricService;
use Evaneos\Enqueue\Metric\StatsDMetricService;
use PHPUnit\Framework\TestCase;

class MetricServiceFactoryTest extends TestCase
{
    /**
     * @test
     * @throws \Beberlei\Metrics\MetricsException
     */
    public function it_creates_dogstatsd_metric_service()
    {
        $service = MetricServiceFactory::create('dogstatsd');

        self::assertInstanceOf(DogStatsDMetricService::class, $service);
    }

    /**
     * @test
     * @throws \Beberlei\Metrics\MetricsException
     */
    public function it_creates_statsd_metric_service()
    {
        $service = MetricServiceFactory::create('statsd');

        self::assertInstanceOf(StatsDMetricService::class, $service);
    }
}
