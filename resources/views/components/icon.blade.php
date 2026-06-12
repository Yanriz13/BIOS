@props([
    'name',
    'class' => 'w-4 h-4',
    'strokeWidth' => '1.5',
    'boxed' => true,
    'boxClass' => 'inline-flex items-center justify-center rounded-lg border border-slate-300 p-1',
])

@php
    // Heroicons v2 (24/outline)
    $paths = [
        'back' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />',
        'edit' => '<path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />',
        'trash' => '<path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0C9.16 2.313 8.25 3.296 8.25 4.477v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />',
        'eye' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.178c.07.207.07.437 0 .644C20.577 16.49 16.64 19.5 12 19.5s-8.577-3.01-9.964-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />',
        'download' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M7.5 10.5 12 15m0 0 4.5-4.5M12 15V3" />',
        'close' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />',
        'plus' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />',
        'history' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
        'daily_routine' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />',
        'refresh' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992V4.356m0 0-4.992 4.992M2.985 19.644v-4.992m0 4.992 4.992-4.992m13.038-5.304a9 9 0 0 0-15.89-5.332l-2.14 2.14m18.03 12.522a9 9 0 0 1-15.89 5.332l-2.14-2.14" />',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.742-.479 3 3 0 0 0-4.682-2.72m.94 3.198v.001c0 .518-.115 1.01-.321 1.452m.32-1.453a5.997 5.997 0 0 0-11.358 0m11.358 0A5.99 5.99 0 0 1 12 21a5.99 5.99 0 0 1-5.999-5.281m0 0a3 3 0 0 0-4.681 2.72A9.094 9.094 0 0 0 5.06 18.72m.94-3.197a3 3 0 1 1 5.999 0 3 3 0 0 1-6 0m6 0a3 3 0 1 1 5.999 0 3 3 0 0 1-6 0M9 7.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Z" />',
        'home' => '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75V19.5A1.5 1.5 0 0 0 6 21h3.75v-4.5A1.5 1.5 0 0 1 11.25 15h1.5a1.5 1.5 0 0 1 1.5 1.5V21H18a1.5 1.5 0 0 0 1.5-1.5V9.75" />',
        'checklist' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6 2.25c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9Z" />',
        'note' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H6.75A2.25 2.25 0 0 0 4.5 4.5v15A2.25 2.25 0 0 0 6.75 21h10.5a2.25 2.25 0 0 0 2.25-2.25v-4.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 3v4.125c0 .621.504 1.125 1.125 1.125H18" />',
    ];

    $iconPath = $paths[$name] ?? '<circle cx="12" cy="12" r="9" />';
@endphp

@if($boxed)
    <span {{ $attributes->merge(['class' => $boxClass]) }}>
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $strokeWidth }}" aria-hidden="true">
            {!! $iconPath !!}
        </svg>
    </span>
@else
    <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $strokeWidth }}" aria-hidden="true">
        {!! $iconPath !!}
    </svg>
@endif