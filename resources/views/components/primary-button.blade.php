<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-slate-700 border border-transparent rounded-md font-semibold text-sm text-white tracking-wide hover:bg-slate-600 focus:bg-slate-600 active:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full']) }}>
    {{ $slot }}
</button>
