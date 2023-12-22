<?php

return [
    'storage' => storage_path('app/tntsearch'), // Specify the storage path for TNTSearch
    'fuzziness' => env('TNTSEARCH_FUZZINESS', false),
    'fuzzy' => [
        'prefix_length' => 2,
        'max_expansions' => 50,
        'distance' => 2,
    ],
    'asYouType' => false,
];
