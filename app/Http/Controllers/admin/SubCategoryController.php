<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    //

    public function index(Request $request)
    {
        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')->latest('sub_categories.id')->leftJoin('categories', 'categories.id', 'sub_categories.category_id');

        if (!empty($request->get('keyword'))) {
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%' . $request->get('keyword') . '%');
            $subCategories = $subCategories->orWhere('categories.name', 'like', '%' . $request->get('keyword') . '%');
        }
        $subCategories = $subCategories->paginate(10);

        return view('admin.sub_category.list', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub_category.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required'
        ]);

        if ($validator->passes()) {

            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            // Save Image Here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);

                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $subCategory->id . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/subcategory/' . $newImageName;
                File::copy($sPath, $dPath);

                // Generate Image Thumbnail
                $dPath = public_path() . '/uploads/subcategory/thumb/' . $newImageName;
                $img = Image::make($sPath);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $subCategory->image = $newImageName;
                $subCategory->save();
            }

            $request->session()->flash('success', 'Sub Category Created Successfully');

            return response([
                'status' => true,
                'message' => "Sub Category Created Successfully"
            ]);
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request)
    {

        $subCategory = SubCategory::find($id);
        if (empty($subCategory)) {
            $request->session()->flash('error', 'Record Not Found');
            return redirect()->route('sub-categories.index');
        }

        $categories = Category::orderBy('name', 'ASC')->get();

        return view('admin.sub_category.edit', compact(['categories', 'subCategory']));
    }

    public function update($id, Request $request)
    {

        $subCategory = SubCategory::find($id);

        if (empty($subCategory)) {
            $request->session()->flash('error', 'Record Not Found');
            return response([
                'status' => false,
                'notFound' => true,
                'message' => "Record Not Found"
            ]);
            // return redirect()->route('sub-categories.index');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'slug' => 'required|unique:sub_categories',
            'slug' => 'required|unique:sub_categories,slug,' . $subCategory->id . ',id',
            'category' => 'required',
            'status' => 'required'
        ]);

        if ($validator->passes()) {

            // $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            $oldImage = $subCategory->image;

            // Save Image Here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);

                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $subCategory->id . '-' . time() . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/subcategory/' . $newImageName;
                File::copy($sPath, $dPath);

                // Generate Image Thumbnail
                $dPath = public_path() . '/uploads/subcategory/thumb/' . $newImageName;
                $img = Image::make($sPath);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $subCategory->image = $newImageName;
                $subCategory->save();

                // Delete Old Images Here
                if (!empty($oldImage)) {
                    File::delete(public_path() . '/uploads/subcategory/thumb/' . $oldImage);
                    File::delete(public_path() . '/uploads/subcategory/' . $oldImage);
                }
            }

            $request->session()->flash('success', 'Sub Category Updated Successfully');

            return response([
                'status' => true,
                'message' => "Sub Category Updated Successfully"
            ]);
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destory($id, Request $request)
    {

        $subCategory = SubCategory::find($id);

        if (empty($subCategory)) {
            $request->session()->flash('error', 'Record Not Found');
            return response([
                'status' => false,
                'notFound' => true,
                'message' => "Record Not Found"
            ]);
            // return redirect()->route('sub-categories.index');
        }

        $subCategory->delete();

        $request->session()->flash('success', 'Sub Category Deleted Successfully');
        return response([
            'status' => true,
            'message' => "Sub Category Deleted Successfully"
        ]);
    }
}