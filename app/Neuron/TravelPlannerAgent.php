<?php

declare(strict_types=1);

namespace App\Neuron;

use App\Models\User;
use App\Neuron\Nodes\RetrieveDelegator;
use App\Neuron\Nodes\Receptionist;
use App\Neuron\Nodes\Flights;
use App\Neuron\Nodes\GenerateItinerary;
use App\Neuron\Nodes\Hotels;
use App\Neuron\Nodes\Places;
use NeuronAI\Chat\History\ChatHistoryInterface;
use NeuronAI\Chat\History\FileChatHistory;
use NeuronAI\Exceptions\ChatHistoryException;
use NeuronAI\Exceptions\WorkflowException;
use NeuronAI\Workflow\Persistence\FilePersistence;
use NeuronAI\Workflow\Workflow;
use NeuronAI\Workflow\WorkflowState;

class TravelPlannerAgent extends Workflow
{
    protected ChatHistoryInterface $history;

    /**
     * @throws WorkflowException
     * @throws ChatHistoryException
     */
    public function __construct(protected string $input, User $user){
        parent::__construct(
            state: new WorkflowState(['query' => $this->input]),
            persistence: new FilePersistence(storage_path('ai'), $user->id.'_'),
            workflowId: "planner_{$user->id}"
        );

        $this->history = new FileChatHistory(storage_path('ai'), 'planner_chat_history');
    }

    protected function nodes(): array
    {
        return [
            new Receptionist($this->history),
            new RetrieveDelegator(),
            new Flights(),
            new Hotels(),
            new Places(),
            new GenerateItinerary($this->history)
        ];
    }
}
