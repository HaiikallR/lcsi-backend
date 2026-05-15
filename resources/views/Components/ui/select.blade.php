{{--
    components/ui/select.blade.php
    ─────────────────────────────────────────────────────────────────────────
    ANONYMOUS BLADE COMPONENT — Select / Dropdown

    Contoh:
    <x-ui.select name="status" label="Status" :options="['aktif' => 'Aktif', 'tidak aktif' => 'Tidak Aktif']" :selected="old('status', $pelanggan->status)" />
--}}
@props([
    'name',
    'label',
    'options'  => [],
    'selected' => null,
    'placeholder' => 'Pilih...',
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-1.5">
        {{ $label }}
        @if($attributes->has('required'))
            <span class="text-red-400 ml-0.5">*</span>
        @endif
    </label>

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-slate-800
                        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition ' .
                        ($errors->has($name) ? 'border-red-300' : 'border-slate-200')
        ]) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $val => $label_opt)
            <option value="{{ $val }}" @selected(old($name, $selected) == $val)>
                {{ $label_opt }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>