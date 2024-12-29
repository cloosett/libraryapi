<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    public function openai($text)
    {
        $apiKey = config('openai.api_key');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $apiKey",
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama3-8b-8192',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Ви експерт з написання професійних резюме. Ваше завдання — покращити текст резюме та зробити його професійним і відповідним вимогам працедавців.',
                ],
                [
                    'role' => 'user',
                    'content' => 'Будь ласка, покращте наступний текст резюме, зробіть його професійним та відполірованим, і верни його на Українській мові: ' . $text,
                ],
            ],
        ]);
        if ($response->successful()) {
            $data = $response->json();
            $response = $data['choices'][0]['message']['content'];
            return $response;
        }

        return response()->json(['error' => 'Failed to get response from OpenAI']);

    }

    public function roman($text)
    {
        $apiKey = config('openai.api_key');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $apiKey",
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama3-8b-8192',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Ти експерт по книжках, я тобі дам назву книжку, верни по ній короткий опис тільки на українській мові',
                ],
                [
                    'role' => 'user',
                    'content' => 'Будь ласка, верни по ній короткий опис книжки на українській мові: ' . $text,
                ],
            ],
        ]);
        if ($response->successful()) {
            $data = $response->json();
            $response = $data['choices'][0]['message']['content'];
            return $response;
        }

        return response()->json(['error' => 'Failed to get response from OpenAI']);

    }
}
