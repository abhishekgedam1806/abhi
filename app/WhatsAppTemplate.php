<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WhatsAppTemplate extends Model
{
    protected $table = 'whatsapp_templates';

    protected $fillable = [
        'template_key',
        'title',
        'category',
        'provider_template_name',
        'language',
        'header_text',
        'body_text',
        'footer_text',
        'buttons_json',
        'variables_schema',
        'is_active',
    ];

    protected $casts = [
        'buttons_json' => 'array',
        'variables_schema' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Render the template body with provided variable data
     */
    public function renderBody(array $variables = []): string
    {
        $text = $this->body_text;
        foreach ($variables as $key => $val) {
            $text = str_replace('{{' . $key . '}}', (string)$val, $text);
        }
        return $text;
    }

    /**
     * Render the full composite message (Header + Body + Footer)
     */
    public function renderFullMessage(array $variables = []): string
    {
        $parts = [];

        if (!empty($this->header_text)) {
            $header = $this->header_text;
            foreach ($variables as $key => $val) {
                $header = str_replace('{{' . $key . '}}', (string)$val, $header);
            }
            $parts[] = "📌 *" . trim($header) . "*\n";
        }

        $parts[] = $this->renderBody($variables);

        if (!empty($this->footer_text)) {
            $parts[] = "\n_" . trim($this->footer_text) . "_";
        }

        return implode("\n", $parts);
    }
}
