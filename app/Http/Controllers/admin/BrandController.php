<?php

namespace App\Http\Controllers\admin;

use App\Models\Brand;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    //
    public function index(Request $request){
        $brands = Brand::latest('id');

        if($request->get('keyword')){
            $brands = $brands->where('name','like','%'.$request->keyword.'%');
        }

        $brands = $brands->paginate(10);

        return view('admin.brands.list',compact('brands'));

    } 

    public function create(){
        return view('admin.brands.create'); 
    }

    public function store(Request $request){
         $validator = Validator::make($request->all(),[
             'name' => 'required',
             'slug' => 'required|unique:brands'
         ]);

         if($validator->passes()){
             $brand = new Brand();

             $brand->name = $request->name;
             $brand->slug = $request->slug;
             $brand->status = $request->status;
             $brand->save();

             // Save Image Here
             if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);

                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $brand->id . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/brand/' . $newImageName;
                File::copy($sPath, $dPath);

                // Generate Image Thumbnail
                $dPath = public_path() . '/uploads/brand/thumb/' . $newImageName;
                $img = Image::make($sPath);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $brand->image = $newImageName;
                $brand->save();
            }

             return response()->json([
                'status' => true,
                'message' => 'Brand Created Successfully'
             ]);

         }
         else{
            return response()->json([
               'status' => false,
               'errors' => $validator->errors()     
            ]);
         }  

    }

    public function edit($id, Request $request){

        $brand = Brand::find($id);

        if(empty($brand)){
            $request->session()->flash('error','Record Not Found');
            return redirect()->route('brands.index');
        }

        return view('admin.brands.edit',compact('brand'));        

    }

    public function update($id,Request $request){

        $brand = Brand::find($id);

        if(empty($brand)){
            $request->session()->flash('error','Record Not Found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Record Not Found'
            ]);
            // return redirect()->route('brands.index');
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id'
        ]);

        if($validator->passes()){
            // $brand = new Brand();

            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $oldImage = $brand->image;

            // Save Image Here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);

                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $brand->id . '-' . time() . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/brand/' . $newImageName;
                File::copy($sPath, $dPath);

                // Generate Image Thumbnail
                $dPath = public_path() . '/uploads/brand/thumb/' . $newImageName;
                $img = Image::make($sPath);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $brand->image = $newImageName;
                $brand->save();

                // Delete Old Images Here
                File::delete(public_path() . '/uploads/brand/thumb/' . $oldImage);
                File::delete(public_path() . '/uploads/brand/' . $oldImage);
            }

            $request->session()->flash('success','Brand Updated Successfully');

            return response()->json([
               'status' => true,
               'message' => 'Brand Updated Successfully'
            ]);

        }
        else{
           return response()->json([
              'status' => false,
              'errors' => $validator->errors()     
           ]);
        }
        
    }

}
