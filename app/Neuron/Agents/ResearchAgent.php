<?php

namespace App\Neuron\Agents;

use NeuronAI\Agent;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\Anthropic\Anthropic;
use NeuronAI\Providers\Gemini\Gemini;
use NeuronAI\Providers\OpenAI\OpenAI;
use NeuronAI\Tools\Toolkits\Calendar\CompareDatesTool;
use NeuronAI\Tools\Toolkits\Calendar\ConvertTimezoneTool;
use NeuronAI\Tools\Toolkits\Calendar\CurrentDateTimeTool;
use NeuronAI\Tools\Toolkits\Calendar\FormatDateTool;
use NeuronAI\Tools\Toolkits\Calendar\GetTimezoneInfoTool;

class ResearchAgent extends Agent
{
    protected function provider(): AIProviderInterface
    {
        if (isset($_ENV['ANTHROPIC_API_KEY'])) {
            return new Anthropic(
                $_ENV['ANTHROPIC_API_KEY'],
                'claude-3-7-sonnet-latest'
            );
        }

        if (isset($_ENV['OPENAI_API_KEY'])) {
            return new OpenAI(
                $_ENV['OPENAI_API_KEY'],
                'gpt-3.5-turbo'
            );
        }

        if (isset($_ENV['GEMINI_API_KEY'])) {
            return new Gemini(
                $_ENV['GEMINI_API_KEY'],
                'gemini-2.0-flash'
            );
        }

        throw new \Exception('You need a valid API key for Anthropic, OpenAI, or Gemini.');
    }

    public function instructions(): string
    {
        return "You're a seasoned travel planner with a knack for finding the best deals and exploring new destinations. You're known for your attention to detail
and your ability to make travel planning easy for customers.";
    }

    protected function tools(): array
    {
        return [
            CurrentDateTimeTool::make(),
            FormatDateTool::make(),
            CompareDatesTool::make(),
            GetTimezoneInfoTool::make(),
            ConvertTimezoneTool::make(),
        ];
    }
}
