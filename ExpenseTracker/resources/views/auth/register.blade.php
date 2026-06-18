<x-guest-layout>

    <h2>Create account</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="name">Full name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   required autofocus autocomplete="name" class="form-input"
                   placeholder="John Doe">
            @error('name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autocomplete="username" class="form-input"
                   placeholder="you@example.com">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input id="password" type="password" name="password"
                   required autocomplete="new-password" class="form-input"
                   placeholder="Min. 8 characters">
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   required autocomplete="new-password" class="form-input"
                   placeholder="Repeat your password">
            @error('password_confirmation')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary">Create account</button>
    </form>

    <p class="guest-footer">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </p>

</x-guest-layout>
