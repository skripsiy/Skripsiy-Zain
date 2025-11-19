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
                <div class="flex items-center relative">
                    <div class="absolute -top-2.5 left-4 bg-white px-1.5 text-sm text-gray-500">Email / Username</div>
                    <input id="email" class="w-full px-5 py-4 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" 
                        type="text" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-12 relative">
                <div class="flex items-center relative">
                    <div class="absolute -top-2.5 left-4 bg-white px-1.5 text-sm text-gray-500">Password</div>
                    <input id="password" class="w-full px-5 py-4 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" 
                        type="password" name="password" required autocomplete="current-password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Login Button -->
            <div class="flex justify-center">
                <button type="submit" class="px-16 py-3 bg-blue-900 hover:bg-blue-800 text-white text-lg font-medium rounded-lg shadow-md transition duration-200">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
