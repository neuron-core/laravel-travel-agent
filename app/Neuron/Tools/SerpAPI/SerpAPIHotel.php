<?php

declare(strict_types=1);

namespace App\Neuron\Tools\SerpAPI;

use GuzzleHttp\Client;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\Tool;
use NeuronAI\Tools\ToolProperty;

/**
 * @method static static make(string $key, string $currency = 'USD')
 */
class SerpAPIHotel extends Tool
{
    protected Client $client;

    public function __construct(
        protected string $key,
        protected string $currency = 'USD',
    ){
        // Define Tool name and description
        parent::__construct(
            'find_hotels',
            'Find hotels in a specific city.',
        );
    }

    /**
     * Return the list of properties.
     */
    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'city',
                type: PropertyType::STRING,
                description: 'The city where the hotels are located',
                required: true,
            ),
            new ToolProperty(
                name: 'check_in_date',
                type: PropertyType::STRING,
                description: 'The check-in date in the format YYYY-MM-DD',
                required: true,
            ),
            new ToolProperty(
                name: 'check_out_date',
                type: PropertyType::STRING,
                description: 'The check-out date in the format YYYY-MM-DD',
                required: true,
            ),
        ];
    }

    /**
     * Implementing the tool logic
     */
    public function __invoke(
        string $city,
        string $check_in_date,
        string $check_out_date,
    ): array {
        $result = $this->getClient()->get('search', [
            'query' => [
                "engine" => "google_hotels",
                "q" => $city,
                "hl" => "en",
                "gl" => "us",
                "check_in_date" => $check_in_date,
                "check_out_date" => $check_out_date,
                "currency" => $this->currency,
                "api_key" => $this->key,
            ]
        ])->getBody()->getContents();

        return \json_decode($result, true);
    }

    protected function getClient(): Client
    {
        return $this->client ?? $this->client = new Client([
            'base_uri' => \trim('https://serpapi.com/', '/').'/',
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);
    }
}
