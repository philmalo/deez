<dialog class="modalePage">
    <form class="formulairePage" method="POST" action="{{ route('admin.update', 'id-user') }}">
        <h2>@lang('admin.update_user_title')</h2>
        @csrf
        @method('PATCH')
        <div>
            <label for="username">@lang('admin.new_username') :</label>
            <input type="text" id="username" name="username" required>
            <label for="email">@lang('admin.new_email'):</label>
            <input type="email" id="email" name="email" required>
        </div>
        <input type="hidden" id="userId" name="userId">
        <div class="modaleActions">
            <button class="boutonCellier-add espace" type="submit">@lang('admin.modal_update')</button>
            <button class="boutonCellier-cancel espace" id="closeDialog">@lang('admin.modal_close')</button>
        </div>
    </form>
</dialog>