@props(['label', 'name'])

<div class="mb-4"></div>
    <label for="{{ $name }}" class="block text-white font-bold mb-2">
        {{ $label ?? __('Color') }}
    </label>
    <input 
        type="color" 
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, '#4F46E5') }}"
        class="w-16 h-8 bg-gray-800 rounded border border-gray-700 cursor-pointer"
    >
</div>