<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CustomException extends Exception
{
    protected $error;

    public function __construct($message, $error, $code = 400)
    {
        $this->error = $error;

        parent::__construct($message, $code);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @return JsonResponse
     */
    public function render()
    {
        return response()->json([
            "message" => $this->getMessage(),
            "error" => $this->error
        ], $this->getCode());
    }
}
