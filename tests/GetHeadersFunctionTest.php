<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use function SimpleMehanizm\Http\get_http_headers;

class GetHeadersFunctionTest extends TestCase
{
    #[DataProvider('provideTestData')]
    public function test_get_headers_function_by_passing_array_as_params(array $input, array $expected): void
    {
        $result = get_http_headers($input);

        $this->assertEquals($expected, $result);
    }

    public static function provideTestData(): array
    {
        return [
            'invalid input provided, empty result returned' => [
                [['not a string key for first outer element' => ['value']]],    // input
                []                                                              // expected
            ],

            'valid input array provided' => [
                [
                    'HTTP_CONTENT_TYPE' => 'text/javascript',
                    'HTTP_CHARSET' => 'utf-8'
                ],
                [
                    'Content-Type' => 'text/javascript',
                    'Charset' => 'utf-8'
                ]
            ]
        ];
    }
}