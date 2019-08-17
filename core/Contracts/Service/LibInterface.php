<?php


namespace Core\Contracts\Service;


interface LibInterface
{
    public static function parseData(array $data): array;

    public static function buildQuery(array $data): string;

    public static function buildUrl(array $data): string;
}