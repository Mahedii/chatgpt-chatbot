<?php

namespace App\Http\Controllers;

use OpenAI\Client;
use Illuminate\Http\Request;
use App\Services\OpenAiService;

class ChatGptController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = $request->input('message');

        $openai = new OpenAiService(config('services.openai.key'));

        $response = $openai->sendMessage($message);

        return response()->json(['message' => $response]);
    }

    public function handleMessage(Request $request)
    {
        $message = $request->input('message');

        $openai = new OpenAiService(config('services.openai.key'));

        $response = $openai->handleMessage($message);

        return response()->json(['message' => $response]);
    }
}
