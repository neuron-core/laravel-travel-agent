<?php

namespace App\Neuron\Events;

use App\Neuron\Agents\TourInfo;
use NeuronAI\Workflow\Event;

class RetrievePlaces implements Event
{
    public function __construct(public TourInfo $tour)
    {
    }
}
