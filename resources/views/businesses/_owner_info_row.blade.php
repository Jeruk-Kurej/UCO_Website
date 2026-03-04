<div class="flex items-center gap-2.5 py-2 border-b border-gray-50 last:border-0">
    <div class="w-6 h-6 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
        <i class="bi {{ $icon }} text-gray-400 text-xs"></i>
    </div>
    <div class="flex-1 min-w-0">
        <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">{{ $label }}</span>
        <p class="text-xs font-medium text-gray-800 truncate leading-tight">{{ $value }}</p>
    </div>
</div>
