<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key and Organization
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API Key and organization. This will be
    | used to authenticate with the OpenAI API - you can find your API key
    | and organization on your OpenAI dashboard, at https://openai.com.
    */

    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),
    'transporter' => OpenAI\Http\GuzzleTransporter::class,
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Endpoint
    |--------------------------------------------------------------------------
    |
    | This value is the API endpoint for OpenAI. You can change it if needed
    | but it is not recommended.
    |
    */

    'endpoint' => 'https://api.openai.com',

    /*
    |--------------------------------------------------------------------------
    | Default Model ID
    |--------------------------------------------------------------------------
    |
    | This value is the default Model ID to use for the OpenAI API. You can
    | specify a different Model ID when calling the API.
    |
    */

    'default_model' => 'davinci',


];
