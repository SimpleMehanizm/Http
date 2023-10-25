<?php

namespace SimpleMehanizm\Http\Protocol;

enum Headers: string
{
    case ACCEPT = 'Accept';
    case ACCEPT_CHARSET = 'Accept-Charset';
    case ACCEPT_ENCODING = 'Accept-Encoding';
    case ACCEPT_LANGUAGE = 'Accept-Language';
    case AUTHORIZATION = 'Authorization';
    case CACHE_CONTROL = 'Cache-Control';
    case CONNECTION = 'Connection';
    case CONTENT_LENGTH = 'Content-Length';
    case CONTENT_TYPE = 'Content-Type';
    case COOKIE = 'Cookie';
    case DNT = 'DNT';
    case HOST = 'Host';
    case ORIGIN = 'Origin';
    case REFERER = 'Referer';
    case USER_AGENT = 'User-Agent';
    case X_REQ_WITH = 'X-Requested-With';
}