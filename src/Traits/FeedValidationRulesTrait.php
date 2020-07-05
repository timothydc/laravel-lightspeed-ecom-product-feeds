<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits;

use Cron\CronExpression;
use TimothyDC\LightspeedEcomProductFeed\Interfaces\ProductPayloadMappingInterface;

trait FeedValidationRulesTrait
{
    protected function mappingClassRules(): array
    {
        return [
            'bail',
            fn ($attribute, $value, $fail) => ! class_exists($value)
                ? $fail('Class "' . $value . '" not found.')
                : null,
            fn ($attribute, $value, $fail) => ! (new $value()) instanceof ProductPayloadMappingInterface
                ? $fail($value . ' must implement ' . ProductPayloadMappingInterface::class)
                : null,
        ];
    }

    protected function cronExpressionRules(): array
    {
        return [
            fn ($attribute, $value, $fail) => ! CronExpression::isValidExpression($value)
                ? $fail('"' . $value . '" is not a valid cron expression.')
                : null,
        ];
    }
}