<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @php
                        // Redirect based on role (fallback if routing doesn't work)
                        $user = Auth::user();
                        if ($user->isAdmin()) {
                            header('Location: ' . route('admin.dashboard'));
                            exit;
                        } elseif ($user->isTeamLeader()) {
                            header('Location: ' . route('team-leader.dashboard'));
                            exit;
                        } elseif ($user->isAgent()) {
                            header('Location: ' . route('agent.dashboard'));
                            exit;
                        }
                    @endphp
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
