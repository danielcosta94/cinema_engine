<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait JsonParser
{
    /**
     *  Returns a JSON response
     *
     * @param $data
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    protected function response($data, int $status = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * @param Request $request
     * @return Request
     */
    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if ($data !== null) {
            $request->request->replace($data);
        }

        return $request;
    }
}