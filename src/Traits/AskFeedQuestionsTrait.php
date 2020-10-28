<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits;

use TimothyDC\LightspeedEcomProductFeed\Feeds\StandardFeed;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;

trait AskFeedQuestionsTrait
{
    use AskUntilValidTrait;
    use FeedValidationRulesTrait;
    use WebshopInteractionTrait;

    protected function askMappingClass(ProductFeed $feed = null): string
    {
        return $this->askWithValidation(
            'Enter your custom mapping class',
            'mapping_class',
            $this->mappingClassRules(),
            $feed && $feed->mapping_class ? $feed->mapping_class : StandardFeed::class
        );
    }

    protected function askCronExpression(ProductFeed $feed = null): string
    {
        return $this->anticipateWithValidation(
            'Enter your cron interval',
            fn ($input) => ['* * * * *', '*/30 * * * *', '0 */2 * * *', '0 */4 * * *', '0 0 * * *'],
            'cron_expression',
            $this->cronExpressionRules(),
            $feed ? $feed->cron_expression : '30 */4 * * *'
        );
    }

    protected function askApiKey(ProductFeed $feed = null): string
    {
        return $this->askWithValidation(
            'Enter your webshop API key',
            'api key',
            ['required'],
            $feed ? $feed->api_key : null
        );
    }

    protected function askApiSecret(ProductFeed $feed = null): string
    {
        return $this->askWithValidation(
            'Enter your webshop API secret',
            'api secret',
            ['required'],
            $feed ? $feed->api_secret : null
        );
    }

    protected function askLanguage(ProductFeed $feed = null): string
    {
        return $this->choice(
            'What language should your feed be in?',
            ['nl', 'en', 'de', 'fr'],
            $feed ? $feed->language : null
        );
    }
}
