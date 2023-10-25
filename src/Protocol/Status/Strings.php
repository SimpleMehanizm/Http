<?php

namespace SimpleMehanizm\Http\Protocol\Status;

final class Strings
{
    public const CODE_TO_STRING = [
        Code::HTTP_CONTINUE->value=> 'Continue',
        Code::HTTP_SWITCHING_PROTOCOLS->value => 'Switching Protocols',
        Code::HTTP_PROCESSING->value => 'Processing',
        Code::HTTP_EARLY_HINTS->value => 'Early Hints',
        Code::HTTP_OK->value => 'OK',
        Code::HTTP_CREATED->value => 'Created',
        Code::HTTP_ACCEPTED->value => 'Accepted',
        Code::HTTP_NON_AUTHORITATIVE_INFORMATION->value => 'Non-authoritative Information',
        Code::HTTP_NO_CONTENT->value => 'No Content',
        Code::HTTP_RESET_CONTENT->value => 'Reset Content',
        Code::HTTP_PARTIAL_CONTENT->value => 'Partial Content',
        Code::HTTP_MULTIPLE_CHOICES->value => 'Multiple Choices',
        Code::HTTP_MOVED_PERMANENTLY->value => 'Moved Permanently',
        Code::HTTP_FOUND->value => 'Found',
        Code::HTTP_SEE_OTHER->value => 'See Other',
        Code::HTTP_NOT_MODIFIED->value => 'Not Modified',
        Code::HTTP_USE_PROXY->value => 'Use Proxy',
        Code::HTTP_UNUSED->value => 'Unused',
        Code::HTTP_TEMPORARY_REDIRECT->value => 'Temporary Redirect',
        Code::HTTP_BAD_REQUEST->value => 'Bad Request',
        Code::HTTP_UNAUTHORIZED->value => 'Unauthorized',
        Code::HTTP_PAYMENT_REQUIRED->value => 'Payment Required',
        Code::HTTP_FORBIDDEN->value => 'Forbidden',
        Code::HTTP_NOT_FOUND->value => 'Not Found',
        Code::HTTP_METHOD_NOT_ALLOWED->value => 'Method Not Allowed',
        Code::HTTP_NOT_ACCEPTABLE->value => 'Not Acceptable',
        Code::HTTP_PROXY_AUTHENTICATION_REQUIRED->value => 'Proxy Authentication Required',
        Code::HTTP_REQUEST_TIMEOUT->value => 'Request Timeout',
        Code::HTTP_CONFLICT->value => 'Conflict',
        Code::HTTP_GONE->value => 'Gone',
        Code::HTTP_LENGTH_REQUIRED->value => 'Length Required',
        Code::HTTP_PRECONDITION_FAILED->value => 'Precondition Failed',
        Code::HTTP_REQUEST_ENTITY_TOO_LARGE->value => 'Request Entity Too Large',
        Code::HTTP_REQUEST_URI_TOO_LONG->value => 'Request URI Too Long',
        Code::HTTP_UNSUPPORTED_MEDIA_TYPE->value => 'Unsupported Media Type',
        Code::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE->value => 'Requested Range Not Satisfiable',
        Code::HTTP_EXPECTATION_FAILED->value => 'Expectation Failed',
        Code::HTTP_INTERNAL_SERVER_ERROR->value => 'Internal Server Error',
        Code::HTTP_NOT_IMPLEMENTED->value => 'Not Implemented',
        Code::HTTP_BAD_GATEWAY->value => 'Bad Gateway',
        Code::HTTP_SERVICE_UNAVAILABLE->value => 'Service Unavailable',
        Code::HTTP_GATEWAY_TIMEOUT->value => 'Gateway Timeout',
        Code::HTTP_VERSION_NOT_SUPPORTED->value => 'Version Not Supported'
    ];

    final public static function codeToString(Code $code): string | null
    {
        return self::CODE_TO_STRING[$code->value] ?? null;
    }
}