<?php


namespace Core\Components;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Helper
{
    public static function request(string $url) {
        $client = new Client();
        try {
            return $client->request('GET', $url, [
                'timeout' => 3,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3872.0 Safari/537.36 avatar-cache/' . VERSION,
                    'Accept' => 'image/*,*/*'
                ]
            ]);
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    public static function createResponseFromCache(): ResponseInterface {

    }

    public static function createRedirectResponse(string $url): ResponseInterface {
        return new Response(302, [
            'Cache-Control' => 'no-cache',
            'Pragma' => 'no-cache',
            'Location' => $url,
            'X-Cache-Status: MISS; Redirected'
        ]);
    }
}