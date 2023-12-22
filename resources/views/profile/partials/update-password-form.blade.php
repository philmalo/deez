<section>
    <header>
        <h2>{{ __('messages.update_password') }}</h2>

        <p>{{ __('messages.update_password_text') }}
</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="loginForm">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" :value="__('messages.current_password')" />
            <x-text-input id="current_password" name="current_password" type="password" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div>
            <x-input-label for="password" :value="__('messages.new_password')" />
            <x-text-input id="password" name="password" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('messages.confirm_password')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div>
            <x-primary-button>{{ __('messages.save') }}<span class="material-symbols-outlined">check</span></x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">{{ __('messages.saved') }}</p>
            @endif
        </div>
    </form>
</section>
