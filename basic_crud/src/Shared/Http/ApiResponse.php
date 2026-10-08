<?php
declare(strict_types=1);

namespace App\Shared\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * The JSON envelope every endpoint answers with.
 *
 * Keeping it in one place is also the only way to be sure every response
 * remembers JSON_PRESERVE_ZERO_FRACTION: without it a 10.0 price is
 * serialised as 10, so the same product came back differently depending on
 * which endpoint you asked.
 */
final class ApiResponse
{
    /** @param array<string, mixed> $data */
    public static function ok(array $data): JsonResponse
    {
        return self::json(['data' => $data], 200);
    }

    /** @param array<string, mixed> $data */
    public static function created(array $data, string $location): JsonResponse
    {
        $response = self::json(['data' => $data], 201);
        $response->headers->set('Location', $location);

        return $response;
    }

    /** @param list<mixed> $items */
    public static function collection(array $items): JsonResponse
    {
        return self::json(['data' => $items, 'count' => count($items)], 200);
    }

    public static function noContent(): Response
    {
        return new Response(status: 204);
    }

    public static function notFound(string $detail): JsonResponse
    {
        return self::error('not_found', $detail, 404);
    }

    public static function invalidJson(): JsonResponse
    {
        return self::error('invalid_json', 'Invalid JSON provided', 400);
    }

    public static function validationError(string $detail): JsonResponse
    {
        return self::error('validation_failed', $detail, 400);
    }

    /**
     * Reports the first violation, prefixed with the field it belongs to so
     * the client knows which one of them to fix.
     */
    public static function invalidPayload(ConstraintViolationListInterface $violations): JsonResponse
    {
        $violation = $violations->get(0);
        $field = trim($violation->getPropertyPath(), '[]');

        return self::validationError(
            $field === ''
                ? (string) $violation->getMessage()
                : sprintf('%s: %s', $field, $violation->getMessage())
        );
    }

    public static function serverError(string $code, string $detail): JsonResponse
    {
        return self::error($code, $detail, 500);
    }

    private static function error(string $code, string $detail, int $status): JsonResponse
    {
        return self::json(['error' => ['code' => $code, 'detail' => $detail]], $status);
    }

    /** @param array<string, mixed> $payload */
    private static function json(array $payload, int $status): JsonResponse
    {
        $response = new JsonResponse(status: $status);
        $response->setEncodingOptions($response->getEncodingOptions() | \JSON_PRESERVE_ZERO_FRACTION);
        $response->setData($payload);

        return $response;
    }
}