<?php

return [
    'publishable_key' => env('STRIPE_KEY'), 

    'secret_key' => env('STRIPE_SECRET'), 

    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
];
