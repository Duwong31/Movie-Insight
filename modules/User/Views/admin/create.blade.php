@extends('admin.layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-4 text-center">Add New User</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Profile Image Section -->
                    <div class="mb-4 text-center">
                        <label class="form-label">Profile Avatar</label>
                        <div class="d-flex flex-column align-items-center">
                            <div class="mb-3">
                                <div class="profile-preview" id="profile-preview">
                                    <i class="fas fa-user-circle fa-5x text-muted"></i>
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
                            </div>
                        </div>
                    </div>

                    <hr class="mb-4">
                    
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full name</label>
                        <input type="text" name="fullname" id="fullname"
                               class="form-control" value="{{ old('fullname') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone number</label>
                        <input type="tel" name="phone" id="phone"
                               class="form-control" value="{{ old('phone') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="1">Admin</option>
                            <option value="2" selected>User</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add User</button>
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
</style>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const preview = document.getElementById('profile-preview');
        
        reader.onload = function(e) {
            // Replace the icon with an image
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Profile Preview">';
            preview.style.borderStyle = 'solid';
            preview.style.borderColor = '#28a745';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        // Reset to default icon if no file selected
        const preview = document.getElementById('profile-preview');
        preview.innerHTML = '<i class="fas fa-user-circle fa-5x text-muted"></i>';
        preview.style.borderStyle = 'dashed';
        preview.style.borderColor = '#dee2e6';
    }
}
</script>
@endsection