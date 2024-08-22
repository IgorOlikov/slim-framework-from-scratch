<?php

namespace Framework\Psr7Request;

use Framework\Fig\StatusCodeInterface;
use Framework\Psr\Http\Message\ResponseInterface;
use Framework\Psr\Http\Message\StreamInterface;
use Framework\Psr7Request\Factory\StreamFactory;
use Framework\Psr7Request\Interfaces\HeadersInterface;
use Override;

class Response extends Message implements ResponseInterface
{
    protected int $status = StatusCodeInterface::STATUS_OK;

    protected string $reasonPhrase = '';

    protected static array $messages = [
        // Informational 1xx
        StatusCodeInterface::STATUS_CONTINUE => 'Continue',
        StatusCodeInterface::STATUS_SWITCHING_PROTOCOLS => 'Switching Protocols',
        StatusCodeInterface::STATUS_PROCESSING => 'Processing',

        // Successful 2xx
        StatusCodeInterface::STATUS_OK => 'OK',
        StatusCodeInterface::STATUS_CREATED => 'Created',
        StatusCodeInterface::STATUS_ACCEPTED => 'Accepted',
        StatusCodeInterface::STATUS_NON_AUTHORITATIVE_INFORMATION => 'Non-Authoritative Information',
        StatusCodeInterface::STATUS_NO_CONTENT => 'No Content',
        StatusCodeInterface::STATUS_RESET_CONTENT => 'Reset Content',
        StatusCodeInterface::STATUS_PARTIAL_CONTENT => 'Partial Content',
        StatusCodeInterface::STATUS_MULTI_STATUS => 'Multi-Status',
        StatusCodeInterface::STATUS_ALREADY_REPORTED => 'Already Reported',
        StatusCodeInterface::STATUS_IM_USED => 'IM Used',

        // Redirection 3xx
        StatusCodeInterface::STATUS_MULTIPLE_CHOICES => 'Multiple Choices',
        StatusCodeInterface::STATUS_MOVED_PERMANENTLY => 'Moved Permanently',
        StatusCodeInterface::STATUS_FOUND => 'Found',
        StatusCodeInterface::STATUS_SEE_OTHER => 'See Other',
        StatusCodeInterface::STATUS_NOT_MODIFIED => 'Not Modified',
        StatusCodeInterface::STATUS_USE_PROXY => 'Use Proxy',
        StatusCodeInterface::STATUS_RESERVED => '(Unused)',
        StatusCodeInterface::STATUS_TEMPORARY_REDIRECT => 'Temporary Redirect',
        StatusCodeInterface::STATUS_PERMANENT_REDIRECT => 'Permanent Redirect',

        // Client Error 4xx
        StatusCodeInterface::STATUS_BAD_REQUEST => 'Bad Request',
        StatusCodeInterface::STATUS_UNAUTHORIZED => 'Unauthorized',
        StatusCodeInterface::STATUS_PAYMENT_REQUIRED => 'Payment Required',
        StatusCodeInterface::STATUS_FORBIDDEN => 'Forbidden',
        StatusCodeInterface::STATUS_NOT_FOUND => 'Not Found',
        StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED => 'Method Not Allowed',
        StatusCodeInterface::STATUS_NOT_ACCEPTABLE => 'Not Acceptable',
        StatusCodeInterface::STATUS_PROXY_AUTHENTICATION_REQUIRED => 'Proxy Authentication Required',
        StatusCodeInterface::STATUS_REQUEST_TIMEOUT => 'Request Timeout',
        StatusCodeInterface::STATUS_CONFLICT => 'Conflict',
        StatusCodeInterface::STATUS_GONE => 'Gone',
        StatusCodeInterface::STATUS_LENGTH_REQUIRED => 'Length Required',
        StatusCodeInterface::STATUS_PRECONDITION_FAILED => 'Precondition Failed',
        StatusCodeInterface::STATUS_PAYLOAD_TOO_LARGE => 'Request Entity Too Large',
        StatusCodeInterface::STATUS_URI_TOO_LONG => 'Request-URI Too Long',
        StatusCodeInterface::STATUS_UNSUPPORTED_MEDIA_TYPE => 'Unsupported Media Type',
        StatusCodeInterface::STATUS_RANGE_NOT_SATISFIABLE => 'Requested Range Not Satisfiable',
        StatusCodeInterface::STATUS_EXPECTATION_FAILED => 'Expectation Failed',
        StatusCodeInterface::STATUS_IM_A_TEAPOT => 'I\'m a teapot',
        StatusCodeInterface::STATUS_MISDIRECTED_REQUEST => 'Misdirected Request',
        StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY => 'Unprocessable Entity',
        StatusCodeInterface::STATUS_LOCKED => 'Locked',
        StatusCodeInterface::STATUS_FAILED_DEPENDENCY => 'Failed Dependency',
        StatusCodeInterface::STATUS_UPGRADE_REQUIRED => 'Upgrade Required',
        StatusCodeInterface::STATUS_PRECONDITION_REQUIRED => 'Precondition Required',
        StatusCodeInterface::STATUS_TOO_MANY_REQUESTS => 'Too Many Requests',
        StatusCodeInterface::STATUS_REQUEST_HEADER_FIELDS_TOO_LARGE => 'Request Header Fields Too Large',
        444 => 'Connection Closed Without Response',
        StatusCodeInterface::STATUS_UNAVAILABLE_FOR_LEGAL_REASONS => 'Unavailable For Legal Reasons',
        499 => 'Client Closed Request',

        // Server Error 5xx
        StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR => 'Internal Server Error',
        StatusCodeInterface::STATUS_NOT_IMPLEMENTED => 'Not Implemented',
        StatusCodeInterface::STATUS_BAD_GATEWAY => 'Bad Gateway',
        StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE => 'Service Unavailable',
        StatusCodeInterface::STATUS_GATEWAY_TIMEOUT => 'Gateway Timeout',
        StatusCodeInterface::STATUS_VERSION_NOT_SUPPORTED => 'HTTP Version Not Supported',
        StatusCodeInterface::STATUS_VARIANT_ALSO_NEGOTIATES => 'Variant Also Negotiates',
        StatusCodeInterface::STATUS_INSUFFICIENT_STORAGE => 'Insufficient Storage',
        StatusCodeInterface::STATUS_LOOP_DETECTED => 'Loop Detected',
        StatusCodeInterface::STATUS_NOT_EXTENDED => 'Not Extended',
        StatusCodeInterface::STATUS_NETWORK_AUTHENTICATION_REQUIRED => 'Network Authentication Required',
        599 => 'Network Connect Timeout Error',
    ];

    public function __construct(
        int $status = StatusCodeInterface::STATUS_OK,
        ?HeadersInterface $headers = null,
        ?StreamInterface $body = null
    )
    {
        $this->status = $this->filterStatus($status);
        $this->headers = $headers ?: new Headers([],[]);
        $this->body = $body ?: (new StreamFactory())->createStream();
    }

    #[Override] public function getStatusCode(): int
    {
        // TODO: Implement getStatusCode() method.
    }

    #[Override] public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        // TODO: Implement withStatus() method.
    }

    #[Override] public function getReasonPhrase(): string
    {
        // TODO: Implement getReasonPhrase() method.
    }
}