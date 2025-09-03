<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inspector\Laravel\Middleware\WebRequestMonitoring;
use Symfony\Component\HttpFoundation\Response;

class LivewireMiddleware extends WebRequestMonitoring
{
    protected function shouldRecorded($request): bool
    {
        \Log::debug($request->getRequestUri(), $request->all());
        return isset($request->updates) && $request->getRequestUri() === '/livewire/update';
    }

    protected function buildTransactionName($request): string
    {
        \Log::debug($request->getRequestUri(), $request->all());

        return 'livewire.update';
    }
}
