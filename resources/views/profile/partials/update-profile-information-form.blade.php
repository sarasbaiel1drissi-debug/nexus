<section>
    <style>
        .profile-image-grid {
            align-items: stretch;
        }

        .profile-image-field {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 154px;
        }

        .profile-image-preview-row {
            min-height: 74px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .profile-image-preview {
            width: 68px;
            height: 68px;
            object-fit: cover;
            background: #111827;
            flex: 0 0 auto;
        }

        .profile-cover-preview {
            width: 132px;
            height: 54px;
            object-fit: cover;
            background: #111827;
            flex: 0 0 auto;
        }

        .profile-remove-option {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin: 0;
            line-height: 1;
        }

        .profile-remove-option input {
            width: 14px;
            height: 14px;
            margin: 0;
        }

        .profile-save-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 18px;
        }
    </style>

    <h2 class="fw-700 font-md text-grey-900 mb-1">Profile Information</h2>
    <p class="font-xssss text-grey-500 mb-4">Update your profile information, avatar, and gaming preferences.</p>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="row">
            {{-- Name --}}
            <div class="col-lg-6 mb-3">
                <label for="name" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Name</label>
                <input id="name" name="name" type="text" class="form-control rounded-xl border-light font-xssss" value="{{ old('name', $user->name) }}" required>
                @error('name') <small class="text-danger font-xsssss">{{ $message }}</small> @enderror
            </div>

            {{-- Username --}}
            <div class="col-lg-6 mb-3">
                <label for="username" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Username</label>
                <input id="username" name="username" type="text" class="form-control rounded-xl border-light font-xssss" value="{{ old('username', $user->username) }}" required>
                @error('username') <small class="text-danger font-xsssss">{{ $message }}</small> @enderror
            </div>

            {{-- Email --}}
            <div class="col-lg-6 mb-3">
                <label for="email" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Email</label>
                <input id="email" name="email" type="email" class="form-control rounded-xl border-light font-xssss" value="{{ old('email', $user->email) }}" required>
                @error('email') <small class="text-danger font-xsssss">{{ $message }}</small> @enderror
            </div>

            {{-- Location --}}
            <div class="col-lg-6 mb-3">
                <label for="location" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Location</label>
                <input id="location" name="location" type="text" class="form-control rounded-xl border-light font-xssss" value="{{ old('location', $user->location) }}">
            </div>

            {{-- Birthdate --}}
            <div class="col-lg-6 mb-3">
                <label for="birthdate" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Birthdate</label>
                <input id="birthdate" name="birthdate" type="date" class="form-control rounded-xl border-light font-xssss" value="{{ old('birthdate', $user->birthdate?->format('Y-m-d')) }}">
            </div>

            {{-- Gender --}}
            <div class="col-lg-6 mb-3">
                <label for="gender" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Gender</label>
                <select id="gender" name="gender" class="form-control rounded-xl border-light font-xssss">
                    <option value="">Not set</option>
                    <option value="male" @selected(old('gender', $user->gender) === 'male')>Male</option>
                    <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
                </select>
            </div>

            {{-- Bio --}}
            <div class="col-12 mb-3">
                <label for="bio" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Bio</label>
                <textarea id="bio" name="bio" rows="3" class="form-control rounded-xl border-light font-xssss">{{ old('bio', $user->bio) }}</textarea>
            </div>

            {{-- Region --}}
            <div class="col-lg-6 mb-3">
                <label for="region" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Region</label>
                <input id="region" name="region" type="text" class="form-control rounded-xl border-light font-xssss" value="{{ old('region', $user->region) }}" placeholder="e.g. NA, EUW">
            </div>

            {{-- Languages --}}
            <div class="col-lg-6 mb-3">
                <label for="languages" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Languages</label>
                <input id="languages" name="languages" type="text" class="form-control rounded-xl border-light font-xssss" value="{{ old('languages', $user->languages) }}" placeholder="e.g. English, French">
            </div>

            {{-- Availability --}}
            <div class="col-lg-6 mb-3">
                <label for="availability" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Availability</label>
                <input id="availability" name="availability" type="text" class="form-control rounded-xl border-light font-xssss" value="{{ old('availability', $user->availability) }}" placeholder="e.g. Evenings, Weekends">
            </div>

            {{-- Playstyle Tags --}}
            <div class="col-lg-6 mb-3">
                <label for="playstyle_tags" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Playstyle Tags (comma-separated)</label>
                <input id="playstyle_tags" name="playstyle_tags" type="text" class="form-control rounded-xl border-light font-xssss" 
                    value="{{ old('playstyle_tags', is_array($user->playstyle_tags) ? implode(', ', $user->playstyle_tags) : $user->playstyle_tags) }}" 
                    placeholder="e.g. Competitive, Casual, Team Player">
            </div>
        </div>

        <hr class="my-4">
        <h4 class="fw-700 font-xsss text-grey-900 mb-3">Profile Pictures</h4>
        <div class="row profile-image-grid">
            {{-- Profile picture --}}
            <div class="col-lg-6 mb-4 profile-image-field">
                <label class="fw-600 font-xssss text-grey-600 mb-1 d-block">Profile Picture</label>
                <div class="profile-image-preview-row">
                    <img src="{{ $user->profile_picture_url }}" class="rounded-circle profile-image-preview" alt="Profile picture preview">
                    @if($user->profile_picture)
                        <label class="profile-remove-option">
                            <input type="checkbox" name="remove_profile_picture" value="1">
                            <span class="font-xssss text-grey-500">Remove and reset to default</span>
                        </label>
                    @endif
                </div>
                <input id="profile_picture" name="profile_picture" type="file" accept="image/*" class="form-control rounded-xl border-light font-xssss">
            </div>

            {{-- Cover picture --}}
            <div class="col-lg-6 mb-4 profile-image-field">
                <label class="fw-600 font-xssss text-grey-600 mb-1 d-block">Cover Picture</label>
                <div class="profile-image-preview-row">
                    <img src="{{ $user->cover_picture ? asset('storage/'.$user->cover_picture) : asset('images/u-bg.jpg') }}" class="rounded-xl profile-cover-preview" alt="Cover picture preview">
                </div>
                <input id="cover_picture" name="cover_picture" type="file" accept="image/*" class="form-control rounded-xl border-light font-xssss">
            </div>

            {{-- Cover Position --}}
            <div class="col-lg-6 mb-3">
                <label for="cover_position" class="fw-600 font-xssss text-grey-600 mb-1 d-block">Cover Position</label>
                <input id="cover_position" name="cover_position" type="text" class="form-control rounded-xl border-light font-xssss" value="{{ old('cover_position', $user->cover_position ?? '50% 50%') }}" placeholder="e.g. 50% 50%">
                <small class="font-xsssss text-grey-500">You can also drag your cover picture directly on your profile to reposition it.</small>
            </div>
        </div>

        <div class="profile-save-row">
            <button type="submit" class="btn bg-current text-white font-xssss fw-600 rounded-xl px-4 py-2">
                Save Changes
            </button>

            @if (session('success'))
                <span class="text-success font-xssss">{{ session('success') }}</span>
            @endif
        </div>
    </form>
</section>
