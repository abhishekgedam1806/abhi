<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AICache extends Model
{
    protected $table = 'ai_cache';

    protected $fillable = [
        'input_hash',
        'feature',
        'prompt_version',
        'provider',
        'model',
        'response_text',
        'response_json',
        'input_tokens',
        'output_tokens',
        'hit_count',
        'last_accessed_at',
    ];

    protected $casts = [
        'input_tokens' => 'integer',
        'output_tokens' => 'integer',
        'hit_count' => 'integer',
        'last_accessed_at' => 'datetime',
    ];

    /**
     * Compute a deterministic input hash for caching
     */
    public static function generateHash(string $feature, string $normalizedInput, string $promptVersion, string $model): string
    {
        $raw = "{$feature}|" . trim($normalizedInput) . "|{$promptVersion}|{$model}";
        return hash('sha256', $raw);
    }
}
