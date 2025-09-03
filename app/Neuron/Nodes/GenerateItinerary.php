<?php

namespace App\Neuron\Nodes;

use App\Neuron\Agents\ResearchAgent;
use App\Neuron\Events\CreateItinerary;
use App\Neuron\Events\ProgressEvent;
use App\Neuron\Prompts;
use NeuronAI\Chat\History\ChatHistoryInterface;
use NeuronAI\Chat\Messages\ToolCallMessage;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Tools\ToolInterface;
use NeuronAI\Workflow\Node;
use NeuronAI\Workflow\StopEvent;
use NeuronAI\Workflow\WorkflowState;

class GenerateItinerary extends Node
{
    public function __construct(protected ChatHistoryInterface $history)
    {
    }

    /**
     * @throws \Throwable
     */
    public function __invoke(CreateItinerary $event, WorkflowState $state): \Generator|StopEvent
    {
        $message = \str_replace('{flights}', $state->get('flights'), Prompts::ITINERARY_WRITER);
        $message = \str_replace('{hotels}', $state->get('hotels'), $message);
        $message = \str_replace('{places}', $state->get('places'), $message);

        $result = ResearchAgent::make()
            ->withChatHistory($this->history)
            ->stream(
                new UserMessage($message)
            );

        foreach ($result as $item) {
            if ($item instanceof ToolCallMessage){
                yield new ProgressEvent(
                    \array_reduce($item->getTools(), function (string $carry, ToolInterface $tool): string {
                        $carry .= "\n- Calling tool: ".$tool->getName();
                        return $carry;
                    }, '')."\n"
                );
            } else {
                yield new ProgressEvent($item);
            }
        }

        // Finally, the agent stream returns the AssistantMessage with the whole content
        $state->set('travel_plan', $result->getReturn()->getContent());

        return new StopEvent();
    }
}
