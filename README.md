# Enqueue metrics

This library provide an Extension for publishing metrics.

## How to use ?
```php
$config = ['host' => 'host', 'port' => 'port'];

// StatsD
$metricService = MetricServiceFactory::create('statsd', $config);
// DogStatsD
$metricService = MetricServiceFactory::create('dogstatsd', $config);

$tags = ['service' => 'myService']; // This tags will be sent with each metric
$extension = new ConsumptionMetricExtension($metricService, $tags, $optionalPrefix = 'consumer');
```

## Metrics

- `{PREFIX|default: consumer}.started` (increment)
- `{PREFIX|default: consumer}.stopped` (increment)
- `{PREFIX|default: consumer}.message_received` (increment)
- `{PREFIX|default: consumer}.message_consumed` (increment)
- `{PREFIX|default: consumer}.message_processing_time` (timing)
- `{PREFIX|default: consumer}.error` (increment)
