{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}



<x-guest-layout>
<h4 class="text-center mb-4">Sign in your account</h4>
<x-auth-session-status class="mb-4" :status="session('status')" />
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group mb-4">
        <label class="form-label" for="username">Email</label>
        <input type="email"  class="form-control" placeholder="Enter email" id="username" :value="old('email')" name="email" required autofocus autocomplete="username">
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
    <div class="mb-sm-4 mb-3 position-relative">
        <label class="form-label" for="dlab-password">Password</label>
        <input  type="password"
        name="password"
        required autocomplete="current-password"  id="dlab-password" class="form-control" value="123456">
        <span class="show-pass eye">
            <i class="fa fa-eye-slash"></i>
            <i class="fa fa-eye"></i>
        </span>
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>
    <div class="form-row d-flex flex-wrap justify-content-between mb-2">
        <div class="form-group mb-sm-4 mb-1">
            <div class="form-check custom-checkbox ms-1">
                <input id="remember_me" type="checkbox"  class="form-check-input" id="basic_checkbox_1" name="remember" >
                <label class="form-check-label" for="basic_checkbox_1">Remember my preference</label>
                {{-- <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span> --}}
            </div>
        </div>
        <div class="form-group ms-2">
            <a href="page-forgot-password.html">Forgot Password?</a>
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </div>
</form>
{{-- <div class="new-account mt-3">
    <p>Don't have an account? <a class="text-primary" href="{{ route('register') }}">Sign up</a></p>
</div> --}}
</x-guest-layout>