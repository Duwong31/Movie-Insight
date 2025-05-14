@extends('admin.layouts.app') {{-- Kế thừa layout admin chính --}}

@section('title', 'Manage Customers') {{-- Đặt tiêu đề cho trang --}}

@section('content')
<div class="container-fluid"> {{-- Hoặc class container của bạn --}}
    <div class="row mb-3">
        <div class="col">
            <h2>All Customers</h2>
        </div>
        <div class="col text-right">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add New Customer
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-container-dark">
        {{-- 3. USE THE CUSTOM TABLE CLASS --}}
         <table class="table table-dark-custom"> 
             <thead>
                 <tr>
                    {{-- 4. REMOVE MOST text-center --}}
                     <th>S.N.</th> 
                     <th>Username</th>
                     <th>Email</th>
                     <th>Contact Number</th>
                     <th>Joining Date</th>
                     <th class="text-center">Actions</th> {{-- Keep Actions Centered --}}
                 </tr>
             </thead>
             <tbody>
                 @forelse ($users as $key => $user)
                     <tr>
                         <td>{{ $users->firstItem() + $key }}</td> {{-- SN Left Aligned --}}
                         <td>{{ $user->fullname }}</td>
                         <td>{{ $user->email }}</td>
                         <td>{{ $user->phone ?? 'N/A' }}</td>
                         <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                         <td class="text-center">  {{-- Keep TD Centered --}}
                             {{-- 5. USE NEW BUTTON CLASSES --}}
                             <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action btn-action-edit" title="Edit">
                                 <i class="fas fa-pencil-alt"></i> {{-- Use FontAwesome 5/6 icon --}}
                             </a>
                             <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="btn-action btn-action-delete" title="Delete">
                                     <i class="fas fa-trash"></i> {{-- Use FontAwesome 5/6 icon --}}
                                 </button>
                             </form>
                         </td>
                     </tr>
                 @empty
                     <tr>
                         <td colspan="6" class="text-center">No customers found.</td>
                     </tr>
                 @endforelse
             </tbody>
         </table>
      </div>
        @if($users->hasPages())
        <div class="card-footer">
            {{ $users->links() }} {{-- Hiển thị phân trang --}}
        </div>
        @endif
    </div>
</div>
@endsection

