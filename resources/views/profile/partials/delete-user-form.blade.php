<section>
    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
        <div>
            <h2 class="fw-700 font-md text-grey-900 mb-1">Delete Account</h2>
            <p class="font-xssss text-grey-500 mb-0">
                Permanently delete your account and all related data. This action cannot be undone.
            </p>
        </div>

        <button type="button" class="btn btn-outline-danger font-xssss fw-600 rounded-xl px-4 py-2" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            Delete Account
        </button>
    </div>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-xxl">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-700 text-grey-900">Confirm account deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="font-xssss text-grey-500">
                            Enter your password to confirm you want to permanently delete your account.
                        </p>

                        <label for="delete_account_password" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Password</label>
                        <input id="delete_account_password" name="password" type="password" class="form-control rounded-xl border-light font-xssss" placeholder="Password">
                        @error('password', 'userDeletion')
                            <small class="text-danger font-xsssss d-block mt-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn bg-greylight text-grey-700 font-xssss fw-600 rounded-xl px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger font-xssss fw-600 rounded-xl px-4">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalElement = document.getElementById('deleteAccountModal');
                if (window.bootstrap?.Modal) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                } else {
                    modalElement.classList.add('show');
                    modalElement.style.display = 'block';
                    document.body.classList.add('modal-open');
                }
            });
        </script>
    @endif
</section>
