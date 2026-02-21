<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>
        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
            @endif
            <x-primary-button class="ms-3">{{ __('Log in') }}</x-primary-button>
        </div>
    </form>

    @if(app()->environment('local', 'testing'))
    <div class="mt-6 pt-6 border-t border-gray-200">
        <p class="text-xs text-gray-500 text-center mb-3">Quick Login (dev only)</p>
        <div class="grid grid-cols-2 gap-2">
            @foreach([
                'admin@procurement.test' => 'Admin',
                'manager@procurement.test' => 'Manager',
                'buyer@procurement.test' => 'Buyer',
                'warehouse@procurement.test' => 'Warehouse',
                'supplier@procurement.test' => 'Supplier',
            ] as $email => $label)
            <form method="POST" action="{{ route('quick-login') }}">
                @csrf
                <input type="hidden" name="user_email" value="{{ $email }}">
                <button type="submit" class="w-full text-xs px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md border">
                    {{ $label }}
                </button>
            </form>
            @endforeach
        </div>
    </div>
    @endif
</x-guest-layout>
