<?php

return [
    /*
     * Interacting with the Inleed Docsign API requires an API key.
     * You can obtain your own API key at https://docsign.se.
     */
    'api_key' => env('DOCSIGN_API_KEY', 'your-api-key-here'),
    /*
    * The callbacks configuration allows you to enable or disable the
    * handling of callbacks from Inleed Docsign. If enabled, a callback route
    * will be registered and the package will attempt to dispatch jobs for
    * the following events:
    * - Document complete
    * - Party sign
    */
    'callbacks' => [
        'enabled' => true,
        'document_complete_job' => \JacobTilly\LaravelDocsign\Jobs\DocsignDocumentCompleteJob::class,
        'party_sign_job' => \JacobTilly\LaravelDocsign\Jobs\DocsignPartySignJob::class,
        'document_complete_callback_url' => function () { return route("docsign.callbacks.document-complete"); },
        'party_sign_callback_url' => function () { return route("docsign.callbacks.party-sign"); },
    ],
];
