{{--
    components/ui/stat-card.blade.php
    Props: $label, $value, $icon, $colorClasses, $prefix, $suffix, $sub
--}}
<div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">{{ $label }}</p>
            <p class="text-2xl font-display font-bold text-slate-800 truncate" style="font-family: 'Sora', sans-serif;">
                @if($prefix)<span class="text-base font-medium text-slate-500 mr-1">{{ $prefix }}</span>@endif
                {{ is_numeric($value) && $value > 999 ? number_format($value, 0, ',', '.') : $value }}
                @if($suffix)<span class="text-base font-medium text-slate-500 ml-1">{{ $suffix }}</span>@endif
            </p>
            @if($sub)
                <p class="text-xs text-slate-400 mt-1">{{ $sub }}</p>
            @endif
        </div>
        <div class="w-11 h-11 rounded-xl border flex items-center justify-center flex-shrink-0 ml-3 {{ $colorClasses }}">
            @include('Components.ui.icons', ['icon' => $icon, 'class' => 'w-5 h-5'])
        </div>
    </div>
</div>