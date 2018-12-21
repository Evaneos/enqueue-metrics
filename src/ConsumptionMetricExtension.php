<?php

namespace Evaneos\Enqueue\Metric;

use Enqueue\Consumption\Context\End;
use Enqueue\Consumption\Context\MessageReceived;
use Enqueue\Consumption\Context\MessageResult;
use Enqueue\Consumption\Context\ProcessorException;
use Enqueue\Consumption\Context\Start;
use Enqueue\Consumption\EndExtensionInterface;
use Enqueue\Consumption\MessageReceivedExtensionInterface;
use Enqueue\Consumption\MessageResultExtensionInterface;
use Enqueue\Consumption\ProcessorExceptionExtensionInterface;
use Enqueue\Consumption\StartExtensionInterface;

class ConsumptionMetricExtension implements StartExtensionInterface, MessageReceivedExtensionInterface, MessageResultExtensionInterface, ProcessorExceptionExtensionInterface, EndExtensionInterface
{
    /** @var MetricService */
    private $metricService;

    /** @var array */
    private $tags;

    /** @var string */
    private $prefix;

    /** @var Clock */
    private $clock;

    /**
     * ConsumptionMetricExtension constructor.
     *
     * @param MetricService $metricService
     * @param array         $tags
     * @param string        $prefix
     */
    public function __construct(MetricService $metricService, array $tags = [], $prefix = 'consumer')
    {
        $this->metricService = $metricService;
        $this->prefix = $prefix;
        $this->tags = $tags;
        $this->clock = new SystemClock();
    }

    /**
     * @param Clock $clock
     */
    public function setClock(Clock $clock)
    {
        $this->clock = $clock;
    }

    public function onStart(Start $context): void
    {
        $this->metricService->increment($this->prefix.'.started', $this->tags);
    }

    public function onMessageReceived(MessageReceived $context): void
    {
        $this->metricService->increment($this->prefix.'.message_received', $this->tags);
    }

    public function onResult(MessageResult $context): void
    {
        $this->metricService->increment($this->prefix.'.message_received', $this->tags);

        $processingTime = $this->clock->timestampInMs() - ($context->getReceivedAt() / 1000);

        $this->metricService->timing($this->prefix.'.message_processing_time', $processingTime, $this->tags);
    }

    public function onProcessorException(ProcessorException $context): void
    {
        $this->metricService->increment($this->prefix.'.error', $this->tags);
    }

    public function onEnd(End $context): void
    {
        $this->metricService->increment($this->prefix.'.stopped', $this->tags);
    }
}
