<form method="post" action="{{ route('calculate') }}">
    @csrf

    <label for="class">{{ __('messages.class') }}</label>
    <input type="text" id="class" name="class" value="{{ old('class', '10') }}">
    @error('class')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <label for="methods">{{ __('messages.methods') }}</label>
    <input type="text" id="methods" name="methods" value="{{ old('methods', '9.5') }}">
    @error('methods')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <label for="dit">{{ __('messages.dit') }}</label>
    <input type="text" id="dit" name="dit" value="{{ old('dit', '3') }}">
    @error('dit')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <label for="confidence">{{ __('messages.confidence') }}</label>
    <input type="text" id="confidence" name="confidence" value="{{ old('confidence', '0.95') }}">
    @error('confidence')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <button type="submit">{{ __('messages.submit') }}</button>
</form>

<style>
    .alert {
        padding: 5px;
        margin-top: 5px;
        color: red;
        border: 1px solid red;
    }
</style>
