<?php

namespace SimpleMehanizm\Http;

if(!function_exists('get_http_headers'))
{
    function get_http_headers(?array $server): array
    {
        $server = $server ?? $_SERVER;

        $headers = [];

        foreach($server as $key => $value)
        {
            if(is_string($key) && str_starts_with($key, 'HTTP_'))
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))))] = $value;
            }
        }

        return $headers;
    }
}