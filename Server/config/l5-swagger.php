<?php

return [
    'api' => [
        'title' => 'EcoHeroes API',
        'description' => 'API Documentation for EcoHeroes',
        'version' => '1.0.0',
        'host' => env('APP_URL', 'http://localhost'),
        'basePath' => '/api',
        'schemes' => ['http', 'https'],
        'consumes' => ['application/json'],
        'produces' => ['application/json'],
    ],

    'routes' => [
        'api' => 'api/documentation',
        'docs' => 'docs',
        'oauth2_callback' => 'api/oauth2-callback',
        'middleware' => [
            'api' => [],
            'assets' => [],
            'docs' => [],
            'oauth2_callback' => [],
        ],
        'group_options' => [],
    ],

    'paths' => [
        'docs' => storage_path('api-docs'),
        'docs_json' => 'api-docs.json',
        'docs_yaml' => 'api-docs.yaml',
        'annotations' => [
            base_path('app'),
        ],
        'excludes' => [
            base_path('app/Http/Controllers/Auth'),
            base_path('app/Http/Controllers/API'),
        ],
        'base' => env('L5_SWAGGER_BASE_PATH', null),
        'swagger' => [
            'paths' => [
                base_path('app/Http/Controllers'),
            ],
        ],
    ],

    'security' => [
        'passport' => [
            'type' => 'oauth2',
            'flow' => 'password',
            'tokenUrl' => env('APP_URL') . '/oauth/token',
            'scopes' => [],
        ],
    ],

    'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', true),
    'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),
    'proxy' => false,
    'additional_config_url' => null,
    'operations_sort' => null,
    'validator_url' => null,
    'ui' => [
        'display' => [
            'doc_expansion' => 'none',
            'filter' => true,
        ],
        'authorization' => [
            'persist_authorization' => true,
        ],
    ],
]; 