<dialog id="modal" class="modalePage">
    <form id="modal-form" method="POST" action="{{ route('cellier_quantite_bouteille.store') }}">
        <h2 id="modal-title"></h2>
        <hr>
        @csrf
        <label for="quantite">@lang('messages.modify_qty')</label>
        <div class="quantity-input">
            <span class="quantity-btn minus-btn" onclick="decrementQuantity()">&#8722;</span>
            <input name="quantite" type="number" id="quantity" value="" min="1">
            <span class="quantity-btn plus-btn" onclick="incrementQuantity()">&#43;</span>
        </div>
        <input type="hidden" name="bouteille_id" id="bouteille-id">
        <input type="hidden" name="cellier_id" id="cellier-id">
        <input type="hidden" name="source_page" value="celliers.show">
        <hr>
        <div>
            <button type="submit" class="btn btn-primary btn-details">
                @lang('messages.save')
            </button>
            <button type="button" class="btn btn-secondary btn-details" onclick="closeModal()">
                @lang('messages.cancel')
            </button>
        </div>
    </form>
</dialog>