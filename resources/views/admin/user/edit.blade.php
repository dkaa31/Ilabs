@extends('admin.template.master')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
    <form method="POST" action="{{ route('user.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password Baru (opsional)</label>
            <input type="password" name="password" class="form-control" minlength="6">
            <div class="form-text">Kosongkan jika tidak ingin ganti password</div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning">Update</button>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection
