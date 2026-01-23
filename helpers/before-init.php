<?php

if (!file_exists(JW_BASE_PATH . '/.env')) {
    copy(JW_BASE_PATH . '/.env.example', JW_BASE_PATH . '/.env');
    file_put_contents(JW_BASE_PATH . '/.env', str_replace(
        [
            'APP_KEY=',
            'APP_ENV=local',
        ],
        [
            'APP_KEY=base64:' . base64_encode(random_bytes(32)),
            'APP_ENV=production',
        ],
        file_get_contents(JW_BASE_PATH . '/.env')
    ));
}
