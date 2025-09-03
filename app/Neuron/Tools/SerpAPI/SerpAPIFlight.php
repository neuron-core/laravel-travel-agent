<?php

declare(strict_types=1);

namespace App\Neuron\Tools\SerpAPI;

use GuzzleHttp\Client;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\Tool;
use NeuronAI\Tools\ToolProperty;

/**
 * @method static static make(string $key, string $currency = 'USD', int $stops = 2)
 */
class SerpAPIFlight extends Tool
{
    protected Client $client;

    public function __construct(
        protected string $key,
        protected string $currency = 'USD',
        protected int $stops = 2,
    ){
        // Define Tool name and description
        parent::__construct(
            'find_flights',
            'Find flights between two airports on given dates.',
        );
    }

    /**
     * Return the list of properties.
     */
    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'departure_airport',
                type: PropertyType::STRING,
                description: 'The 3 letter departure airport code (IATA) e.g. LHR',
                required: true,
            ),
            new ToolProperty(
                name: 'arrival_airport',
                type: PropertyType::STRING,
                description: 'The 3 letter arrival airport code (IATA) e.g. JFK',
                required: true,
            ),
            new ToolProperty(
                name: 'departure_date',
                type: PropertyType::STRING,
                description: 'The departure date in the format YYYY-MM-DD',
                required: true,
            ),
            new ToolProperty(
                name: 'return_date',
                type: PropertyType::STRING,
                description: 'The return date in the format YYYY-MM-DD',
                required: false,
            ),
        ];
    }

    /**
     * Implementing the tool logic
     */
    public function __invoke(
        string $departure_airport,
        string $arrival_airport,
        string $departure_date,
        string $return_date = null,
    ): array {
        $result = $this->getClient()->get('search', [
            'query' => [
                "engine" => "google_flights",
                "hl" => "en",
                "departure_id" => $departure_airport,
                "arrival_id" => $arrival_airport,
                "outbound_date" => $departure_date,
                "return_date" => $return_date,
                "stops" => $this->stops,  # 1 stop of less
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
