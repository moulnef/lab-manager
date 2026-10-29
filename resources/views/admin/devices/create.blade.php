@extends('layouts.admin')

@section('title', 'Thêm thiết bị')
@section('page-title', 'Thêm thiết bị mới')

@section('content')
    <form action="{{ route('admin.devices.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm card-body p-4" novalidate>
        @include('admin.devices._form')
    </form>
@endsection
