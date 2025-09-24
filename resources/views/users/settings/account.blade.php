@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-cog"></i> Account Settings</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.account.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Image Section -->
                        <div class="mb-4">
                            <label class="form-label">Profile Image</label>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    @if($user->hasProfileImage())
                                        <img src="{{ $user->profile_image_url }}" 
                                             alt="{{ $user->fullname }}" 
                                             class="rounded-circle img-thumbnail"
                                             style="width: 80px; height: 80px; object-fit: cover;"
                                             id="profile-preview">
                                    @else
                                        <i class="fas fa-user-circle fa-4x text-muted" id="profile-preview"></i>
                                    @endif
                                </div>
                                <div>
                                    <input type="file" 
                                           class="form-control @error('profile_image') is-invalid @enderror" 
                                           id="profile_image" 
                                           name="profile_image" 
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    <small class="form-text text-muted">Choose JPG, PNG or GIF (max 2MB)</small>
                                    @error('profile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($user->hasProfileImage())
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeProfileImage()">
                                                <i class="fas fa-trash"></i> Remove Image
                                            </button>
                                            <input type="hidden" name="remove_profile_image" id="remove_profile_image" value="0">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" 
                                   class="form-control @error('fullname') is-invalid @enderror" 
                                   id="fullname" 
                                   name="fullname" 
                                   value="{{ old('fullname', $user->fullname) }}" 
                                   required>
                            @error('fullname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone (Optional)</label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        <h5>Change Password</h5>
                        <p class="text-muted">Leave blank if you don't want to change your password</p>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Profile
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const preview = document.getElementById('profile-preview');
        
        reader.onload = function(e) {
            // Replace the icon with an image
            preview.outerHTML = '<img src="' + e.target.result + '" alt="Profile Preview" class="rounded-circle img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;" id="profile-preview">';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeProfileImage() {
    document.getElementById('remove_profile_image').value = '1';
    document.getElementById('profile_image').value = '';
    
    // Replace image with default icon
    const preview = document.getElementById('profile-preview');
    preview.outerHTML = '<i class="fas fa-user-circle fa-4x text-muted" id="profile-preview"></i>';
    
    // Hide the remove button
    event.target.parentElement.style.display = 'none';
}
</script>
@endsection
