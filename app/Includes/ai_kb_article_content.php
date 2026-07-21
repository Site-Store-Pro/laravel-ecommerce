<?php

if (!function_exists('ai_kb_article_content')) {
    function ai_kb_article_content(string $text, string $prompt = ''): string
    {
        $apiKey = env('OPENAI_API_KEY') ?: config('ai.openai_api_key');
        if (empty($apiKey)) {
            return "Error: OpenAI API Key is missing.";
        }

        // Return mock data for local testing / automated tests
        if (app()->environment('testing') || $apiKey === 'test_openai_key') {
            return "This is placeholder AI content for the KB article using prompt: '{$prompt}' based on:\n\n" . $text;
        }

        try {
            $client = \OpenAI::client($apiKey);
            
            $response = $client->chat()->create([
                'model' => 'gpt-5-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are a technical writer creating customer-facing knowledge base articles. Return valid JSON only."
                    ],
                    [
                        'role' => 'user',
                        'content' => "
Create/rewrite a KB article using this prompt:
{$prompt}

Based on this content:
{$text}

Return:
{
  \"title\": \"\",
  \"summary\": \"\",
  \"body\": \"\",
  \"steps\": [],
  \"faq\": [],
  \"tags\": []
}
"
                    ],
                ],
            ]);

            $content = $response->choices[0]->message->content;
            $data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
                return $content; // fallback to raw response if not valid JSON
            }

            // Format JSON into beautiful HTML structure for TinyMCE
            $html = '';
            if (!empty($data['title'])) {
                $html .= "<h1>" . e($data['title']) . "</h1>\n";
            }
            if (!empty($data['summary'])) {
                $html .= "<p><strong>Summary:</strong> " . e($data['summary']) . "</p>\n";
            }
            if (!empty($data['body'])) {
                $html .= $data['body'] . "\n";
            }
            if (!empty($data['steps'])) {
                $html .= "<h2>Steps</h2>\n<ol>\n";
                foreach ($data['steps'] as $step) {
                    $html .= "    <li>" . e($step) . "</li>\n";
                }
                $html .= "</ol>\n";
            }
            if (!empty($data['faq'])) {
                $html .= "<h2>FAQ</h2>\n<ul>\n";
                foreach ($data['faq'] as $faqItem) {
                    if (is_array($faqItem)) {
                        $q = $faqItem['question'] ?? $faqItem['q'] ?? '';
                        $a = $faqItem['answer'] ?? $faqItem['a'] ?? '';
                        if ($q && $a) {
                            $html .= "    <li><strong>" . e($q) . "</strong><br>" . e($a) . "</li>\n";
                        }
                    } else {
                        $html .= "    <li>" . e($faqItem) . "</li>\n";
                    }
                }
                $html .= "</ul>\n";
            }
            if (!empty($data['tags'])) {
                $html .= "<p><small>Tags: " . implode(', ', array_map('e', $data['tags'])) . "</small></p>\n";
            }

            return $html ?: $content;

        } catch (\Exception $e) {
            return "Error calling OpenAI API: " . $e->getMessage();
        }
    }
}
