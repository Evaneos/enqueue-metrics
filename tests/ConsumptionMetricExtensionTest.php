<?php

namespace Evaneos\Enqueue\Metric\Test;

use Enqueue\Consumption\Context\End;
use Enqueue\Consumption\Context\MessageReceived;
use Enqueue\Consumption\Context\MessageResult;
use Enqueue\Consumption\Context\ProcessorException;
use Enqueue\Consumption\Context\Start;
use Enqueue\Null\NullMessage;
use Evaneos\Enqueue\Metric\Clock;
use Evaneos\Enqueue\Metric\ConsumptionMetricExtension;
use Evaneos\Enqueue\Metric\MetricService;
use Interop\Queue\Consumer;
use Interop\Queue\Context;
use Interop\Queue\Processor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ConsumptionMetricExtensionTest extends TestCase
{
    private const PREFIX = 'test.consumer';

    private const TAGS = [];

    /** @var MetricService | \PHPUnit_Framework_MockObject_MockObject */
    private $metricService;

    /** @var ConsumptionMetricExtension */
    private $extension;

    public function setUp()
    {
        $this->metricService = $this->createMock(MetricService::class);

        $this->extension = new ConsumptionMetricExtension(
            $this->metricService,
            self::TAGS,
            self::PREFIX
        );
    }

    /**
     * @test
     */
    public function it_publish_on_start()
    {
        $this->metricService
            ->expects($this->once())
            ->method('increment')
            ->with(self::PREFIX.'.started', self::TAGS);

        $context = new Start($this->createContextMock(), $this->createLogger(), [], 1, 1);

        $this->extension->onStart($context);
    }

    /**
     * @test
     */
    public function it_publish_on_message_received()
    {
        $context = new MessageReceived(
            $this->createContextMock(),
            $this->createConsumerMock(),
            $this->aMessage(),
            $this->createProcessorMock(),
            1,
            $this->createLogger()
        );

        $this->metricService
            ->expects($this->once())
            ->method('increment')
            ->with(self::PREFIX.'.message_received', self::TAGS);

        $this->extension->onMessageReceived($context);
    }

    /**
     * @test
     */
    public function it_publish_on_result()
    {
        /** @var Clock | MockObject $clock */
        $clock = $this->createMock(Clock::class);
        $clock->expects($this->once())
            ->method('timestampInMs')
            ->willReturn(0.002);

        $this->extension->setClock($clock);

        $context = new MessageResult(
            $this->createContextMock(),
            $this->createConsumerMock(),
            $this->aMessage(),
            $this->createProcessorMock(),
            1,
            $this->createLogger()
        );

        $this->metricService
            ->expects($this->once())
            ->method('increment')
            ->with(self::PREFIX.'.message_received', self::TAGS);

        $this->metricService
            ->expects($this->once())
            ->method('timing')
            ->with(self::PREFIX.'.message_processing_time', 0.001, self::TAGS);

        $this->extension->onResult($context);
    }

    public function it_publish_on_processor_exception()
    {
        $exception = new \Exception();

        $context = new ProcessorException(
            $this->createContextMock(),
            $this->createConsumerMock(),
            $this->aMessage(),
            $exception,
            1,
            $this->createLogger()
        );

        $this->metricService
            ->expects($this->once())
            ->method('increment')
            ->with(self::PREFIX.'.error', self::TAGS);

        $this->extension->onProcessorException($context);
    }

    public function it_publish_on_end()
    {
        $context = new End(
            $this->createContextMock(),
            1,
            2,
            $this->createLogger()
        );

        $this->metricService
            ->expects($this->once())
            ->method('increment')
            ->with(self::PREFIX.'.stopped', self::TAGS);

        $this->extension->onEnd($context);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createContextMock(): Context
    {
        return $this->createMock(Context::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|LoggerInterface
     */
    private function createLogger()
    {
        return $this->createMock(LoggerInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Processor
     */
    private function createProcessorMock(): Processor
    {
        return $this->createMock(Processor::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createConsumerMock(): Consumer
    {
        return $this->createMock(Consumer::class);
    }

    private function aMessage()
    {
        $message = new NullMessage('aBody');
        $message->setProperty('aProp', 'aPropVal');
        $message->setHeader('aHeader', 'aHeaderVal');

        return $message;
    }
}
