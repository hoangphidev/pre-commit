<?php

use HoangPhi\PreCommit\Commands\PreCommitHookCommand;

return [

    'enabled' => env('PRE_COMMIT_ENABLED', true),

    'psr2' => [
        'standard' => __DIR__ . '/../phpcs.xml',
        'ignored' => [
            '*/database/*',
            '*/public/*',
            '*/assets/*',
            '*/vendor/*',
        ],
    ],

    'hooks' => [
        'pre-commit' => PreCommitHookCommand::class,
    ],
];
