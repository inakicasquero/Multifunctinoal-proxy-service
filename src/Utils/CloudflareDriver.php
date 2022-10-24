<?php

declare(strict_types=1);

namespace App\Utils;

use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\DNS;
use Cloudflare\API\Endpoints\Zones;

final class CloudflareDriver
{
    // @todo: parameters
    public static function modifyRecord(DNS $dns, $zoneID, $recordID, $name, $content, $proxied = false)
    {
        $details = ['type' => 'A', 'name' => $name, 'content' => $content, 'proxied' => $proxied];
        if ($dns->updateRecordDetails($zoneID, $recordID, $details)->success === true) {
            return 1;
        }
        return 0;
    }

    public static function addRecord(DNS $dns, $zoneID, $type, $name, $content, $ttl = 120, $proxied = false)
    {
        if ($dns->addRecord($zoneID, $type, $name, $content, $ttl, $proxied) === true) {
            return 1;
        }
        return 0;
    }

    public static function updateRecord($name, $content, $proxied = false): void
    {
        $key = new APIKey($_ENV['cloudflare_email'], $_ENV['cloudflare_key']);
        $adapter = new Guzzle($key);
        $zones = new Zones($adapter);

        $zoneID = $zones->getZoneID($_ENV['cloudflare_name']);

        $dns = new DNS($adapter);

        $r = $dns->listRecords($zoneID, '', $name);
        $recordCount = $r->result_info->count;
        $records = $r->result;

        if ($recordCount === 0) {
            self::addRecord($dns, $zoneID, 'A', $name, $content);
        } elseif ($recordCount >= 1) {
            foreach ($records as $record) {
                $recordID = $record->id;
                self::modifyRecord($dns, $zoneID, $recordID, $name, $content, $proxied);
            }
        }
    }
}
