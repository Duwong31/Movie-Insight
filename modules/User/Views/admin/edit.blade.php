@extends('admin.layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-4 text-center">Edit User</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Profile Image Section -->
                    <div class="mb-4 text-center">
                        <label class="form-label">Profile Avatar</label>
                        <div class="d-flex flex-column align-items-center">
                            <div class="mb-3">
                                <div class="profile-preview" id="profile-preview">
                                    @if($user->hasProfileImage())
                                        <img src="{{ $user->profile_image_url }}" alt="{{ $user->fullname }}">
                                    @else
                                        <i class="fas fa-user-circle fa-5x text-muted"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="w-100">
                                <input type="file" 
                                       class="form-control @error('profile_image') is-invalid @enderror" 
                                       id="profile_image" 
                                       name="profile_image" 
                                       accept="image/*"
                                       onchange="previewImage(this)">
                                <small class="form-text text-muted">Choose JPG, PNG or GIF (max 2MB, optional)</small>
                                @error('profile_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($user->hasProfileImage())
                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remove_image" name="remove_profile_image" value="1">
                                            <label class="form-check-label" for="remove_image">
                                                Remove current profile image
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr class="mb-4">

                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full name</label>
                        <input type="text" name="fullname" id="fullname"
                               class="form-control" value="{{ old('fullname', $user->fullname) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone number</label>
                        <input type="tel" name="phone" id="phone"
                               class="form-control" value="{{ old('phone', $user->phone) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>User</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">New password (leave blank if not changing)</label>
                        <input type="password" name="password" id="password"
                               class="form-control">
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm new password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.profile-preview {
    width: 120px;
    height: 120px;
    border: 2px dashed #dee2e6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    transition: all 0.3s ease;
}

.profile-preview:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.profile-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.profile-preview .fas {
    font-size: 3rem;
    color: #6c757d;
}

.profile-preview.has-image {
    border-style: solid;
    border-color: #28a745;
}
</style>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const preview = document.getElementById('profile-preview');
        
        reader.onload = function(e) {
            // Replace the content with new image
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Profile Preview">';
            preview.classList.add('has-image');
            preview.style.borderStyle = 'solid';
            preview.style.borderColor = '#28a745';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
