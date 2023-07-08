<?php

namespace Framework\Enums;

enum HttpMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case DELETE = 'DELETE';
    case PUT = 'PUT';
}