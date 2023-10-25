<?php

namespace SimpleMehanizm\Http\Protocol;

enum MagicStrings: string
{
    // <input type="hidden" name="_method" value="method value you need here" />
    case FORM_METHOD_OVERRIDE = '_method';
    case METHOD_OVERRIDE = 'X-HTTP-METHOD-OVERRIDE';
    case FORWARD_SLASH = '/';
    case QUERYSTRING_SEPARATOR = '?';
    case HTTP_FRAGMENT = '#'; // this never actually reaches the server when sent from browser
    case XHR = 'XmlHttpRequest';
}