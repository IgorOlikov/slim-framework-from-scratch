<?php

namespace Framework\Fig;

interface RequestMethodInterface
{
    const string METHOD_HEAD    = 'HEAD';
    const string METHOD_GET     = 'GET';
    const string METHOD_POST    = 'POST';
    const string METHOD_PUT     = 'PUT';
    const string METHOD_PATCH   = 'PATCH';
    const string METHOD_DELETE  = 'DELETE';
    const string METHOD_PURGE   = 'PURGE';
    const string METHOD_OPTIONS = 'OPTIONS';
    const string METHOD_TRACE   = 'TRACE';
    const string METHOD_CONNECT = 'CONNECT';
}