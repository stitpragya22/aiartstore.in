<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 */

if (! function_exists('get_custom_setting')) {
    function get_custom_setting(string $key, string $default = ''): string
    {
        try {
            $db = db_connect();
            $row = $db->table('settings')
                ->where('class', 'App\Views\Layouts')
                ->where('key', $key)
                ->get()
                ->getRowArray();
            return $row['value'] ?? $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}
