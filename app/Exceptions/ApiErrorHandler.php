<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiErrorHandler {
    protected $errorCode = 500, $errorMessage;

    public function __construct(int $errorCode, $errorMessage = null) {
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }

    public function throwException() {
        $responseMsg = null;

        switch ($this->errorCode) {
            case 400:
                $responseMsg = 'Request sent could not be understood by the server';
                break;

            case 401:
                $responseMsg = 'Unauthorized access detected';
                break;

            case 403:
                $responseMsg = 'Forbidden. Cannot access this resource';
                break;

            case 404:
                $responseMsg = 'Resource item cannot be found';
                break;

            case 405:
                $responseMsg = 'Method not allowed';
                break;
                
            case 422:
                $responseMsg = 'Unprocessable entity detected';
                break;

            default:
                $responseMsg = 'Whoops, looks like something went wrong';
                break;
        }

        throw new HttpException(
            $this->errorCode,
            $this->errorMessage ?? $responseMsg
        );
    }
}