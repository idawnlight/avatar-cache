<?php


namespace Service\Gravatar;


class Lib
{
    public static function parseData(array $data) :array {
        $res['identifier'] = $data['identifier'];
        $res['size'] = $data['s'] ?? $data['size'] ?? null;
        $res['default'] = $data['d'] ?? $data['default'] ?? '404';
        $res['force_default'] = $data['f'] ?? $data['forcedefault'] ?? null;
        $res['rating'] = $data['r'] ?? $data['rating'] ?? null;
        return $res;
    }
}