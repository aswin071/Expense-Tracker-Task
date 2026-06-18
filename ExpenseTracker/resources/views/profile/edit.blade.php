<x-app-layout>

<div class="section-heading">Profile</div>

{{-- User info chip --}}
<div style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border-radius: 16px; padding: 20px 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 14px;">
    <div style="width: 52px; height: 52px; background: rgba(255,255,255,0.25); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; color: #fff; flex-shrink: 0;">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>
    <div>
        <div style="font-size: 16px; font-weight: 700; color: #fff;">{{ auth()->user()->name }}</div>
        <div style="font-size: 13px; color: rgba(255,255,255,0.75); margin-top: 2px;">{{ auth()->user()->email }}</div>
    </div>
</div>

{{-- Update profile info --}}
<div class="section-heading">Account Details</div>
<div class="card" style="margin-bottom: 14px;">
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label class="form-label" for="name">Full name</label>
            <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                   required autocomplete="name" class="form-input">
            @error('name', 'updateProfileInformation')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                   required autocomplete="username" class="form-input">
            @error('email', 'updateProfileInformation')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="submit" class="btn-primary" style="flex: 1; padding: 13px;">Save Changes</button>
            @if (session('status') === 'profile-updated')
                <span style="font-size: 13px; color: #16a34a; font-weight: 600;">Saved!</span>
            @endif
        </div>
    </form>
</div>

{{-- Change password --}}
<div class="section-heading">Change Password</div>
<div class="card" style="margin-bottom: 14px;">
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label" for="current_password">Current password</label>
            <input id="current_password" type="password" name="current_password"
                   autocomplete="current-password" class="form-input" placeholder="Enter current password">
            @error('current_password', 'updatePassword')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="new_password">New password</label>
            <input id="new_password" type="password" name="password"
                   autocomplete="new-password" class="form-input" placeholder="Min. 8 characters">
            @error('password', 'updatePassword')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm new password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   autocomplete="new-password" class="form-input" placeholder="Repeat new password">
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="submit" class="btn-primary" style="flex: 1; padding: 13px;">Update Password</button>
            @if (session('status') === 'password-updated')
                <span style="font-size: 13px; color: #16a34a; font-weight: 600;">Updated!</span>
            @endif
        </div>
    </form>
</div>

{{-- Logout --}}
<div class="section-heading">Session</div>
<div class="card" style="margin-bottom: 14px;">
    <p style="font-size: 13px; color: #6b7280; margin-bottom: 14px;">
        Sign out of your account on this device.
    </p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="width: 100%; padding: 13px; background: #4f46e5; color: #fff; border: none; border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; font-family: inherit;">
            Log Out
        </button>
    </form>
</div>

{{-- Delete account --}}
<div class="section-heading">Danger Zone</div>
<div class="card" style="border: 1.5px solid #fee2e2;">
    <p style="font-size: 13px; color: #6b7280; margin-bottom: 14px; line-height: 1.5;">
        Once your account is deleted, all data will be permanently removed. This cannot be undone.
    </p>
    <form method="POST" action="{{ route('profile.destroy') }}"
          onsubmit="return confirm('Are you sure? This will permanently delete your account.')">
        @csrf
        @method('DELETE')
        <div class="form-group">
            <label class="form-label" for="del_password">Confirm your password</label>
            <input id="del_password" type="password" name="password"
                   class="form-input" placeholder="Enter password to confirm">
            @error('password', 'userDeletion')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn-danger" style="width: 100%; padding: 13px; border-radius: 12px; font-size: 15px;">
            Delete Account
        </button>
    </form>
</div>

</x-app-layout>
