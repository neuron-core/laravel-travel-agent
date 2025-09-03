<?php

namespace App\Neuron\Events;

use App\Neuron\Agents\TourInfo;
use NeuronAI\Workflow\Event;

class CreateItinerary implements Event
{
    public function __construct(public TourInfo $tour)
    {
    }
}
