<?php

namespace App\Services;

class PhoneHelper
{
    /**
     * Normalize a phone or whatsapp number for duplicate detection and consistent storage.
     * E.g.: '+880 1712-345678' -> '01712345678'
     * E.g.: '8801712345678'     -> '01712345678'
     * E.g.: '01712345678'       -> '01712345678'
     */
    public static function normalize(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Remove all non-numeric characters except leading '+' temporarily
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if (empty($cleaned)) {
            return null;
        }

        // Bangladeshi number normalization:
        // 8801XXXXXXXXX (13 digits) -> 01XXXXXXXXX (11 digits)
        if (str_starts_with($cleaned, '8801') && strlen($cleaned) === 13) {
            return substr($cleaned, 2);
        }

        // 1XXXXXXXXX (10 digits starting with 1) -> 01XXXXXXXXX (11 digits)
        if (str_starts_with($cleaned, '1') && strlen($cleaned) === 10) {
            return '0' . $cleaned;
        }

        return $cleaned;
    }

    /**
     * Format phone for user-friendly display: e.g. +880 1712-345678
     */
    public static function formatDisplay(?string $phone): string
    {
        $normalized = self::normalize($phone);
        if (!$normalized) {
            return $phone ?? '';
        }

        if (strlen($normalized) === 11 && str_starts_with($normalized, '01')) {
            // E.g. 01712-345678
            return substr($normalized, 0, 5) . '-' . substr($normalized, 5);
        }

        return $phone ?? '';
    }
}
