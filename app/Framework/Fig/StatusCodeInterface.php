<?php

namespace Framework\Fig;

interface StatusCodeInterface
{
    // Informational 1xx
    const int STATUS_CONTINUE = 100;
    const int STATUS_SWITCHING_PROTOCOLS = 101;
    const int STATUS_PROCESSING = 102;
    const int STATUS_EARLY_HINTS = 103;
    // Successful 2xx
    const int STATUS_OK = 200;
    const int STATUS_CREATED = 201;
    const int STATUS_ACCEPTED = 202;
    const int STATUS_NON_AUTHORITATIVE_INFORMATION = 203;
    const int STATUS_NO_CONTENT = 204;
    const int STATUS_RESET_CONTENT = 205;
    const int STATUS_PARTIAL_CONTENT = 206;
    const int STATUS_MULTI_STATUS = 207;
    const int STATUS_ALREADY_REPORTED = 208;
    const int STATUS_IM_USED = 226;
    // Redirection 3xx
    const int STATUS_MULTIPLE_CHOICES = 300;
    const int STATUS_MOVED_PERMANENTLY = 301;
    const int STATUS_FOUND = 302;
    const int STATUS_SEE_OTHER = 303;
    const int STATUS_NOT_MODIFIED = 304;
    const int STATUS_USE_PROXY = 305;
    const int STATUS_RESERVED = 306;
    const int STATUS_TEMPORARY_REDIRECT = 307;
    const int STATUS_PERMANENT_REDIRECT = 308;
    // Client Errors 4xx
    const int STATUS_BAD_REQUEST = 400;
    const int STATUS_UNAUTHORIZED = 401;
    const int STATUS_PAYMENT_REQUIRED = 402;
    const int STATUS_FORBIDDEN = 403;
    const int STATUS_NOT_FOUND = 404;
    const int STATUS_METHOD_NOT_ALLOWED = 405;
    const int STATUS_NOT_ACCEPTABLE = 406;
    const int STATUS_PROXY_AUTHENTICATION_REQUIRED = 407;
    const int STATUS_REQUEST_TIMEOUT = 408;
    const int STATUS_CONFLICT = 409;
    const int STATUS_GONE = 410;
    const int STATUS_LENGTH_REQUIRED = 411;
    const int STATUS_PRECONDITION_FAILED = 412;
    const int STATUS_PAYLOAD_TOO_LARGE = 413;
    const int STATUS_URI_TOO_LONG = 414;
    const int STATUS_UNSUPPORTED_MEDIA_TYPE = 415;
    const int STATUS_RANGE_NOT_SATISFIABLE = 416;
    const int STATUS_EXPECTATION_FAILED = 417;
    const int STATUS_IM_A_TEAPOT = 418;
    const int STATUS_MISDIRECTED_REQUEST = 421;
    const int STATUS_UNPROCESSABLE_ENTITY = 422;
    const int STATUS_LOCKED = 423;
    const int STATUS_FAILED_DEPENDENCY = 424;
    const int STATUS_TOO_EARLY = 425;
    const int STATUS_UPGRADE_REQUIRED = 426;
    const int STATUS_PRECONDITION_REQUIRED = 428;
    const int STATUS_TOO_MANY_REQUESTS = 429;
    const int STATUS_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    const int STATUS_UNAVAILABLE_FOR_LEGAL_REASONS = 451;
    // Server Errors 5xx
    const int STATUS_INTERNAL_SERVER_ERROR = 500;
    const int STATUS_NOT_IMPLEMENTED = 501;
    const int STATUS_BAD_GATEWAY = 502;
    const int STATUS_SERVICE_UNAVAILABLE = 503;
    const int STATUS_GATEWAY_TIMEOUT = 504;
    const int STATUS_VERSION_NOT_SUPPORTED = 505;
    const int STATUS_VARIANT_ALSO_NEGOTIATES = 506;
    const int STATUS_INSUFFICIENT_STORAGE = 507;
    const int STATUS_LOOP_DETECTED = 508;
    const int STATUS_NOT_EXTENDED = 510;
    const int STATUS_NETWORK_AUTHENTICATION_REQUIRED = 511;
}