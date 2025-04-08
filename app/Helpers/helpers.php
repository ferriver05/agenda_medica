<?php

if (!function_exists('initials')) {
    function initials(string $name): string {
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty(trim($word))) {
                $initials .= strtoupper(substr(trim($word), 0, 1));
            }
        }
        
        return substr($initials, 0, 2);
    }
}