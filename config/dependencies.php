<?php

return [
    'blade' => [
        'npm' => [
            'prod' => ['alpinejs', 'livewire'],
            'dev' => [],
        ],
    ],
    'vue' => [
        'npm' => [
            'prod' => ['vue', 'pinia'],
            'dev' => ['vue-loader'],
        ],
    ],
    'inertia' => [
        'npm' => [
            'prod' => ['@inertiajs/inertia', '@inertiajs/inertia-vue3'],
            'dev' => [],
        ],
        'composer' => [
            'prod' => ['inertiajs/inertia-laravel'],
            'dev' => [],
        ],
    ],
];
