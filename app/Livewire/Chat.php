<?php

namespace App\Livewire;

use App\Neuron\Events\ProgressEvent;
use App\Neuron\TravelPlannerAgent;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Inspector\Laravel\InspectorLivewire;
use Livewire\Attributes\On;
use Livewire\Component;
use NeuronAI\Chat\History\ChatHistoryInterface;
use NeuronAI\Chat\History\FileChatHistory;
use NeuronAI\Exceptions\ChatHistoryException;
use NeuronAI\Exceptions\WorkflowException;
use NeuronAI\Workflow\Persistence\FilePersistence;
use NeuronAI\Workflow\Persistence\PersistenceInterface;
use NeuronAI\Workflow\WorkflowInterrupt;
use NeuronAI\Workflow\WorkflowState;

class Chat extends Component
{
    use InspectorLivewire;

    public string $input;

    public array $messages = [];

    public bool $thinking = false;

    public bool $interrupted = false;

    public function render(): View
    {
        return view('livewire.chat');
    }

    public function chat(): void
    {
        $this->messages[] = [
            'who' => 'user',
            'content' => $this->input,
        ];

        $this->thinking = true;

        $this->dispatch('scroll-bottom');

        $this->dispatch('getAIResponse', $this->input);
        $this->input = '';
    }

    /**
     * @throws \Throwable
     * @throws WorkflowException
     */
    #[On('getAIResponse')]
    public function getAIResponse($input): void
    {
        $workflow = new TravelPlannerAgent(
            \array_first($this->messages)['content'],
            auth()->user()
        );

        try {
            if ($this->interrupted) {
                $handler = $workflow->wakeup($input);
            } else {
                $handler = $workflow->start();
            }

            $message = '';
            foreach ($handler->streamEvents() as $event) {
                if ($event instanceof ProgressEvent) {
                    $message .= $event->message;
                    $this->stream('response', Str::markdown($message, ['html_input' => 'strip']), true);
                }
            }

            $this->messages[] = [
                'who' => 'ai',
                'content' => $handler->getResult()->get('travel_plan'),
            ];
            $this->thinking = false;
            $this->interrupted = false;
            $this->dispatch('scroll-bottom');

        } catch (WorkflowInterrupt $interrupt) {
            $this->interrupted = true;
            $this->messages[] = [
                'who' => 'ai',
                'content' => $interrupt->getData()['question'],
            ];
            $this->thinking = false;
        }
    }
}
