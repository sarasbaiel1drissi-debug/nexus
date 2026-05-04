@extends('include.master')
@section('page', 'Profile Edit')
@section('content')

<div class="main-content right-chat-active">
    <div class="middle-sidebar-bottom">
        <div class="middle-sidebar-left">
            <div class="row">
                <div class="col-xl-12 col-xxl-12 col-lg-12">
                    <div class="card shadow-xss w-100 d-block d-flex border-0 p-4 mb-3">
                        <h2 class="fw-700 mb-0 mt-0 font-md text-grey-900 d-flex align-items-center">
                            Edit Profile
                        </h2>
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12">
                    <div class="card shadow-xss w-100 d-block d-flex border-0 p-4 mb-3">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                    
                    <div class="card shadow-xss w-100 d-block d-flex border-0 p-4 mb-3">
                        <livewire:game-manager />
                    </div>

                    <div class="card shadow-xss w-100 d-block d-flex border-0 p-4 mb-3">
                        @include('profile.partials.update-password-form')
                    </div>

                    <div class="card shadow-xss w-100 d-block d-flex border-0 p-4 mb-3">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
