<dialog class="ajoutCellier" id="modal">
    <form action="{{ route('celliers.store') }}" method="POST">
        @csrf
        <h2>@lang('messages.namecellar')</h2>
        <input type="text" name="nom" id="nom" placeholder="@lang('messages.name_your_cellar')">
        <button class="" type="submit">@lang('messages.add')<img src="{{ asset('icons/plus_icon.svg') }}" alt="Ajouter"></button>
    </form>
</dialog>