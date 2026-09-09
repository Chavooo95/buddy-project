<?php
declare(strict_types=1);

namespace App\Shared\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
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
    /** @param array<string, mixed> $payload */
    public static function ok(array $payload): JsonResponse
    {
        return self::json(['success' => true] + $payload, 200);
    }

    /** @param array<string, mixed> $payload */
    public static function created(array $payload): JsonResponse
    {
        return self::json(['success' => true] + $payload, 201);
    }

    public static function notFound(string $message): JsonResponse
    {
        return self::json(['success' => false, 'message' => $message], 404);
    }

    public static function invalidJson(): JsonResponse
    {
        return self::json(['success' => false, 'message' => 'Invalid JSON provided'], 400);
    }

    public static function validationError(string $error): JsonResponse
    {
        return self::json([
            'success' => false,
            'message' => 'Validation error',
            'error' => $error,
        ], 400);
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

    public static function serverError(string $message, string $error): JsonResponse
    {
        return self::json([
            'success' => false,
            'message' => $message,
            'error' => $error,
        ], 500);
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