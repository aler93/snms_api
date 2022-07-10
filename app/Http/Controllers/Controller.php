<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Throwable;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    protected array $HttpStatus;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->HttpStatus = array_keys(Response::$statusTexts);
    }

    public function json($data, int $status = 200): JsonResponse
    {
        return response()->json($data, $status);
    }

    public function jsonException(Throwable $e, int $status = 500): JsonResponse
    {
        if( $e->getCode() > 0 ) {
            $status = $e->getCode();
        }

        if( !in_array($status, $this->HttpStatus) ) {
            $status = 500;
        }

        $response = [
            "title" => "An unexpected error has occured",
            "message" => $e->getMessage(),
            "icon" => "error",
            "status" => $status
        ];

        if(env("APP_DEBUG")) {
            $response["file"] = $e->getFile();
            $response["line"] = $e->getLine();
            $response["trace"] = $e->getTrace();
        }

        return $this->json($response, $status);
    }
}
