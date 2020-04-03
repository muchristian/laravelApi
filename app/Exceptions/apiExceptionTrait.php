<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
trait apiExceptionTrait
{
    public function apiException($request, $e) {

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'A written route not found'
              ], Response::HTTP_NOT_FOUND);
        }
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Model not found'
              ], Response::HTTP_NOT_FOUND);
        }
        return parent::render($request, $exception);
    }
}