<select
    {{ $attributes->class(['mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full appearance-none px-4 py-[10px] text-sm disabled:cursor-not-allowed disabled:opacity-75']) }}>
    {{ $slot }}
</select>
