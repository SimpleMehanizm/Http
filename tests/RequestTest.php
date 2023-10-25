<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SimpleMehanizm\Http\Request;
use SimpleMehanizm\Http\Protocol\Method;
use SimpleMehanizm\Http\Protocol\Headers;
use SimpleMehanizm\Http\Protocol\ContentType;
use PHPUnit\Framework\Attributes\DataProvider;
use SimpleMehanizm\Http\Protocol\MagicStrings;
use SimpleMehanizm\Http\Protocol\FastCGI\ServerKeys;
use SimpleMehanizm\Http\Exceptions\JsonDecodingException;

class RequestTest extends TestCase
{
    #[DataProvider('providePathsForPathTest')]
    public function test_parse_path_supplied_via_http_request(string $expected, string $actual): void
    {
        $request = new Request(
            server: [ServerKeys::REQUEST_URI->value => $actual]
        );

        $this->assertEquals($expected, $request->getPath());
        $this->assertEquals($actual, $request->getOriginalPath());
    }

    #[DataProvider('provideDataForHttpMethodTest')]
    public function test_get_http_method_returns_correct_value(string $expected, array $input): void
    {
        $request = new Request(...$input);

        $this->assertEquals($expected, $request->getHttpMethod());
    }

    #[DataProvider('provideHeadersForTest')]
    public function test_getting_headers_with_casing_ignored_succeeds(array $expected, array $actual): void
    {
        $request = new Request(
            headers: $actual
        );

        $this->assertEquals($expected, $request->headers());

        foreach($expected as $header => $value)
        {
            $this->assertEquals($value, $request->header($header));
        }
    }

    #[DataProvider('provideHeadersForJsonTest')]
    public function test_request_is_json_works_as_expected(bool $expected, array $headers): void
    {
        $request = new Request(
            headers: $headers
        );

        $this->assertEquals($expected, $request->isJson());
    }

    #[DataProvider('provideHeadersForXmlHttpRequest')]
    public function test_is_xhr_works_as_expected(bool $expected, array $headers): void
    {
        $request = new Request(
            headers: $headers
        );

        $this->assertEquals($expected, $request->isXmlHttpRequest());
    }

    #[DataProvider('provideGetParametersForInputGettersTest')]
    public function test_querystring_parameter_retrieval_succeeds(mixed $expected, array $input, string $path): void
    {
        $request = new Request(
            get: $input,
            request: $input
        );

        $this->assertEquals($expected, $request->get($path));
        $this->assertEquals($expected, $request->input($path));
    }

    #[DataProvider('provideGetParametersForInputGettersTest')]
    public function test_post_parameter_retrieval_succeeds(mixed $expected, array $input, string $path): void
    {
        $request = new Request(
            post: $input,
            request: $input
        );

        $this->assertEquals($expected, $request->post($path));
        $this->assertEquals($expected, $request->input($path));
    }

    #[DataProvider('provideJsonDataForHttpBody')]
    public function test_retrieve_input_parameters_from_json_body(mixed $expected, string $json, string $key): void
    {
        if('exception' === $expected)
        {
            $this->expectException(JsonDecodingException::class);
        }

        $request = new Request(
            headers: [Headers::CONTENT_TYPE->value => ContentType::APP_JSON->value],
            usePhpInputStream: false,
            httpBody: $json
        );

        $this->assertEquals($expected, $request->input($key));
    }

    public static function providePathsForPathTest(): array
    {
        return [
            'empty string for path supplied' => [
                '',                                 // expected
                ''                                  // actual (input)
            ],

            'path with forward slashes' => [
                '/this/is/path',                    // expected
                '/this/is/path'                     // actual (input)
            ],

            'path with querystring, querystring is ignored' => [
                '/root',
                '/root?param=1&second=2'
            ],

            'path with fragment, fragment is ignored' => [
                '/root',
                '/root#look-fragment-here'
            ],

            'path with querystring and fragment, both are ignored' => [
                '/root',
                '/root?querystring=value#fragment-here'
            ],

            'path uses http proxy and is empty' => [
                '',
                'https://my.domain.tld'
            ],

            'path uses http proxy and contains querystring, querystring is ignored' => [
                '/',
                'https://my.domain.tld/?param=value'
            ],

            'path uses http proxy and contains fragment, fragment is ignored' => [
                '/',
                'https://my.domain.tld/#value'
            ],

            'path uses http proxy and contains querystring with fragment, both are ignored' => [
                '/test',
                'https://my.domain.tld/test?query=string#value'
            ]
        ];
    }

    public static function provideDataForHttpMethodTest(): array
    {
        return [
            'GET method, in $_SERVER' => [
                Method::GET->value, [
                    'server' => [ServerKeys::REQUEST_METHOD->value => Method::GET->value]
                ]
            ],

            'POST method, in $_SERVER' => [
                Method::POST->value, [
                    'server' => [ServerKeys::REQUEST_METHOD->value => Method::POST->value]
                ]
            ],

            'PUT method, in header' => [
                Method::PUT->value, [
                    'headers' => [MagicStrings::METHOD_OVERRIDE->value => Method::PUT->value]
                ]
            ],

            'CONNECT method, in HTML form override' => [
                Method::CONNECT->value, [
                    'request' => [MagicStrings::FORM_METHOD_OVERRIDE->value => Method::CONNECT->value]
                ]
            ]
        ];
    }

    public static function provideHeadersForTest(): array
    {
        return [
            'array of headers that contain uppercased naming scheme' => [
                ['accept-language' => 'en-US,en;q=0.9', 'accept-encoding' => 'gzip, deflate, br'],  // expected
                ['Accept-Language' => 'en-US,en;q=0.9', 'Accept-Encoding' => 'gzip, deflate, br']   // actual
            ]
        ];
    }

    public static function provideHeadersForJsonTest(): array
    {
        return [
            'accepts json' => [
                true, [Headers::CONTENT_TYPE->value => ContentType::APP_JSON->value]
            ],

            'not json' => [
                false, [Headers::CONTENT_TYPE->value => ContentType::APP_XML->value]
            ]
        ];
    }

    public static function provideHeadersForXmlHttpRequest(): array
    {
        return [
            'is XHR' => [
                true, [
                    Headers::X_REQ_WITH->value => MagicStrings::XHR->value
                ]
            ],

            'is not XHR when x-requested-with is missing' => [
                false, []
            ],

            'is not XHR when x-requested-with is present but contains wrong value' => [
                false, [
                    Headers::X_REQ_WITH->value => 'incorrect value'
                ]
            ],
        ];
    }

    public static function provideGetParametersForInputGettersTest(): array
    {
        return [
            'no parameters' => [
                null, [], 'path.to.key'
            ],

            'single parameter' => [
                'hello', ['value' => 'hello'], 'value'
            ],

            'nested parameter' => [
                1, ['value' => ['key' => 1]], 'value.key'
            ],

            'nonexistent value' => [
                null, ['array' => ['collection' => ['dictionary' => ['hashmap' => ['tuple']]]]], 'key.does.not.exist'
            ],

            'array value returned' => [
                ['hello' => 'world'], ['input' => ['hello' => 'world']], 'input'
            ]
        ];
    }

    public static function provideJsonDataForHttpBody(): array
    {
        return [
            'empty body, empty array is created for JSON body' => [
                null, json_encode([]), 'key-to-access'
            ],

            'single key-value element in JSON body' => [
                'value', json_encode(['key' => 'value']), 'key'
            ],

            'throws exception because HTTP body is invalid JSON' => [
                'exception', 'a - b - c - d', 'key'
            ]
        ];
    }
}