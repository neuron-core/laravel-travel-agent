<?php

namespace App\Neuron\Nodes;

use App\Neuron\Agents\ResearchAgent;
use App\Neuron\Events\Retrieve;
use App\Neuron\Events\RetrieveFlights;
use App\Neuron\Tools\SerpAPI\SerpAPIFlight;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Exceptions\AgentException;
use NeuronAI\Workflow\Node;
use NeuronAI\Workflow\WorkflowState;

class Flights extends Node
{
    /**
     * @throws AgentException
     */
    public function __invoke(RetrieveFlights $event, WorkflowState $state): Retrieve
    {
        $response = ResearchAgent::make()
            ->addTool(
                SerpAPIFlight::make($_ENV['SERPAPI_KEY'])
            )
            ->chat(
                new UserMessage(
                    "Find the best flights between DEPARTURE_AIRPORT: " . $event->tour->airport_from . " and ARRIVAL_AIRPORT:" . $event->tour->airport_to .
                    " on START_DATE: " . $event->tour->departure_date . " and RETURN_DATE: " . $event->tour->return_date .
                    "Select the flights with the lowest price, best departure time, and best return time."
                )
            );


        $state->set('flights', $response->getContent());

        return new Retrieve($event->tour);
    }
}
