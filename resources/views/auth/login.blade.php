<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="account-box">
        <div class="account-wrapper">
            <center>
                <a href="/" class="">
                    <img src="assets/img/logo1.png" class="w-20 fill-current text-gray-500" />
                </a>
            </center>
            <br>
            <h3 class="account-title">Login</h3>
            <p class="account-subtitle">Access to our dashboard</p>
                    
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
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
                <!-- <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div> -->

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="ml-3 main_button">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
                <!-- <div class="flex items-center justify-center mt-4">
                    <div class="account-footer">
                        <p>Don't have an account yet? <a href="register.php">Register</a></p>
                    </div>
                </div> -->

            </form>

        </div>
    </div>

</x-guest-layout>
