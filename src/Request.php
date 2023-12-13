<?php

declare(strict_types=1);

namespace SimpleMehanizm\Http;

use SimpleMehanizm\Http\Protocol\Headers;
use SimpleMehanizm\Http\Protocol\ContentType;
use SimpleMehanizm\Http\Protocol\MagicStrings;
use SimpleMehanizm\Http\Protocol\FastCGI\ServerKeys;
use SimpleMehanizm\Http\Exceptions\JsonDecodingException;
use function SimpleMehanizm\Array\Functions\readValue;
use function SimpleMehanizm\Array\Functions\arrayKeyExists;

class Request
{
    protected array $headers = [];
    protected string|null $method = null;
    protected string $uriPath;

    public function __construct(
        protected array $get = [],
        protected array $post = [],
        protected array $request = [],
        array $headers = [],
        protected array $cookies = [],
        protected array $files = [],
        protected array $server = [],
        protected bool $usePhpInputStream = true,
        protected string $httpBody = ''
    )
    {
        $this->method = $this->server(ServerKeys::REQUEST_METHOD->value);

        foreach($headers as $key => $value)
        {
            $this->headers[strtolower($key)] = $value;
        }

        if($this->isJson())
        {
            $json = $this->getArrayFromJsonInput(
                $this->usePhpInputStream ? $this->getHttpBody() : $this->httpBody
            );
            $this->request = array_merge($this->request, $json);
            $this->post = $json;
        }
    }

    public static function fromSuperglobals(): static
    {
        return new self(
            $_GET,
            $_POST,
            $_REQUEST,
            get_http_headers($_SERVER),
            $_COOKIE,
            $_FILES,
            $_SERVER
        );
    }

    public function getHttpBody(): string
    {
        return file_get_contents('php://input');
    }

    public function getArrayFromJsonInput(string $body): array
    {
        try
        {
            $json = empty($body) ? '[]' : $body;

            return json_decode($json, true, 512, \JSON_BIGINT_AS_STRING | \JSON_THROW_ON_ERROR);
        }
        catch(\JsonException $e)
        {
            throw new JsonDecodingException('Cannot decode JSON in HTTP body. Error: '. $e->getMessage(), $e->getCode());
        }
    }

    public function header(string $key): string|int|float|null
    {
        return $this->headers[strtolower($key)] ?? null;
    }

    public function hasHeader(string $key): bool
    {
        return isset($this->headers[strtolower($key)]);
    }

    public function setHeader(string $key, string $value): void
    {
        $this->headers[strtolower($key)] = $value;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function input(string $key, mixed $default = null): string|int|float|array|null
    {
        return readValue($this->request, $key, $default);
    }

    public function hasInput(string $key): bool
    {
        return arrayKeyExists($this->request, $key);
    }

    public function file(string $key): string|int|float|null
    {
        return $this->files[$key] ?? null;
    }

    public function get(string $key, mixed $default = null): string|int|float|array|null
    {
        return readValue($this->get, $key, $default);
    }

    public function post(string $key, mixed $default = null): string|int|float|array|null
    {
        return readValue($this->post, $key, $default);
    }

    public function cookie(string $key): string|int|float|null
    {
        return $this->cookies[$key] ?? null;
    }

    public function server(string $key): string|int|float|array|null
    {
        return $this->server[$key] ?? null;
    }

    public function getHttpMethod(): string
    {
        // Return early if already processed (to lazy reader: cache result of the method)
        if(!empty($this->method)) return $this->method;

        $method = $this->server(ServerKeys::REQUEST_METHOD->value);

        if($this->hasHeader(MagicStrings::METHOD_OVERRIDE->value))
        {
            $method = $this->header(MagicStrings::METHOD_OVERRIDE->value);
        }
        elseif($this->hasInput(MagicStrings::FORM_METHOD_OVERRIDE->value))
        {
            $method = $this->input(MagicStrings::FORM_METHOD_OVERRIDE->value);
        }

        $this->method = $method;

        return $this->method;
    }

    public function getPath(): string | null
    {
        if(!empty($this->uriPath)) return $this->uriPath;

        $uri = $this->server(ServerKeys::REQUEST_URI->value);

        $result = $uri;

        if(empty($result) || str_starts_with($result, MagicStrings::FORWARD_SLASH->value))
        {
            if(false !== $pos = strpos($result, MagicStrings::QUERYSTRING_SEPARATOR->value))
            {
                $result = substr($result, 0, $pos);
            }

            // Fragment can be sent via non-browser http client, remove it
            if(false !== $pos = strpos($result, MagicStrings::HTTP_FRAGMENT->value))
            {
                $result = substr($result, 0, $pos);
            }
        }
        else
        {
            $parts = parse_url($result);

            $result = $parts['path'] ?? '';
        }

        $this->uriPath = $result;

        return $this->uriPath;
    }

    public function getOriginalPath(): string
    {
        return $this->server(ServerKeys::REQUEST_URI->value);
    }

    public function isJson(): bool
    {
        return $this->header(Headers::CONTENT_TYPE->value) === ContentType::APP_JSON->value;
    }

    public function isXmlHttpRequest(): bool
    {
        return $this->header(Headers::X_REQ_WITH->value) === MagicStrings::XHR->value;
    }
}