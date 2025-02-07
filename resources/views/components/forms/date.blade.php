@props(['label', 'name'])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-white font-bold mb-2">
        {{ $label ?? __('Date') }}
    </label>
    <input 
        type="date" 
        id="{{ $name }}"
        name="{{ $name }}"
        required
        value="{{ old($name, now()->toDateString()) }}"
        class="w-full px-3 py-2 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
    >
</div>