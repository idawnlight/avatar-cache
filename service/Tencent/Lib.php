<?php

namespace Service\Tencent;

use Core\Contracts\Service\LibInterface;

class Lib implements LibInterface
{
    public static function parseData(array $data): array {
        $res['identifier'] = $data['identifier'];
        $res['size'] = $data['s'] ?? '100';
        return $res;
    }

    public static function buildQuery(array $data): string {
        $para = [
            'b' => 'qq',
            'nk' => $data['identifier'],
            's' => $data['size']
        ];

        return http_build_query($para);
    }

    public static function buildUrl(array $data): string {
        return "https://q1.qlogo.cn/g?" . self::buildQuery($data);
    }
}