<?php

//* ce n'est pas optimal encore, mais vous pouvez supprimer toutes les images dans storage/app/glide
//* En utilisant php artisan tinker et cette commande: 
/* 
use Illuminate\Support\Facades\File;
File::cleanDirectory(storage_path('app/glide')); 
*/


return [
    'source' => 'storage/app', // Path to the source directory of original images
    'cache' => 'storage/app/glide', // Path to the cache directory for manipulated images
    'base_url' => 'img/glide', // Base URL for the Glide route
    'defaults' => [
        'fm' => 'jpg', // Default output format
        'fit' => 'max', // Default fit mode
    ],
    'presets' => [
        'xs' => [
            'w' => 100,
            'h' => 150,
            'fit' => 'crop',
        ],
        'sm' => [
            'w' => 320,
            'h' => 240,
            'fit' => 'crop',
        ],
        'md' => [
            'w' => 640,
            'h' => 480,
            'fit' => 'crop',
        ],
        'lg' => [
            'w' => 800,
            'h' => 600,
            'fit' => 'crop',
        ],
        'xl' => [
            'w' => 1024,
            'h' => 768,
            'fit' => 'crop',
        ],
    ],
];