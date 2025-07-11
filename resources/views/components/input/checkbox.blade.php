@props(['label', 'name', 'checked' => null, 'isCheckedWhen' => null])

<label for="{{ $name }}" class="inline-flex items-center">
    <input id="{{ $name }}" type="checkbox" {{ $attributes }} @if ($checked or $isCheckedWhen == $attributes->get('value')) checked @endif
        class="text-indigo-600 border-gray-300 rounded shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
        name="{{ $name }}">
    <span class="text-sm text-gray-600 ms-2 dark:text-gray-400 whitespace-nowrap">{{ $label }}</span>
</label>
