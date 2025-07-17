@props(['heading', 'subheading'])

<div class="p-8 text-center border-2 border-slate-700 bg-slate-800 rounded-xl">
    <div class="font-mono text-5xl font-medium">{{ $heading }}</div>

    <div class="mt-1 text-xl opacity-80">{{ $subheading }}</div>
</div>
