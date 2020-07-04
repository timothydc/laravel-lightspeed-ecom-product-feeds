<?php

declare(strict_types=1);

return [
    /*
     * Enter the filesystem disk where you want to save the generated feeds
     */
    'feed_disk' => env('LS_PRODUCT_FEEDS_FEED_DISK', 'public'),

    'scheduled_tasks' => [
        /*
         * Define if you want to automatically add the product feeds to task scheduler
         */
        'auto_run' => env('LS_PRODUCT_FEEDS_AUTO_RUN', true),
        /*
         * Define if you want to process the feeds through a job
         */
        'use_queue' => env('LS_PRODUCT_FEEDS_USE_QUEUE', true),
        /*
         * Define the queue on which the jobs should be run
         */
        'queue' => env('LS_PRODUCT_FEEDS_QUEUE', 'default'),
    ],
];
