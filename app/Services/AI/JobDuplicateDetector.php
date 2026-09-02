<?php

namespace App\Services\AI;

use App\RawJob;
use App\Job;

class JobDuplicateDetector
{
    /**
     * Compute a normalized SHA-256 fingerprint hash for a job posting
     *
     * @param string $company
     * @param string $title
     * @param string $location
     * @return string 64-character SHA-256 hex string
     */
    public static function generateHash(string $company, string $title, string $location = ''): string
    {
        $normCompany = self::normalizeText($company);
        $normTitle = self::normalizeText($title);
        $normLocation = self::normalizeText($location);

        $combined = "{$normCompany}|{$normTitle}|{$normLocation}";
        return hash('sha256', $combined);
    }

    /**
     * Check if a job fingerprint is a duplicate
     *
     * @param string $contentHash
     * @return bool
     */
    public static function isDuplicate(string $contentHash): bool
    {
        // 1. Check in raw jobs table
        if (RawJob::where('content_hash', $contentHash)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Normalize string for reliable fingerprinting
     */
    public static function normalizeText(string $text): string
    {
        $text = mb_strtolower(trim($text), 'UTF-8');
        // Remove punctuation and special characters
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
        // Replace multiple whitespace with single space
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }
}
