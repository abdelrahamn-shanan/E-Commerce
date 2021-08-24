<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use DB;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);

        return view('dashboard.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('dashboard.brands.create');
    }

    public function store(BrandRequest $request)
    {
        try {
            //validation
            if (!$request->has('is_active')) {
                $request->request->add(['is_active' => 0]);
            } else {
                $request->request->add(['is_active' => 1]);
            }
            $fileName = '';
            if ($request->has('photo')) {
                $fileName = uploadImage('brands', $request->photo);
            }
            DB::beginTransaction();

            $brand = Brand::create($request->except('_token', 'photo'));
            $brand->name = $request->name;
            $brand->photo = $fileName;
            $brand->save();
            DB::commit();

            return redirect()->route('index.brand')->with(['success' => __('admin/SuccessMsg.success add')]);
        } catch (\Exception $ex) {
            DB::rollback();

            return redirect()->route('index.brand')->with(['error' => __('admin/SuccessMsg.error add')]);
        }
    }

    public function edit($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return redirect()->route('index.brand')->with(['error' => __('admin/SuccessMsg.Not Found')]);
        }

        return view('dashboard.brands.edit', compact('brand'));
    }

    public function update($id, BrandRequest $request)
    {
        try {
            $brand = Brand::find($id);
            if (!$brand) {
                return redirect()->route('index.brand')->with(['error' => __('admin/SuccessMsg.Not Found')]);
            }

            if (!$request->has('is_active')) {
                $request->request->add(['is_active' => 0]);
            } else {
                $request->request->add(['is_active' => 1]);
            }

            DB::beginTransaction();
            $filePath = '';
            if ($request->has('photo')) {
                $filePath = uploadImage('brands', $request->photo);
            } else {
                $filePath = Str::after(getImage($brand->photo), 'assets/');
            }
            $brand->update([
             'is_active' => $request->is_active,
             'photo' => $filePath,
  ]);
            // save translation
            $brand->name = $request->name;
            $brand->save();
            DB::commit();

            return redirect()->route('index.brand')->with(['success' => __('admin/SuccessMsg.success update')]);
        } catch (\Exception $ex) {
            DB::rollback();

            return redirect()->route('index.brand')->with(['error' => __('admin/SuccessMsg.error update')]);
        }
    }

    public function delete($id)
    {
        try {
            $brand = Brand::find($id);
            if (!$brand) {
                return redirect()->route('index.brand')->with(['error' => __('admin/SuccessMsg.Not Found')]);
            }

            if ($brand->photo) {
                $Image = getImage($brand->photo);
                unlink($Image);
            }
            $brand->delete();

            return redirect()->route('index.brand')->with(['success' => __('admin/SuccessMsg.success delete')]);
        } catch (\Exception $ex) {
            return redirect()->route('index.brand')->with(['error' => __('admin/SuccessMsg.error delete')]);
        }
    }
}
