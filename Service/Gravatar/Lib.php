<?php

namespace Service\Gravatar;

use Core\Contracts\Service\LibInterface;

class Lib implements LibInterface
{
    public static function parseData(array $data): array {
        $res['identifier'] = $data['identifier'];
        $res['size'] = $data['s'] ?? $data['size'] ?? '80';
        $res['default'] = $data['d'] ?? $data['default'] ?? null;
        $res['force_default'] = $data['f'] ?? $data['forcedefault'] ?? null;
        $res['rating'] = $data['r'] ?? $data['rating'] ?? 'g';
        $res['rating'] = strtolower($res['rating']);
        return $res;
    }

    public static function buildUrl(array $data): string {
        return "https://www.gravatar.com/avatar/{$data['identifier']}?" . self::buildQuery($data);
    }

    public static function buildQuery(array $data): string {
        $para = [
            's' => $data['size'],
            'r' => $data['rating']
        ];

        ($data['default'] !== null) ? $para['d'] = $data['default'] : null;
        ($data['force_default'] !== null) ? $para['f'] = $data['force_default'] : null;

        return http_build_query($para);
    }
}