@props(['danger' => null, 'warning' => null])
<span
    {{ $attributes->class([
        'px-2 py-1 text-xs font-medium text-white  border rounded-lg w-fit dark:text-white',
        'border-red-600 bg-red-600 dark:border-red-600 dark:bg-red-600' => $danger,
        'border-amber-600 bg-amber-600 dark:border-amber-600 dark:bg-amber-600' => $warning,
    ]) }}>
    {{ $slot }}
</span>
