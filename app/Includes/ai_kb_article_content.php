<?php

if (!function_exists('ai_safe_str')) {
    function ai_safe_str(mixed $val): string
    {
        if (is_string($val)) {
            return $val;
        }
        if (is_numeric($val) || is_bool($val)) {
            return (string) $val;
        }
        if (is_array($val)) {
            $items = [];
            foreach ($val as $v) {
                $str = ai_safe_str($v);
                if ($str !== '') {
                    $items[] = $str;
                }
            }
            return implode("\n", $items);
        }
        return '';
    }
}

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
                return is_string($content) ? $content : json_encode($content); // fallback to raw response if not valid JSON
            }

            // Format JSON into beautiful HTML structure for TinyMCE
            $html = '';
            if (!empty($data['title'])) {
                $html .= "<h1>" . e(ai_safe_str($data['title'])) . "</h1>\n";
            }
            if (!empty($data['summary'])) {
                $html .= "<p><strong>Summary:</strong> " . e(ai_safe_str($data['summary'])) . "</p>\n";
            }
            if (!empty($data['body'])) {
                $html .= (is_array($data['body']) ? implode("\n\n", array_map('ai_safe_str', $data['body'])) : ai_safe_str($data['body'])) . "\n";
            }
            if (!empty($data['steps'])) {
                $html .= "<h2>Steps</h2>\n<ol>\n";
                foreach ((array)$data['steps'] as $step) {
                    $stepStr = is_array($step) ? implode(': ', array_filter(array_map('ai_safe_str', $step))) : ai_safe_str($step);
                    if ($stepStr !== '') {
                        $html .= "    <li>" . e($stepStr) . "</li>\n";
                    }
                }
                $html .= "</ol>\n";
            }
            if (!empty($data['faq'])) {
                $html .= "<h2>FAQ</h2>\n<ul>\n";
                foreach ((array)$data['faq'] as $faqItem) {
                    if (is_array($faqItem)) {
                        $q = ai_safe_str($faqItem['question'] ?? $faqItem['q'] ?? '');
                        $a = ai_safe_str($faqItem['answer'] ?? $faqItem['a'] ?? '');
                        if ($q && $a) {
                            $html .= "    <li><strong>" . e($q) . "</strong><br>" . e($a) . "</li>\n";
                        } else {
                            $itemStr = implode(': ', array_filter(array_map('ai_safe_str', $faqItem)));
                            if ($itemStr !== '') {
                                $html .= "    <li>" . e($itemStr) . "</li>\n";
                            }
                        }
                    } else {
                        $itemStr = ai_safe_str($faqItem);
                        if ($itemStr !== '') {
                            $html .= "    <li>" . e($itemStr) . "</li>\n";
                        }
                    }
                }
                $html .= "</ul>\n";
            }
            if (!empty($data['tags'])) {
                $tagStrings = array_filter(array_map('ai_safe_str', (array)$data['tags']));
                if (!empty($tagStrings)) {
                    $html .= "<p><small>Tags: " . implode(', ', array_map('e', $tagStrings)) . "</small></p>\n";
                }
            }

            return $html ?: (is_string($content) ? $content : '');

        } catch (\Exception $e) {
            return "Error calling OpenAI API: " . $e->getMessage();
        }
    }
}

if (!function_exists('wrap_prose_content')) {
    function wrap_prose_content(string $content): string
    {
        $wrapperPrefix = '<div class="prose prose-slate max-w-none" style="max-width: none !important; width: 100%;">';
        $wrapperSuffix = '</div>';

        $trimmed = trim($content);
        if (empty($trimmed) || str_starts_with($trimmed, 'Error:')) {
            return $content;
        }

        if (str_contains($trimmed, 'prose prose-slate max-w-none')) {
            return $trimmed;
        }

        return $wrapperPrefix . "\n" . $trimmed . "\n" . $wrapperSuffix;
    }
}

if (!function_exists('ai_cms_page_content')) {
    function ai_cms_page_content(string $text, string $prompt = ''): string
    {
        $apiKey = env('OPENAI_API_KEY') ?: config('ai.openai_api_key');
        if (empty($apiKey)) {
            return "Error: OpenAI API Key is missing.";
        }

        if (app()->environment('testing') || $apiKey === 'test_openai_key') {
            return wrap_prose_content("<h1>AI Generated Page Content</h1>\n<p>Generated based on prompt: '" . e($prompt) . "'</p>\n<p>" . e($text) . "</p>");
        }

        try {
            $client = \OpenAI::client($apiKey);

            $response = $client->chat()->create([
                'model' => 'gpt-5-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are an expert web CMS content writer and copywriter creating clean, modern HTML content for website pages."
                    ],
                    [
                        'role' => 'user',
                        'content' => "Create or rewrite website CMS page content using this instruction prompt:\n{$prompt}\n\nExisting Content Context:\n{$text}\n\nReturn well-formatted HTML with headings, paragraphs, lists, or callouts as appropriate."
                    ],
                ],
            ]);

            $raw = $response->choices[0]->message->content ?? '';
            return wrap_prose_content($raw);
        } catch (\Exception $e) {
            return "Error calling OpenAI API: " . $e->getMessage();
        }
    }
}

if (!function_exists('ai_product_description_content')) {
    function ai_product_description_content(string $context, string $prompt = ''): string
    {
        $apiKey = env('OPENAI_API_KEY') ?: config('ai.openai_api_key');
        if (empty($apiKey)) {
            return "Error: OpenAI API Key is missing.";
        }

        if (app()->environment('testing') || $apiKey === 'test_openai_key') {
            return wrap_prose_content("<h2>Product Description</h2>\n<p>AI generated product description based on prompt: '" . e($prompt) . "'</p>\n<p>" . e($context) . "</p>");
        }

        try {
            $client = \OpenAI::client($apiKey);

            $response = $client->chat()->create([
                'model' => 'gpt-5-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are an expert e-commerce product copywriter creating high-converting, persuasive, and professional product descriptions in clean HTML."
                    ],
                    [
                        'role' => 'user',
                        'content' => "Generate a detailed product description using this instruction prompt:\n{$prompt}\n\nProduct Information & Context:\n{$context}\n\nReturn clean HTML suitable for an e-commerce product long description."
                    ],
                ],
            ]);

            $raw = $response->choices[0]->message->content ?? '';
            return wrap_prose_content($raw);
        } catch (\Exception $e) {
            return "Error calling OpenAI API: " . $e->getMessage();
        }
    }
}
