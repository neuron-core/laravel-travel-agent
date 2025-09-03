<?php

namespace App\Neuron\Agents;

use NeuronAI\StructuredOutput\SchemaProperty;
use NeuronAI\StructuredOutput\Validation\Rules\Length;

class TourInfo
{
    #[SchemaProperty(
        description: 'The valid 3-letter IATA airport code for the departure airport e.g. LHR, LAX etc.',
        required: true
    )]
    #[Length(exactly: 3)]
    public string $airport_from;

    #[SchemaProperty(
        description: 'The valid 3-letter IATA airport code for the destination airport e.g. LHR, LAX etc.',
        required: true
    )]
    #[Length(exactly: 3)]
    public string $airport_to;

    #[SchemaProperty(
        description: 'The departure date in the format YYYY-MM-DD. Must be explicitly defined by the user.',
        required: true
    )]
    #[Length(exactly: 10)]
    public string $departure_date;

    #[SchemaProperty(
        description: 'The return date in the format YYYY-MM-DD. If the user does not want to return, set as one week after departure date.',
        required: true
    )]
    #[Length(exactly: 10)]
    public string $return_date;

    #[SchemaProperty(
        description: 'The city the user wants to visit.',
        required: true
    )]
    public string $city;

    public function isComplete(): bool
    {
        return isset($this->airport_from) && isset($this->airport_to) && isset($this->departure_date) && isset($this->return_date) && isset($this->city);
    }
}
