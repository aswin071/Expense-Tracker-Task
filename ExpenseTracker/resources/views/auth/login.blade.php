<x-guest-layout>

    <h2>Sign in</h2>

    @if (session('status'))
        <div class="flash-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username" class="form-input"
                   placeholder="you@example.com">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input id="password" type="password" name="password"
                   required autocomplete="current-password" class="form-input"
                   placeholder="Enter your password">
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <label>
                <input type="checkbox" name="remember" id="remember_me">
                Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn-primary">Sign in</button>
    </form>

    <p class="guest-footer">
        Don't have an account? <a href="{{ route('register') }}">Create one</a>
    </p>

</x-guest-layout>
