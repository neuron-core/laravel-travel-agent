<?php

declare(strict_types=1);

namespace App\Neuron\Nodes;

use App\Neuron\Agents\ExtractedInfo;
use App\Neuron\Agents\ResearchAgent;
use App\Neuron\Events\Retrieve;
use App\Neuron\Prompts;
use NeuronAI\Chat\History\ChatHistoryInterface;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Exceptions\AgentException;
use NeuronAI\Exceptions\WorkflowException;
use NeuronAI\Workflow\Node;
use NeuronAI\Workflow\StartEvent;
use NeuronAI\Workflow\WorkflowInterrupt;
use NeuronAI\Workflow\WorkflowState;

class Receptionist extends Node
{
    public function __construct(protected ChatHistoryInterface $history)
    {
    }

    /**
     * This node is responsible for collecting all the information needed to create the itinerary.
     *
     * @throws \Throwable
     * @throws WorkflowInterrupt
     * @throws AgentException
     * @throws \ReflectionException
     * @throws WorkflowException
     */
    public function __invoke(StartEvent $event, WorkflowState $state): Retrieve
    {
        $query = $this->consumeInterruptFeedback();

        if ($query === null) {
            $query = \str_replace('{query}', $state->get('query'), Prompts::TOUR_PLANNER);
            $query = \str_replace('{name}', auth()->user()->name, $query);
        }

        /** @var ExtractedInfo $info */
        $info = ResearchAgent::make()
            ->withChatHistory($this->history)
            ->structured(
                new UserMessage($query),
                ExtractedInfo::class
            );

        if (!isset($info->tour) || !$info->tour->isComplete()) {
            $this->interrupt(['question' => $info->description]);
        }

        return new Retrieve($info->tour);
    }
}
