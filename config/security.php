<?php

return [
    'access_control' => [
        [
            'path' => '/(login|sign_in|register)',
            'roles' => [
                'Anonymous'
            ]
        ],
        [
            'path' => '/(.*)',
            'roles' => [
                'ROLE_USER'
            ]
        ],
    ],
    'login' => 'login'
];