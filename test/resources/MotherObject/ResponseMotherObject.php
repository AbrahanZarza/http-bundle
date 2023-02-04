<?php

declare(strict_types=1);

namespace AbrahanZarza\HttpBundle\TestResources\MotherObject;

use DateTime;
use Symfony\Component\HttpFoundation\Response;

final class ResponseMotherObject
{
    public const DATETIME_FORMAT = 'Y-m-d H:i:s';
    public const SAMPLE_DATETIME_STRING = '2023-10-10 16:00:00';

    public static function success(): Response
    {
        $response = new Response('Success', Response::HTTP_OK);
        self::normalizeResponseDate($response);

        return $response;
    }

    public static function notFound(): Response
    {
        $response = new Response('Not found', Response::HTTP_NOT_FOUND);
        self::normalizeResponseDate($response);

        return $response;
    }

    public static function normalizeResponseDate(Response &$response): void
    {
        $response->setDate(DateTime::createFromFormat(self::DATETIME_FORMAT, self::SAMPLE_DATETIME_STRING));
    }
}