<?php

namespace App\Neuron\Agents;

use NeuronAI\StructuredOutput\SchemaProperty;

class ExtractedInfo
{
    #[SchemaProperty(
        description: 'Use this field to describe the missing information you need to complete the task. Use a curated markdown syntax to format the text.',
        required: true
    )]
    public string $description;

    #[SchemaProperty(
        description: 'The extracted tour information. If you are not able to fully extract the tour information, you must leave this field empty.',
        required: false
    )]
    public TourInfo $tour;
}
