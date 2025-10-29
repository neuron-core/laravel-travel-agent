<div>
    <div class="max-w-2xl mx-auto">

        <!-- Welcome -->
        <div class="text-center">
            <h1 class="text-4xl font-semibold  pb-12">
                Because Netflix will still be there <br>
                when you get back
            </h1>

            <p class="text-lg pb-12">
                Describe your destination and let our travel agent help you plan your perfect trip.
            </p>
        </div>

        <!-- Messages List -->
        <div>
            @foreach($messages as $message)
                <!-- User -->
                @if($message['who'] === 'user')
                    <div class="bg-purple-800 text-white rounded p-4 my-12">
                            {!! \Illuminate\Support\Str::markdown($message['content']) !!}
                    </div>
                @endif

                <!--  LLM -->
                @if($message['who'] === 'ai')
                    <div class="my-12">
                            {!! \Illuminate\Support\Str::markdown($message['content']) !!}
                    </div>
                @endif
            @endforeach

            @if($thinking)
                <div class="my-12" wire:stream="response">
                </div>
                <img src="{{asset('/images/thinking.gif')}}" width="50" alt=""/>
            @endif
        </div>

        <!-- Chat Box -->
        <form wire:submit.prevent="chat">
            <label for="chat" class="sr-only">Your message</label>
            <div class="flex items-center py-2 px-3 bg-gray-50 rounded-lg dark:bg-gray-700">
                <input
                    wire:model.defer="input"
                    id="chat"
                    rows="1"
                    class="block mx-4 p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Type here..."
                    required
                />
                <button type="submit" class="inline-flex justify-center p-2 text-blue-600 rounded-full cursor-pointer hover:bg-blue-100 dark:text-blue-500 dark:hover:bg-gray-600">
                    <svg class="w-6 h-6 rotate-90" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                </button>
            </div>
        </form>

        <p class="mt-8 text-sm">
            The travel agent running behind the scenes is built with Neuron PHP framework. For more information, please visit
            <a class="text-blue-600 hover:underline" href="https://docs.neuron-ai.dev" target="_blank">Neuron Documentation</a>.
        </p>
    </div>
</div>
