<?php

declare(strict_types=1);

namespace App\Services;

// Config is singleton instance store all config
final class Config
{
    private function __construct()
    {
    }

    public static function getPublicConfig()
    {
        return [
            'appName' => $_ENV['appName'],
            'baseUrl' => $_ENV['baseUrl'],

            'enable_checkin' => $_ENV['enable_checkin'],
            'checkinMin' => $_ENV['checkinMin'],
            'checkinMax' => $_ENV['checkinMax'],

            'jump_delay' => $_ENV['jump_delay'],
            'enable_analytics_code' => $_ENV['enable_analytics_code'],
            'enable_ticket' => $_ENV['enable_ticket'],

            'enable_kill' => $_ENV['enable_kill'],
            'enable_change_email' => $_ENV['enable_change_email'],

            'enable_telegram' => $_ENV['enable_telegram'],
            'telegram_bot' => $_ENV['telegram_bot'],

            'enable_telegram_login' => $_ENV['enable_telegram_login'],

            'subscribeLog' => $_ENV['subscribeLog'],
            'subscribeLog_keep_days' => $_ENV['subscribeLog_keep_days'],

            'enable_auto_detect_ban' => $_ENV['enable_auto_detect_ban'],
            'auto_detect_ban_type' => $_ENV['auto_detect_ban_type'],
            'auto_detect_ban_number' => $_ENV['auto_detect_ban_number'],
            'auto_detect_ban_time' => $_ENV['auto_detect_ban_time'],
            'auto_detect_ban' => $_ENV['auto_detect_ban'],

            'sentry_dsn' => ! isset($_ENV['sentry_dsn']) ? $_ENV['sentry_dsn'] : null,
        ];
    }

    public static function getDbConfig()
    {
        return [
            'driver' => $_ENV['db_driver'],
            'host' => $_ENV['db_host'],
            'unix_socket' => $_ENV['db_socket'],
            'database' => $_ENV['db_database'],
            'username' => $_ENV['db_username'],
            'password' => $_ENV['db_password'],
            'charset' => $_ENV['db_charset'],
            'collation' => $_ENV['db_collation'],
            'prefix' => $_ENV['db_prefix'],
        ];
    }

    public static function getSupportParam($type)
    {
        switch ($type) {
            case 'ss_aead_method':
                return [
                    'aes-128-gcm',
                    'aes-192-gcm',
                    'aes-256-gcm',
                    'chacha20-ietf-poly1305',
                    'xchacha20-ietf-poly1305',
                ];
            case 'ss_obfs':
                return [
                    'simple_obfs_http',
                    'simple_obfs_http_compatible',
                    'simple_obfs_tls',
                    'simple_obfs_tls_compatible',
                ];
            case 'ss_2022':
                return [
                    '2022-blake3-aes-128-gcm',
                    '2022-blake3-aes-256-gcm',
                    '2022-blake3-chacha20-poly1305',
                ];
            default:
                return [
                    'aes-128-gcm',
                    'aes-192-gcm',
                    'aes-256-gcm',
                    'chacha20-ietf-poly1305',
                    'xchacha20-ietf-poly1305',
                    'none',
                    'plain',
                    '2022-blake3-aes-128-gcm',
                    '2022-blake3-aes-256-gcm',
                    '2022-blake3-chacha20-poly1305',
                ];
        }
    }
}
