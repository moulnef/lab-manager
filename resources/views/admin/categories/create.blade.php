@extends('layouts.admin')

@section('title', 'Thêm danh mục')
@section('page-title', 'Thêm danh mục')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-6">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="card shadow-sm card-body p-4" novalidate>
            @include('admin.categories._form')
        </form>
    </div>
</div>
@endsection
