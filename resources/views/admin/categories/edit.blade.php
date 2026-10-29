@extends('layouts.admin')

@section('title', 'Sửa danh mục')
@section('page-title', 'Sửa danh mục: '.$category->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-6">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="card shadow-sm card-body p-4" novalidate>
            @method('PUT')
            @include('admin.categories._form')
        </form>
    </div>
</div>
@endsection
