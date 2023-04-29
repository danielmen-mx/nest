<?php

namespace App\Http\Controllers\Cupboard;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected $forbiddenRequest = false;
    protected $translate = true;

    public function translateSuccess($message, $params)
    {
        $key = strtolower($message);

        return  __('api.' . $key, $params);
    }

    public function translateError($message, $params)
    {
        $key = strtolower($message);

        return  __('api_error.' . $key, $params);
    }

    public function responseWithResource($resource, $message = '', $messageParams = [])
    {
        $message = $this->translateSuccess($message, $messageParams);

        if (request()->filled('integration')) {
            return $resource;
        }

        return $resource->additional([
            'message' => $message,
        ]);
    }

    public function responseWithData($data, $message = null, $messageParams = [], $statusCode = 200)
    {
        $message = $this->translateSuccess($message, $messageParams);

        $response = (object) [
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $statusCode);
    }

    public function responseWithError($exception, $message, $messageParams = [], $errors = null, $statusCode = 417)
    {
        if ($this->forbiddenRequest) {
            $statusCode = 403;
            $message = 'Forbidden';
        }

        if ($this->translate) {
            $message = $this->translateError($message, $messageParams);
        }

        $response = (object) [
            'message' => $message,
            'errors' => $errors,
            'exception' => $exception ? $exception->getMessage() : null,
        ];

        return response()->json($response, $statusCode);
    }

    public function responseWithMessage($message, $messageParams = [], $statusCode = 200)
    {
        return $this->responseWithData(null, $message, $messageParams, $statusCode);
    }

    public function responseWithErrorMessage($message, $messageParams = [], $statusCode = 417)
    {
        return $this->responseWithError(null, $message, $messageParams, null, $statusCode);
    }

    public function dontTranslate()
    {
        $this->translate = false;
        return $this;
    }
}
