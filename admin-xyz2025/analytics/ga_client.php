<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config_secrets.php';

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\Dimension;

class GAClient {
    private BetaAnalyticsDataClient $client;
    private string $property;

    public function __construct() {
        $this->client = new BetaAnalyticsDataClient([
            'credentials' => __DIR__ . '/keys/nuevopack-545bc92e6f19.json'
        ]);
        $this->property = 'properties/' . GA4_PROPERTY_ID;
    }

    public function summaryLast7Days(): array {
        $resp = $this->client->runReport([
            'property' => $this->property,
            'dateRanges' => [ new DateRange(['start_date'=>'7daysAgo','end_date'=>'today']) ],
            'metrics' => [
                new Metric(['name'=>'activeUsers']),
                new Metric(['name'=>'sessions']),
                new Metric(['name'=>'screenPageViews']),
                new Metric(['name'=>'newUsers']),
            ],
        ]);
        $row = $resp->getRows()[0] ?? null;
        return [
            'activeUsers' => $row ? (int)$row->getMetricValues()[0]->getValue() : 0,
            'sessions'    => $row ? (int)$row->getMetricValues()[1]->getValue() : 0,
            'pageViews'   => $row ? (int)$row->getMetricValues()[2]->getValue() : 0,
            'newUsers'    => $row ? (int)$row->getMetricValues()[3]->getValue() : 0,
        ];
    }

    public function eventCountLast7Days(string $eventName): int {
        $resp = $this->client->runReport([
            'property' => $this->property,
            'dateRanges' => [ new DateRange(['start_date'=>'7daysAgo','end_date'=>'today']) ],
            'metrics' => [ new Metric(['name'=>'eventCount']) ],
            'dimensions' => [ new Dimension(['name'=>'eventName']) ],
        ]);
        $total = 0;
        foreach ($resp->getRows() as $row) {
            $name = $row->getDimensionValues()[0]->getValue();
            if ($name === $eventName) {
                $total += (int)$row->getMetricValues()[0]->getValue();
            }
        }
        return $total;
    }

    public function realtimeActiveUsers(): int {
        $resp = $this->client->runRealtimeReport([
            'property' => $this->property,
            'metrics'  => [ new Metric(['name'=>'activeUsers']) ],
        ]);
        $row = $resp->getRows()[0] ?? null;
        return $row ? (int)$row->getMetricValues()[0]->getValue() : 0;
    }
}
