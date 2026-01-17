<form method="{{ $method ?? 'POST' }}"
      action="{{ $action ?? '' }}"
      class="form-ajax"
      data-notify="{{ $notify ? 'true' : 'false' }}"
      data-jw-token="{{ $token ? 'true' : 'false' }}"
>

    <div class="jquery-message" style="display: none;margin-bottom: 10px"></div>

    {{ $slot }}
</form>
