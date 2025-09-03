<?php

declare(strict_types=1);

namespace App\Neuron;

use App\Neuron\Nodes\Delegator;
use App\Neuron\Nodes\Receptionist;
use App\Neuron\Nodes\Flights;
use App\Neuron\Nodes\GenerateItinerary;
use App\Neuron\Nodes\Hotels;
use App\Neuron\Nodes\Places;
use NeuronAI\Chat\History\ChatHistoryInterface;
use NeuronAI\Chat\History\FileChatHistory;
use NeuronAI\Exceptions\ChatHistoryException;
use NeuronAI\Exceptions\WorkflowException;
use NeuronAI\Workflow\Persistence\PersistenceInterface;
use NeuronAI\Workflow\Workflow;
use NeuronAI\Workflow\WorkflowState;

class TravelPlannerAgent extends Workflow
{
    /**
     * @throws WorkflowException
     */
    public function __construct(
        protected ChatHistoryInterface $history,
        ?WorkflowState $state = null,
        ?PersistenceInterface $persistence = null,
        ?string $workflowId = null
    ){
        parent::__construct($state, $persistence, $workflowId);
    }

    protected function nodes(): array
    {
        return [
            new Receptionist($this->history),
            new Delegator(),
            new Flights(),
            new Hotels(),
            new Places(),
            new GenerateItinerary($this->history)
        ];
    }
}
