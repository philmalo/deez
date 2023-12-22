<dialog class="modalePage">
    <form id="pageSelectorForm" class="formulairePage">
        @csrf
        <label for="selecteurPage">@lang('messages.page_selector')</label>
        <input type="text" name="selecteurPage" class="numeroPage" placeholder="entre 1 et {{ $dernierePage }}"  min="1" required data-derniere-page="{{$dernierePage}}">
        <button type="submit" class="page-button">@lang('messages.go')</button>
    </form>
</dialog>