<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\ProductsBrand;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;
use File;
use App\Models\Frontmenu;
use Cache;

class BrandController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return Brand::class;
    }
    
    public function list(Request $request) {
		
		if (request()->ajax()) {
            $model = Brand::select('*');
            $table = DataTables::eloquent($model);
            $table->addIndexColumn();
            $table->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->brand_id . '" />';
            });
            $table->editColumn('brand_name', function ($row) {
                /*if($row->name == 'contact-us')
				{
					return "<a href=" . URL('/') . '/pnkpanel/brand/edit' . $row->brand_id.".html". " target='_blank'>" . $row->brand_name .".html". "</a>";
				}
				else
				{*/
					return "<a href=" . URL('/') . '/pnkpanel/brand/edit/' . $row->brand_id."". ">" . $row->brand_name ."". "</a>";
				//}
            });
            $table->editColumn('status', function ($row) {
                return ($row->status ? 'Active' : 'Inactive');
            });
            $table->editColumn('action', function ($row) {
                $action = (string)view('pnkpanel.component.datatable_action', ['id' => $row->brand_id]);
                return $action;
            });
            $table->rawColumns(['checkbox', 'action', 'brand_name']);
            return $table->make(true);
        }

        $pageData['page_title'] = "Brand Page";
        $pageData['meta_title'] = "Brand Page";
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Brand Page',
                'url' => route('pnkpanel.brand.list')
            ]
        ];
        return view('pnkpanel.brand.list')->with($pageData);
	}
	
	public function edit($id = 0) {
        if($id > 0) {
			$brand = Brand::findOrFail($id);
		} else {
			$brand =  new Brand;
		}
		
		$prefix = ($id > 0 ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix.' Brand';
		$pageData['meta_title'] = $prefix.' Brand';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Brand List',
				 'url' =>route('pnkpanel.brand.list')
			 ],
			 [
				 'title' => $prefix.' Brand',
				 'url' =>route('pnkpanel.brand.edit', $id)
			 ]
		];
		
        return view('pnkpanel.brand.edit', compact('brand'))->with($pageData);;
    }
	
    public function update(Request $request) {
		
		//dd($brand);
		$actType = $request->actType;
		$brand_id = $request->brand_id;
		$is_delete = $request->is_delete;
		$del_chk = $request->del_chk;
		$del_chk_arr = explode(",",$del_chk);
		
		if($actType == 'add') {
			$check_duplicate = Brand::where('brand_name', $request->brand_name)->first();
		} else {
			$check_duplicate = Brand::where('brand_name', $request->brand_name)->where('brand_id', '<>', $brand_id)->first();
		}
		if($check_duplicate)
		{
			session()->flash('site_common_msg_err', 'Brand name already exists. Please change it');
			return redirect()->route('pnkpanel.brand.edit', $brand_id);
		}

		$this->validate($request, [
			'brand_name'	=> 'required|string'
		]);
		
		if($request->display_position) {
			$this->validate($request, [
				'display_position'	=> 'numeric'
			]);
		}
		$brand = Brand::findOrNew($brand_id);
		// if ($request->hasFile('brand_logo_image')) {
		// 	if ($request->file('brand_logo_image')->isValid()) {
		// 		$data = $this->validate($request, [
		// 			'brand_logo_image' => 'required|mimes:jpeg,png,webp,jpg',
		// 		]);
		// 	}
		// }else{
		// 	if($request->display_on_home == 'Yes' && empty($brand->brand_logo_image)){
		// 		$this->validate($request, [
		// 			'brand_logo_image'	=> 'required'
		// 		]);
		//     }
		// }
		if ($request->hasFile('brand_logo_image')) {
			if ($request->file('brand_logo_image')->isValid()) {
				$data = $this->validate($request, [
					'brand_logo_image' => 'required|mimes:jpeg,png,webp,jpg',
				]);
			}
		}else{
			if($request->display_on_home == 'Yes' && (!file_exists(config('const.BRAND_IMAGE_PATH').$brand->brand_logo_image) || empty($brand->brand_logo_image)) ){
				$this->validate($request, [
					'brand_logo_image'	=> 'required'
				]);
		    }elseif($request->display_on_category == 'Yes' && (!file_exists(config('const.BRAND_IMAGE_PATH').$brand->brand_logo_image) || empty($brand->brand_logo_image)) ){
				$this->validate($request, [
					'brand_logo_image'	=> 'required'
				]);
			}
		}
		
		if ($request->hasFile('brand_banner_image')) {
			if ($request->file('brand_banner_image')->isValid()) {
				$data = $this->validate($request, [
					'brand_banner_image' => 'required|mimes:jpeg,png,webp,jpg',
				]);
			}
		}
				
		
		$brand->brand_name =  $request->brand_name;
		$brand->brand_description 	=  $request->brand_description;
		//$brand->display_menu_position 	=  (empty($request->display_menu_position) AND trim($request->display_menu_position) == '') ?  99999 : $request->display_menu_position;
		
		$brand->display_position 	=  (empty($request->display_position) AND trim($request->display_position) == '') ?  999999 : $request->display_position;		
		
		$brand->display_on_home 	=  $request->display_on_home;
		$brand->display_on_category 	=  $request->display_on_category;
		$brand->is_popular 	=  $request->is_popular;

		$brand->status 	=  $request->status;
		
		$brand->meta_title 	=  $request->meta_title;
		$brand->meta_keywords 	=  $request->meta_keywords;
		$brand->meta_description 	=  $request->meta_description;
		
		if($brand->save()) {
			$frontmenu = Frontmenu::where('brand_id', $brand->brand_id)->get();
			
			if($frontmenu->isNotEmpty()){
				Cache::forget('getAllCategories_cache');
				$Name = remove_special_chars($brand->brand_name);
				$brand_id = remove_special_chars($brand->brand_id);
				$brand_url = config('const.SITE_URL').'/brand/'.$Name.'/brid/'.$brand_id;
				$frontmenu = Frontmenu::where('brand_id', $brand_id)->update(['menu_link'=>$brand_url]);
				Cache::forget('menu_array');
				Cache::forget('menu_array_common');
			}
			if ($request->hasFile('brand_logo_image')) {
				if ($request->file('brand_logo_image')->isValid()) {
					
					if(!file_exists(config('const.BRAND_IMAGE_PATH'))) {
						File::makeDirectory(config('const.BRAND_IMAGE_PATH'), $mode = 0777, true, true);
					}
					
					$image = $request->file('brand_logo_image');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = strtolower(clearSpecialCharacters($original_filename))."_".$rand_num."_".$brand->brand_id.".".$image->getClientOriginalExtension();

					$image_name = $original_filename; //site_slug($brand->brand_name).'_'.$brand->brand_id.'_th'.'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.BRAND_IMAGE_PATH');
					$res = $image->move($destination_path, $image_name);
					
					$orig_saved_file_path = $destination_path.'/'.$image_name;
					// $image_resize = Image::make($orig_saved_file_path);  
					// $image_resize->resize(config('const.BRAND_IMAGE_THUMB_WIDTH'), config('const.BRAND_IMAGE_THUMB_HEIGHT'));
					// $image_resize->save($orig_saved_file_path);					
					// if(isset($brand->brand_logo_image) && !empty($brand->brand_logo_image)) {
                    //     $request->actType =  'delete_image';
					// 	$request->type = 'brand_logo_image';
					// 	$request->id =  $brand->brand_id;
					// 	$request->subtype = 'brand_logo_image'; 
					// 	$request->image_name = $brand->brand_logo_image;
                    //     $this->deleteImage($request);
					// }	 

					$brand = Brand::find($brand->brand_id);
					$brand->brand_logo_image = $image_name;
					$brand->save();
				}
			}
			
			if ($request->hasFile('brand_banner_image')) {
				if ($request->file('brand_banner_image')->isValid()) {
					
					if(!file_exists(config('const.BRAND_IMAGE_PATH'))) {
						File::makeDirectory(config('const.BRAND_IMAGE_PATH'), $mode = 0777, true, true);
					}
					
					$image = $request->file('brand_banner_image');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = strtolower(clearSpecialCharacters($original_filename))."_".$rand_num."_".$brand->brand_id.".".$image->getClientOriginalExtension();

					$image_name = $original_filename; //site_slug($brand->brand_name).'_'.$brand->brand_id.'_th'.'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.BRAND_IMAGE_PATH');
					$res = $image->move($destination_path, $image_name);
					
					$orig_saved_file_path = $destination_path.'/'.$image_name;
					// $image_resize = Image::make($orig_saved_file_path);  
					// $image_resize->resize(config('const.BRAND_IMAGE_THUMB_WIDTH'), config('const.BRAND_IMAGE_THUMB_HEIGHT'));
					// $image_resize->save($orig_saved_file_path);					
					// if(isset($brand->brand_banner_image) && !empty($brand->brand_banner_image)) {
                    //     $request->actType =  'delete_image';
					// 	$request->type = 'brand_banner_image';
					// 	$request->id =  $brand->brand_id;
					// 	$request->subtype = 'brand_banner_image'; 
					// 	$request->image_name = $brand->brand_banner_image;
                    //     $this->deleteImage($request);
					// }	 

					$brand = Brand::find($brand->brand_id);
					$brand->brand_banner_image = $image_name;
					$brand->save();
				}
			}
			
			if($actType == 'add') {
				session()->flash('site_common_msg', config('messages.msg_add'));
			} else {
				session()->flash('site_common_msg', config('messages.msg_update')); 
			}
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_add_err'));
		}
		return redirect()->route('pnkpanel.brand.edit', $brand->brand_id);
	}
	
	public function delete($id) {
		if($this->deleteBrand($id)) {
			session()->flash('site_common_msg', config('messages.msg_delete')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 
		}
		return redirect()->route('pnkpanel.brand.list');
	}
	
	private function deleteBrand($id) {
		Brand::where('brand_id', $id)->delete();
		return true;
	}
	
	
	// public function bulkDelete(Request $request) {
	// 	$success = false;
	// 	$errors = [];
	// 	$messages = [];
	// 	$response_http_code = 400;

	// 	$actType = $request->actType;
	// 	if($actType == 'delete')
	// 	{
	// 		$id_str = $request->ids;
	// 		if(empty($id_str))	
	// 		{
	// 			$success = false;
	// 			$errors = ["message" => ["Please select record(s) to Delete."]];
	// 			$messages = [];
	// 			$response_http_code = 400;
	// 		} else {
	// 			$id_arr = explode(",", $id_str);
				
	// 			$reccount = 0;
	// 			for($i = 0; $i <= count($id_arr); $i++)
	// 			{
	// 				$brand_id = $id_arr[$i]??'';
	// 				if( !empty($brand_id) and $brand_id != "" )
	// 				{
	// 					$this->deleteBrandTree($brand_id);
	// 					$reccount++;
	// 				}
	// 			}
	// 			if($reccount) {
	// 				$success = true;
	// 				$errors = [];
	// 				$messages = ["message" => [config("messages.msg_delete")]];
	// 				$response_http_code = 200;
	// 			} else {
	// 				$success = false;
	// 				$errors = ["message" => [config("messages.msg_delete_err")]];
	// 				$messages = [];
	// 				$response_http_code = 400;
	// 			}
	// 		}







	// 	}
	// 	return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	// }
	
	public function deleteImage(Request $request) {
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;
		
		$actType = $request->actType;
		
		if(in_array($actType, ['delete_image']))
		{
			//echo $request->type; exit;
			$destination_path = '';
			$destination_landing_path = '';
			if($request->type == 'brand_logo_image') {
				$destination_path = config('const.BRAND_IMAGE_PATH');
			} 
			if($request->type == 'brand_banner_image') {
				$destination_path = config('const.BRAND_IMAGE_PATH');
			} 
			
			//echo config('const.BRAND_IMAGE_PATH').$request->image_name; exit;
			$image_name = $request->image_name;
			//echo $destination_path.$request->image_name; exit;
			
			//echo $request->subtype; exit;
			if(File::delete($destination_path.$image_name)) {
				
				$brand = Brand::find($request->id);

				if($request->subtype == 'brand_logo_image') {
					$brand->brand_logo_image = NULL;
				}
				if($request->subtype == 'brand_banner_image') {
					$brand->brand_banner_image = NULL;
				}
				$brand->save();
				
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_delete_image")]];
				$response_http_code = 200;
			} else {
				$success = false;
				$errors = ["message" => [config("messages.msg_delete_image_err")]];
				$messages = [];
				$response_http_code = 400;
			}
	
		}
		//dd($messages);
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}

	
}
