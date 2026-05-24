<section>
    <h2 class="fw-700 font-md text-grey-900 mb-1">Update Password</h2>
    <p class="font-xssss text-grey-500 mb-4">Ensure your account is using a long, random password to stay secure.</p>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="row">
            <div class="col-lg-6 mb-3">
                <label for="update_password_current_password" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Current Password</label>
                <input id="update_password_current_password" name="current_password" type="password" class="form-control rounded-xl border-light font-xssss" autocomplete="current-password">
                @error('current_password', 'updatePassword')
                    <small class="text-danger font-xsssss">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-6 mb-3">
                {{-- Spacer --}}
            </div>

            <div class="col-lg-6 mb-3">
                <label for="update_password_password" class="fw-600 font-xssss text-grey-600 mb-1 d-block">New Password</label>
                <input id="update_password_password" name="password" type="password" class="form-control rounded-xl border-light font-xssss" autocomplete="new-password">
                @error('password', 'updatePassword')
                    <small class="text-danger font-xsssss">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-6 mb-3">
                <label for="update_password_password_confirmation" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Confirm Password</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control rounded-xl border-light font-xssss" autocomplete="new-password">
                @error('password_confirmation', 'updatePassword')
                    <small class="text-danger font-xsssss">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn bg-current text-white font-xssss fw-600 rounded-xl px-4 py-2">
            Update Password
        </button>

        @if (session('status') === 'password-updated')
            <span class="text-success font-xssss ms-3">Password updated successfully</span>
        @endif
    </form>
</section>
