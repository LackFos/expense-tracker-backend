<?php

namespace App\Enums;

enum StatusCode
{
    const OK = 200;
    const CREATED = 201;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const NOT_FOUND = 404;
    const CONFLICT = 409;
    const TOO_MANY_REQUEST = 429;
    const INTERNAL_SERVER_ERROR = 500;
}
