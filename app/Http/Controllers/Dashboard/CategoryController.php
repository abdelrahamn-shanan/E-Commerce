<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Enumerations\CategoryType;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
       $categories = Category::all();
        return view('dashboard.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::select('id', 'parent_id')->get();

        return view('dashboard.categories.create', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        try {
            //validation
            if (!$request->has('is_active')) {
                $request->request->add(['is_active' => 0]);
            } else {
                $request->request->add(['is_active' => 1]);
            }
            // upload image
            $filePath = '';
            if ($request->has('photo')) {
                $filePath = uploadImage('maincategories', $request->photo);
            }
            // if user choose maincategory remove parent_id
            if ($request->type == CategoryType::MainCategory) {
                $request->request->add(['parent_id' => null]);
            }
            //if user choose sub category we must add parent-id
            DB::beginTransaction();
            $category = Category::create($request->except('_token'));
            $category->photo = $filePath;
            // save translations
            $category->name = $request->name;
            $category->save();
            DB::commit();

            return redirect()->route('index.category')->with(['success' => __('admin/SuccessMsg.success add')]);
        } catch (\Exception $ex) {
            DB::rollback();

            return redirect()->route('index.category')->with(['error' => __('admin/SuccessMsg.error add')]);
        }
    }

    public function edit($id)
    {
        $category = Category::orderBy('id', 'DESC')->find($id);
        if (!$category) {
            return redirect()->route('index.category')->with(['error' => __('admin/SuccessMsg.Not Found')]);
        }

        return view('dashboard.categories.edit', compact('category'));
    }

    public function update($id, CategoryUpdateRequest $request)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return redirect()->route('index.category')->with(['error' => __('admin/SuccessMsg.Not Found')]);
            }

            if (!$request->has('is_active')) {
                $request->request->add(['is_active' => 0]);
            } else {
                $request->request->add(['is_active' => 1]);
            }
            $filePath = '';
            if ($request->has('photo')) {
                $filePath = uploadImage('maincategories', $request->photo);
            } else {
                $filePath = Str::after(getImage($category->photo), 'assets/');
            }
            $category->update([
            'slug' => $request->slug,
            'is_active' => $request->is_active,
            'photo' => $filePath,
      ]);
            // save translation
            $category->name = $request->name;
            $category->save();

            return redirect()->route('index.category')->with(['success' => __('admin/SuccessMsg.success update')]);
        } catch (\Exception $ex) {
            return redirect()->route('index.category')->with(['error' => __('admin/SuccessMsg.error update')]);
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return redirect()->route('index.category')->with(['error' => __('admin/SuccessMsg.Not Found')]);
            }

            if ($category->photo) {
                $Image = getImage($category->photo);
                unlink($Image);
            }
            $category->delete();

            return redirect()->route('index.category')->with(['success' => __('admin/SuccessMsg.success delete')]);
        } catch (\Exception $ex) {
            return redirect()->route('index.category')->with(['success' => __('admin/SuccessMsg.error delete')]);
        }
    }
}
