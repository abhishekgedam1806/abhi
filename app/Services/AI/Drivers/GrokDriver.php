<?php

namespace App\Services\AI\Drivers;

use App\AIProvider;

class GrokDriver extends OpenAIDriver
{
    public function __construct(AIProvider $provider)
    {
        parent::__construct($provider);
        $this->model = $provider->model ?: 'grok-beta';
        $this->baseUrl = rtrim($provider->base_url ?: 'https://api.x.ai/v1', '/');
    }
}
