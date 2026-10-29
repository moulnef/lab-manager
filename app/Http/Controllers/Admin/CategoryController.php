<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * CRUD Bảng 1 – Danh mục thiết bị.
 */
class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('keyword'));

        // withCount('devices') → thêm cột devices_count bằng 1 truy vấn con, không bị N+1
        $categories = Category::withCount('devices')
            ->when($keyword !== '', fn ($q) => $q->where('name', 'like', "%{$keyword}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'keyword'));
    }

    public function create()
    {
        return view('admin.categories.create', ['category' => new Category]);
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', "Đã thêm danh mục \"{$category->name}\".");
    }

    public function show(Category $category)
    {
        return redirect()->route('admin.devices.index', ['category_id' => $category->id]);
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', "Đã cập nhật danh mục \"{$category->name}\".");
    }

    public function destroy(Category $category)
    {
        // Chặn xóa danh mục đang có thiết bị (kể cả thiết bị trong thùng rác)
        $deviceCount = $category->devices()->withTrashed()->count();
        if ($deviceCount > 0) {
            return back()->with('error',
                "Không thể xóa danh mục \"{$category->name}\" vì đang có {$deviceCount} thiết bị. Hãy chuyển hoặc xóa các thiết bị trước.");
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Đã xóa danh mục \"{$category->name}\".");
    }
}
