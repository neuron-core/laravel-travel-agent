<?php

namespace App\Neuron\Tools\SerpAPI;

use NeuronAI\Tools\Toolkits\AbstractToolkit;

/**
 * @method static static make(string $key)
 */
class SerpAPIToolkit extends AbstractToolkit
{
    public function __construct(protected string $key)
    {
    }

    public function provide(): array
    {
        return [
            SerpAPIFlight::make($this->key),
            SerpAPIHotel::make($this->key),
            SerpAPIPlace::make($this->key),
        ];
    }
}
