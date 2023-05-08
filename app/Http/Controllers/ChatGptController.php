<?php

namespace App\Http\Controllers;

use OpenAI\Client;
// use OpenAI\Models\Gpt3\Gpt3;
// use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\Request;
use Log;

class ChatGptController extends Controller {

    public function handleMessages(Request $request)
    {
        $message = $request->input('message');
        $message = "OPENAI";

        $client = new Client(config('openai.api_key'));

        $prompt = $message . "\nBot:";
        $response = $client->complete([
            'model' => 'gpt-3.5-turbo',
            'prompt' => $prompt,
            'max_tokens' => 150,
            'temperature' => 0.7,
            'n' => 1,
            'stop' => "\n"
        ]);
        dd($response);
        $text = $response['choices'][0]['text'];
        return response()->json(['message' => $text]);
    }

    public function handleMessage (Request $request) {

        // $message = $request->input('message');
        // $transporter = new GuzzleTransporter(config('openai.api_key'));
        // $client = OpenAI::client(env('OPENAI_API_KEY'));
        // $response = $client->completions()->create([
        //     'engine' => 'davinci',
        //     'prompt' => $message,
        //     'temperature' => 0.5,
        //     'max_tokens' => 50,
        //     'top_p' => 1,
        //     'frequency_penalty' => 0,
        //     'presence_penalty' => 0,
        //     'n' => 1,
        //     'stop' => "\n"
        // ]);
        // $text = $response['choices'][0]['text'];
        // return response()->json(['message' => $text]);

        // Set up the API endpoint URL and authentication headers
        $url = 'https://api.openai.com/v1/engines/text-curie-001/completions';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer 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'
        ];

        // Set up the request data
        $data = [
            'prompt' => 'what is the stable version of opeai package',
            'max_tokens' => 50,
            'temperature' => 0.7, // Adjust temperature for randomness
            'n' => 5, // Number of completions to generate
            'stop' => ['\n'], // Stop generating completions when encountering a newline character
            'echo' => false,
            'top_p' => 0.9, // Top p probability sampling
            'presence_penalty' => 0.5, // Frequency penalty for avoiding repetitive responses
            'frequency_penalty' => 0.5, // Presence penalty for encouraging diverse responses
            'log_level' => 'info', // Log level for API logs (choices: 'debug', 'info', 'warning', 'error', 'critical')
            'logprobs' => null, // Include log probabilities in the response
            'logit_bias' => null, // ['positive' => 2, 'negative' => -2] // Bias the model's response using logit values
            'best_of' => null, // Return the best completion out of a specified number of attempts
            'user' => null, // Set a string to identify the user or the context of the conversation
            'model' => 'davinci-codex', // Specify the model name explicitly
            'temperature_decay_rate' => null, // Temperature decay rate for temperature sampling
            'presence_penalty_decay_rate' => null, // Presence penalty decay rate for repetitive responses
            'frequency_penalty_decay_rate' => null, // Frequency penalty decay rate for diverse responses
            'use-case' => 'chat', // generic // Specify the use-case of the chat (choices: 'chat', 'generic')
            'beta' => true, // Enable beta features
            'time_limit' => null, // Time limit for the API call
            'max_completions' => null, // Maximum number of completions to generate
            'expand' => null, // true // Expand shorthand prompts
            'debug' => null, // Enable debug mode for detailed API response
            'return_prompt' => null, // Include prompt in the API response
            'return_metadata' => null, // Include metadata in the API response
            'return_attention' => null, // Include attention weights in the API response
            'return_tokens' => null, // Include token-level details in the API response
            'context' => null, // Array of message objects to provide a conversation context
            'temperature_min' => null, // Minimum temperature for temperature sampling
            'temperature_max' => null, // Maximum temperature for temperature sampling
            'presence_penalty_min' => null, // Minimum presence penalty for avoiding repetitive responses
            'presence_penalty_max' => null, // Maximum presence penalty for avoiding repetitive responses
            'frequency_penalty_min' => null, // Minimum frequency penalty for encouraging diverse responses
            'frequency_penalty_max' => null, // Maximum frequency penalty for encouraging diverse responses
            'best_of' => 1, // Set the number of completions to consider when using the 'best_of' option
        ];
        // $data['context'] = [
        //     ['role' => 'system', 'content' => 'You are a helpful assistant.'],
        //     ['role' => 'user', 'content' => 'What is the weather today?'],
        // ];

        // Send the request to the GPT-3 API
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_CAINFO, 'C:\xampp\php\extras\ssl\cacert.pem');

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
            \Log::error('JSON decoding error: ' . json_last_error_msg());
            \Log::error('Response from API: ' . $response);
            return response()->json(['message' => 'Error: could not decode API response.']);
        }

        if (!$result) {
            // handle error
        }
        dd($response);
        $generated_text = $result['choices'][0]['text'];

        // Output the generated text
        echo $generated_text;

        return response()->json(['message' => $generated_text]);

    }
}
