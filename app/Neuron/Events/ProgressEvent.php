<?php

namespace App\Neuron\Events;

use NeuronAI\Workflow\Event;

class ProgressEvent implements Event
{
    public function __construct(public string $message)
    {
    }
}
