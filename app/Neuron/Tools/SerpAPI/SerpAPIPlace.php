<?php

declare(strict_types=1);

namespace App\Neuron\Tools\SerpAPI;

use GuzzleHttp\Client;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\Tool;
use NeuronAI\Tools\ToolProperty;

/**
 * @method static static make(string $key)
 */
class SerpAPIPlace extends Tool
{
    protected Client $client;

    public function __construct(protected string $key)
    {
        // Define Tool name and description
        parent::__construct(
            'find_places_to_visit',
            'Find places to visit in a specific city.',
        );
    }

    /**
     * Return the list of properties.
     */
    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'location',
                type: PropertyType::STRING,
                description: 'The location to find places to visit.',
                required: true,
            ),
        ];
    }

    /**
     * Implementing the tool logic
     */
    public function __invoke(string $location): array
    {
        $locations = $this->getClient()->get('locations.json?q='.$location)->getBody()->getContents();
        $locations = \json_decode($locations, true)[0] ?? [];

        $result = $this->getClient()->get('search', [
            'query' => [
                "engine" => "google",
                "google_domain" => "google.com",
                "location" => $locations === [] ? 'Austin, Texas, United States' : $locations['canonical_name'],
                "q" => "top sights to visit for a trip in {$location}",
                "hl" => "en",
                "gl" => "us",
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
