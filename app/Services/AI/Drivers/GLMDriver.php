<?php

namespace App\Services\AI\Drivers;

use App\AIProvider;

class GLMDriver extends OpenAIDriver
{
    public function __construct(AIProvider $provider)
    {
        parent::__construct($provider);
        $this->model = $provider->model ?: 'glm-4-flash';
        $this->baseUrl = rtrim($provider->base_url ?: 'https://open.bigmodel.cn/api/paas/v4', '/');
    }
}
