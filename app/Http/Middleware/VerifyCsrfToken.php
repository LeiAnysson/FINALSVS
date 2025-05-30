<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * Add your API routes here if you want to exclude them.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',  // For example, to exclude API routes from CSRF
    ];
}
