<x-guest-layout>
    <div class="px-16 py-16">
        <!-- Page Title -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-semibold text-gray-900">
                Sign <span class="text-blue-600">in</span>
            </h1>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf


            <!-- Email Address -->
            <div class="mb-8 relative">
                <div class="input-group">
                    <input id="email" class="floating-input" 
                        type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder=" " />
                    <label for="email" class="floating-label">Email / Username</label>
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-3" />
            </div>

            <!-- Password -->
            <div class="mb-12 relative">
                <div class="input-group">
                    <input id="password" class="floating-input" 
                        type="password" name="password" required autocomplete="current-password" placeholder=" " />
                    <label for="password" class="floating-label">Password</label>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-3" />
            </div>


            <!-- Login Button -->
            <div class="flex justify-center">
                <button type="submit" class="px-16 py-3 bg-blue-900 hover:bg-blue-800 text-white text-lg font-medium rounded-lg shadow-md transition duration-200">
                    Sign in
                </button>
            </div>
        </form>
    </div>

    <style>
        .input-group {
            position: relative !important;
        }

        .floating-input {
            width: 100% !important;
            padding: 1.25rem 1.25rem !important;
            font-size: 1rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            outline: none !important;
            transition: all 0.2s ease !important;
            background: white !important;
        }

        .floating-input:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 1px #3b82f6 !important;
        }

        .floating-label {
            position: absolute !important;
            left: 1.25rem !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            font-size: 1rem !important;
            color: #9ca3af !important;
            pointer-events: none !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            background: white !important;
            padding: 0 0.375rem !important;
        }

        /* Label naik saat input di-focus atau sudah terisi */
        .floating-input:focus ~ .floating-label,
        .floating-input:not(:placeholder-shown) ~ .floating-label {
            top: 0 !important;
            transform: translateY(-50%) !important;
            font-size: 0.875rem !important;
            color: #3b82f6 !important;
            font-weight: 500 !important;
        }

        /* Label tetap di atas tapi warna abu-abu saat tidak focus tapi terisi */
        .floating-input:not(:focus):not(:placeholder-shown) ~ .floating-label {
            color: #6b7280 !important;
        }
    </style>
</x-guest-layout>

