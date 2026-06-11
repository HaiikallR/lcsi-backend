{{--
    components/ui/input.blade.php
    ─────────────────────────────────────────────────────────────────────────
    ANONYMOUS BLADE COMPONENT

    Komponen ini menggabungkan label + input + error message
    menjadi satu unit yang konsisten di seluruh form.

    @props mendefinisikan props yang diterima.
    $attributes->merge() memastikan attribute HTML lain (class, required, dll)
    tetap bisa di-pass dari luar.

    Contoh:
    <x-ui.input name="nama" label="Nama Lengkap" :value="old('nama', $pelanggan->nama)" required />
    <x-ui.input name="email" label="Email" type="email" required />
--}}
@props([
    'name',
    'label',
    'type'  => 'text',
    'value' => null,
    'help'  => null,
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-1.5">
        {{ $label }}
        @if($attributes->has('required'))
            <span class="text-red-400 ml-0.5">*</span>
        @endif
    </label>

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-slate-800
                        placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500
                        focus:border-transparent transition ' .
                        ($errors->has($name) ? 'border-red-300 bg-red-50/30' : 'border-slate-200')
        ]) }}
    >

    @if($help && !$errors->has($name))
        <p class="mt-1.5 text-xs text-slate-400">{{ $help }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>