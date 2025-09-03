<?php

namespace App\Neuron\Nodes;

use App\Neuron\Agents\ResearchAgent;
use App\Neuron\Events\Retrieve;
use App\Neuron\Events\RetrievePlaces;
use App\Neuron\Tools\SerpAPI\SerpAPIPlace;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Workflow\Node;
use NeuronAI\Workflow\WorkflowState;

class Places extends Node
{
    public function __invoke(RetrievePlaces $event, WorkflowState $state): Retrieve
    {
        $response = ResearchAgent::make()
            ->addTool(
                SerpAPIPlace::make($_ENV['SERPAPI_KEY'])
            )
            ->chat(
                new UserMessage(
                    "Find the best points of interest and places to visit in the area of CITY: {$event->tour->city}"
                )
            );

        $state->set('places', $response->getContent());

        return new Retrieve($event->tour);
    }
}
