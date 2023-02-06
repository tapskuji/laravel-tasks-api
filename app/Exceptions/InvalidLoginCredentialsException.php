<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;

class InvalidLoginCredentialsException extends Exception
{
    protected $code = Response::HTTP_BAD_REQUEST;

    public function report()
    {

    }
    public function render($request)
    {
        return new JsonResponse([
            'message' => 'Login Failed',
            'errors' => [
                'credentials' => [
                    'Invalid email or password',
                ]
            ],
        ], $this->code);
    }
}
