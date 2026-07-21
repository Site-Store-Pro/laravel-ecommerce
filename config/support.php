<?php

return [
    'reply_domain' => env('SUPPORT_REPLY_DOMAIN', 'localhost'),

  'max_attachment_size_kb' => (int) env('SUPPORT_MAX_ATTACHMENT_KB', 5120),

    'allowed_mimes' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'application/pdf',
        'text/plain',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ],
];
