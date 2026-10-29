@extends('layouts.admin')

@section('title', 'Danh mục')
@section('page-title', 'Quản lý danh mục')

@section('page-actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i><span class="d-none d-sm-inline ms-1">Thêm danh mục</span></a>
@endsection

@section('content')
    <form method="GET" class="card card-body shadow-sm mb-3">
        <div class="row g-2">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="search" name="keyword" value="{{ $keyword }}" class="form-control" placeholder="Tìm theo tên danh mục...">
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Tìm</button>
                @if ($keyword)
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                @endif
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width:680px">
                <thead>
                    <tr>
                        <th style="width:60px">ID</th>
                        <th>Danh mục</th>
                        <th>Mô tả</th>
                        <th class="text-center">Số thiết bị</th>
                        <th class="text-end" style="width:170px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="text-muted">{{ $category->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="category-icon" style="width:36px;height:36px;font-size:1rem"><i class="bi {{ $category->icon_class }}"></i></span>
                                    <span class="fw-semibold">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td class="small text-muted" style="max-width:320px">{{ \Illuminate\Support\Str::limit($category->description, 90) }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.devices.index', ['category_id' => $category->id]) }}" class="badge rounded-pill text-bg-light border text-decoration-none">
                                    {{ $category->devices_count }}
                                </a>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i> Sửa</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                                          data-confirm="Xóa danh mục &quot;{{ $category->name }}&quot;?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger rounded-start-0" title="Xóa"
                                                @if ($category->devices_count > 0) data-bs-toggle="tooltip" data-bs-title="Danh mục đang có thiết bị" @endif>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="bi bi-tags"></i>Không có danh mục nào.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($categories->hasPages())
            <div class="card-footer bg-white d-flex justify-content-center">{{ $categories->links() }}</div>
        @endif
    </div>
@endsection

@push('scripts')
<script>document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));</script>
@endpush
