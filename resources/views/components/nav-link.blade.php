@props(['href', 'active' => false])

<a href="{{ $href }}"
   class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors
          {{ $active
              ? 'bg-blue-50 text-blue-700 font-medium'
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
    {{ $slot }}
</a>
