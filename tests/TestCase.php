<?php

namespace Tests;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    public function loadUrl(string $url, array $data)
    {
        $url .= "?" . http_build_query($data);

        $response = file_get_contents($url);

        return json_decode($response, true);
    }
}