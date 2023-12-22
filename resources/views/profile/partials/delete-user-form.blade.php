<section>
    <header>
        <h2>{{ __('messages.delete_account') }}</h2>
        <p>{!! __('messages.delete_account_text') !!}</p>
    </header>

        <div class="supprimer-container">
            <x-danger-button class="profilSupprimergs" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('messages.delete_account') }}<span class="material-symbols-outlined">report</span></x-danger-button>
        </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="loginForm red-border">
            @csrf
            @method('delete')

            <h2 class="attention">{{ __('messages.warning') }}</h2>
            <p>{!! __('messages.delete_account_text_small') !!}<p>
            <p>{!! __('messages.delete_account_text') !!}</p>

            <div>
                <x-input-label for="password" value="{{ __('messages.password') }}" />
                <x-text-input id="password" name="password" type="password" placeholder="" />
                <x-input-error :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="delete-friction"> {{-- fenetre qui suit quand on clique --}}
                <x-danger-button class="profilSupprimergs">{{ __('messages.delete_account') }}<span class="material-symbols-outlined">report</span></x-danger-button>
                <x-secondary-button class="cancel-button" x-on:click="$dispatch('close')">{{ __('messages.cancel') }}<span class="material-symbols-outlined">close</span></x-secondary-button>
            </div>
        </form>
    </x-modal>
</section>
