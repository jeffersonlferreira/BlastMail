<x-layouts.app>
    <x-slot name="header">
        <x-h2>{{ __('Email List') }} > {{ __('Create new list') }}</x-h2>
    </x-slot>

    <x-card>
        <x-form :action="route('email-list.create')" post enctype="multipart/form-data">

            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-input.text id="title" class="block w-full mt-1" name="title" :value="old('title')" autofocus />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="file" :value="__('List File')" />
                <x-input.text id="file" type="file" accept=".csv" class="block w-full mt-1" name="file"
                    autofocus />
                <x-input-error :messages="$errors->get('file')" class="mt-2" />
            </div>

            <div class="flex items-center space-x-4">
                <x-button.secondary type="reset">{{ __('Cancel') }}</x-button.secondary>
                <x-button type="submit">{{ __('Save') }}</x-button>
            </div>

        </x-form>
    </x-card>
</x-layouts.app>
