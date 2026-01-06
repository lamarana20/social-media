@props(['msg', 'bg' => 'bg-green-500'])

<div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => show = false, 5000)" 
    class="mb-4 text-sm font-medium text-white px-4 py-3 rounded-lg {{ $bg }}"
>
    {{ $msg }}
</div>