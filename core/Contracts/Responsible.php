<?php


namespace Core\Contracts;

use GuzzleHttp\Psr7\Response;

interface Responsible
{
    public function createResponse() :Response;
}