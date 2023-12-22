<dialog class="modalePage">
    <form class="formulairePage" action="{{ route('cellier_quantite_bouteille.update', 'id-bouteille' )}}" method="POST">
        @csrf
        @method('PUT')
        <div class="plusMinus">
            <span class="quantity-btn plus-btn moins"><img src="{{ asset('icons/minus_icon.svg') }}" alt=""></span>
            <input  name="nouvelleQuantite" class="inputQuantite" type="number" value="" min="1">
            <span class="quantity-btn plus-btn plus"><img src="{{ asset('icons/plus_icon.svg') }}" alt=""></span>
        </div>
        <div class="modaleActions">
            <button class="boutonCellier-cancel espace">@lang('messages.cancel')<span class="material-symbols-outlined">close</span></button>
            <button class="boutonCellier-add espace" type="submit">@lang('messages.save')<span class="material-symbols-outlined">check</span></button>
        </div>
    </form>
</dialog>