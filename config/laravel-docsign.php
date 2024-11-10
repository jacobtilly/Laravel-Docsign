<?php

return [
    /*
     * Interacting with the Inleed Docsign API requires an API key.
     * You can obtain your own API key at https://docsign.se.
     */
    'api_key' => env('DOCSIGN_API_KEY', 'your-api-key-here'),
    'callbacks' => [
        'enabled' => true,
        'document_complete_job' => \JacobTilly\LaravelDocsign\Jobs\DocumentCompleteJob::class,
        'party_sign_job' => \JacobTilly\LaravelDocsign\Jobs\PartySignJob::class,
    ],
];
