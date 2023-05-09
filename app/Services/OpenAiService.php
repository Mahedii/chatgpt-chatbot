<?php

namespace App\Services;

use Log;
use GuzzleHttp\Client;

class OpenAiService
{
    protected $client;
    protected $headers;
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
            ],
        ]);

        $this->headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ];
    }

    public function sendMessage($message)
    {
        $response = $this->client->post('engines/davinci/completions', [
            'json' => [
                'prompt' => $message,
                'temperature' => 0.5,
                'max_tokens' => 110,
                'top_p' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);
        // dd($responseData);
        $message = $responseData['choices'][0]['text'];

        return $message;
    }

    public function handleMessage($message)
    {
        // Set up the API endpoint URL and authentication headers
        $url = 'https://api.openai.com/v1/engines/text-curie-001/completions';

        // Set up the request data
        $data = [
            'prompt' => $message, // 'what is the stable version of opeai package?',
            'max_tokens' => 50,
            'temperature' => 0.7, // Adjust temperature for randomness
            'n' => 1, // Number of completions to generate
            'stop' => ['\n'], // Stop generating completions when encountering a newline character
            'echo' => false,
            'top_p' => 0.9, // Top p probability sampling
            'presence_penalty' => 0.5, // Frequency penalty for avoiding repetitive responses
            'frequency_penalty' => 0.5, // Presence penalty for encouraging diverse responses
            'logprobs' => null, // Include log probabilities in the response
            'best_of' => 1, // Return the best completion out of a specified number of attempts
            // 'logit_bias' => null, // ['positive' => 2, 'negative' => -2] // Bias the model's response using logit values
            // Error:Unrecognized request argument
            // 'log_level' => 'info', // Log level for API logs (choices: 'debug', 'info', 'warning', 'error', 'critical')
            // 'user' => null, // Set a string to identify the user or the context of the conversation
            // 'model' => 'davinci-codex', // Specify the model name explicitly
            // 'temperature_decay_rate' => null, // Temperature decay rate for temperature sampling
            // 'presence_penalty_decay_rate' => null, // Presence penalty decay rate for repetitive responses
            // 'frequency_penalty_decay_rate' => null, // Frequency penalty decay rate for diverse responses
            // 'use-case' => 'chat', // generic, // Specify the use-case of the chat (choices: 'chat', 'generic')
            // 'beta' => true, // Enable beta features
            // 'time_limit' => 1, // Time limit for the API call
            // 'max_completions' => null, // Maximum number of completions to generate
            // 'expand' => null, // true // Expand shorthand prompts
            // 'debug' => null, // Enable debug mode for detailed API response
            // 'return_prompt' => null, // Include prompt in the API response
            // 'return_metadata' => null, // Include metadata in the API response
            // 'return_attention' => null, // Include attention weights in the API response
            // 'return_tokens' => null, // Include token-level details in the API response
            // 'context' => null, // Array of message objects to provide a conversation context
            // 'temperature_min' => null, // Minimum temperature for temperature sampling
            // 'temperature_max' => null, // Maximum temperature for temperature sampling
            // 'presence_penalty_min' => null, // Minimum presence penalty for avoiding repetitive responses
            // 'presence_penalty_max' => null, // Maximum presence penalty for avoiding repetitive responses
            // 'frequency_penalty_min' => null, // Minimum frequency penalty for encouraging diverse responses
            // 'frequency_penalty_max' => null, // Maximum frequency penalty for encouraging diverse responses
        ];
        // $data['context'] = [
        //     ['role' => 'system', 'content' => 'You are a helpful assistant.'],
        //     ['role' => 'user', 'content' => 'What is the weather today?'],
        // ];

        // Send the request to the GPT-3 API
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_CAINFO, 'D:\xampp\php\extras\ssl\cacert.pem');

        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

         // Log any cURL errors
        if ($curl_error) {
            \Log::error('cURL error: ' . $curl_error);
            return response()->json(['message' => 'Error: cURL error.']);
        }

        // Process the response
        if (!$response) {
            // handle error
        }

        $result = json_decode($response, true);

        // Log any JSON decoding errors
        if (!$result) {
            Log::error('JSON decoding error: ' . json_last_error_msg());
            Log::error('Response from API: ' . $response);
            return response()->json(['message' => 'Error: could not decode API response.']);
        }

        if (!$result) {
            // handle error
        }
        // dd($result);
        $generated_text = $result['choices'][0]['text'];

        // Output the generated text
        // echo $generated_text;

        return $generated_text;
        return response()->json(['message' => $generated_text]);
    }
}
