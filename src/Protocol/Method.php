<?php

namespace SimpleMehanizm\Http\Protocol;

enum Method: string
{
    case GET = 'GET';
    case HEAD = 'HEAD';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';
    case OPTIONS = 'OPTIONS';
    case TRACE = 'TRACE';
    case CONNECT = 'CONNECT';
}