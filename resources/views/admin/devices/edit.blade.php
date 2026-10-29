@extends('layouts.admin')

@section('title', 'Sửa thiết bị')
@section('page-title', 'Sửa thiết bị: '.$device->code)

@section('content')
    <form action="{{ route('admin.devices.update', $device) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm card-body p-4" novalidate>
        @method('PUT')
        @include('admin.devices._form')
    </form>
@endsection
