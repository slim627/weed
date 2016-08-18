<?php

namespace CommonBundle\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    public static $STATUS_SUCCESS = "success";
    public static $STATUS_ERROR   = "error";

    /**
     * Генерация ответа от API
     * @param $data - данные ответа
     * @param string $status - статус ответа
     * @param $code - код HTTP состояния
     * @return JsonResponse
     */
    protected function response($data = null, $status = 'success', $code = Response::HTTP_OK)
    {
        $responseArray = [
            'status' => $status,
            'response' => $data,
            'updated_at' => (new \DateTime())->getTimestamp(),
        ];

        return new Response(json_encode($responseArray, JSON_UNESCAPED_UNICODE), $code, [
            'Content-Type' => 'application/json;charset=utf-8',
        ]);
    }

    /**
     * Возврат ответа об ошибке
     *
     * @param $message - Текст ошибки для отображения
     * @param null $error - Системный текст ошибки для отладки
     * @param mixed $data - Массив дополнительных данных
     * @param $code - код HTTP состояния
     * @return JsonResponse
     */
    protected function error($message, $error = null, $data = null, $code = Response::HTTP_BAD_REQUEST)
    {
        return $this->response(['error' => $error, 'error_message' => $message, 'data' => $data], self::$STATUS_ERROR, $code);
    }
}