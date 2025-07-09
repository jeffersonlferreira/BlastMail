<x-layouts.app>
    <x-slot name="header">
        <x-h2>{{ __('Templates') }}</x-h2>
    </x-slot>

    <x-card class="space-y-4">

        <div class="flex items-center justify-between">
            <div><span class="opacity-70">{{ __('Name') }}:</span> {{ $template->name }}</div>

            <x-button.link secondary :href="route('templates.index')">{{ __('Back to list') }}</x-button.link>
        </div>

        <div class="flex justify-center p-20 border-2 border-gray-400 rounded">{!! $template->body !!}</div>

    </x-card>
</x-layouts.app>
