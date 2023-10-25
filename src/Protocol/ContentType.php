<?php

namespace SimpleMehanizm\Http\Protocol;

enum ContentType: string
{
    case TEXT_HTML = 'text/html';
    case TEXT_PLAIN = 'text/plain';
    case TEXT_CSS = 'text/css';
    case APP_JSON = 'application/json';
    case APP_XML = 'application/xml';
    case APP_JS = 'application/javascript';
    case APP_PDF = 'application/pdf';
    case APP_X_FORM = 'application/x-www-form-urlencoded';
    case APP_ZIP = 'application/zip';
    case IMG_JPG = 'image/jpg';
    case IMG_JPEG = 'image/jpeg';
    case IMG_PNG = 'image/png';
    case IMG_GIF = 'image/gif';
    case AUDIO_MPEG = 'audio/mpeg';
    case VIDEO_MP4 = 'video/mp4';
    case MULTIPART_FORM_DATA = 'multipart/form-data';
}