<?php

namespace Service\Github;

class Lib
{
    const TYPE_USERNAME = 0001;
    const TYPE_ID = 0002;

    public static function parseData(array $data): array {
        $res['identifier'] = $data['identifier'];
        $res['size'] = $data['s'] ?? $data['size'] ?? '100';
        return $res;
    }

    public static function buildQuery(array $data): string {
        return http_build_query([
            's' => $data['size']
        ]);
    }

    public static function buildUrl(array $data, $type = self::TYPE_USERNAME): string {
        switch ($type) {
            case self::TYPE_USERNAME:
                return "https://avatars.githubusercontent.com/{$data['identifier']}?" . self::buildQuery($data);
            case self::TYPE_ID:
                return "https://avatars.githubusercontent.com/u/{$data['identifier']}?" . self::buildQuery($data);
        }
    }
}