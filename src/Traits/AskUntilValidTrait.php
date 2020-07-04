<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits;

use Illuminate\Support\Facades\Validator;

trait AskUntilValidTrait
{
    protected function askWithValidation($question, $field, $rules, $default = null): string
    {
        $value = $this->ask($question, $default);

        if ($message = $this->validateInput($rules, $field, $value)) {
            $this->error($message);

            return $this->askWithValidation($question, $field, $rules);
        }

        return $value;
    }

    protected function anticipateWithValidation($question, $choices, $field, $rules, $default = null): string
    {
        $value = $this->anticipate($question, $choices, $default);

        if ($message = $this->validateInput($rules, $field, $value)) {
            $this->error($message);

            return $this->anticipateWithValidation($question, $choices, $field, $rules, $default);
        }

        return $value;
    }

    protected function validateInput($rules, $fieldName, $value): ?string
    {
        $validator = Validator::make([
            $fieldName => $value,
        ], [
            $fieldName => $rules,
        ]);

        return $validator->fails()
            ? $validator->errors()->first($fieldName)
            : null;
    }
}
