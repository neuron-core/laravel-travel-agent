<?php

namespace App\Neuron\Nodes;

use App\Neuron\Events\CreateItinerary;
use App\Neuron\Events\ProgressEvent;
use App\Neuron\Events\Retrieve;
use App\Neuron\Events\RetrieveFlights;
use App\Neuron\Events\RetrieveHotels;
use App\Neuron\Events\RetrievePlaces;
use NeuronAI\Workflow\Node;
use NeuronAI\Workflow\WorkflowState;

class Delegator extends Node
{
    public function __invoke(
        Retrieve $event,
        WorkflowState $state
    ): \Generator|RetrieveHotels|RetrievePlaces|RetrieveFlights|CreateItinerary {

        if (!$state->has('flights')) {
            yield new ProgressEvent("\n- Retrieving flights information...");
            return new RetrieveFlights($event->tour);
        }

        if (!$state->has('hotels')) {
            yield new ProgressEvent("\n- Retrieving hotels information...");
            return new RetrieveHotels($event->tour);
        }

        if (!$state->has('places')) {
            yield new ProgressEvent("\n- Looking for interesting places and activities...");
            return new RetrievePlaces($event->tour);
        }

        return new CreateItinerary($event->tour);
    }
}
