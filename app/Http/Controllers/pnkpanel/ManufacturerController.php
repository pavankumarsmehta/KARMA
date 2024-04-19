<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manufacturer;
use App\Models\ProductsBrand;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;
use File;

class ManufacturerController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return Manufacturer::class;
    }
    
    public function list(Request $request) {
		
		if (request()->ajax()) {
            $model = Manufacturer::select('*');
            $table = DataTables::eloquent($model);
            $table->addIndexColumn();
            $table->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->manufacturer_id . '" />';
            });
            $table->editColumn('manufacturer_name', function ($row) {
               	return "<a href=" . URL('/') . '/pnkpanel/manufacturer/edit/' . $row->manufacturer_id."". ">" . $row->manufacturer_name ."". "</a>";
				
            });
            $table->editColumn('status', function ($row) {
                return ($row->status ? 'Active' : 'Inactive');
            });
            $table->editColumn('action', function ($row) {
                $action = (string)view('pnkpanel.component.datatable_action', ['id' => $row->manufacturer_id]);
                return $action;
            });
            $table->rawColumns(['checkbox', 'action', 'manufacturer_name']);
            return $table->make(true);
        }

        $pageData['page_title'] = "Manufacturer Page";
        $pageData['meta_title'] = "Manufacturer Page";
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Manufacturer Page',
                'url' => route('pnkpanel.manufacturer.list')
            ]
        ];
        return view('pnkpanel.manufacturer.list')->with($pageData);
	}
	
	public function edit($id = 0) {
        if($id > 0) {
			$manufacturer = Manufacturer::findOrFail($id);
		} else {
			$manufacturer =  new Manufacturer;
		}
		
		$prefix = ($id > 0 ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix.' Manufacturer';
		$pageData['meta_title'] = $prefix.' Manufacturer';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Manufacturer List',
				 'url' =>route('pnkpanel.manufacturer.list')
			 ],
			 [
				 'title' => $prefix.' Manufacturer',
				 'url' =>route('pnkpanel.manufacturer.edit', $id)
			 ]
		];
		
        return view('pnkpanel.manufacturer.edit', compact('manufacturer'))->with($pageData);
    }
	
    public function update(Request $request) {
		
		//dd($request);
		$actType = $request->actType;
		$manufacturer_id = $request->manufacturer_id;
		$is_delete = $request->is_delete;
		$del_chk = $request->del_chk;
		$del_chk_arr = explode(",",$del_chk);
		
		if($actType == 'add') {
			$check_duplicate = Manufacturer::where('manufacturer_name', $request->manufacturer_name)->first();
		} else {
			$check_duplicate = Manufacturer::where('manufacturer_name', $request->manufacturer_name)->where('manufacturer_id', '<>', $manufacturer_id)->first();
		}
		if($check_duplicate)
		{
			session()->flash('site_common_msg_err', 'Manufacturer name already exists. Please change it');
			return redirect()->route('pnkpanel.manufacturer.edit', $manufacturer_id);
		}

		$this->validate($request, [
			'manufacturer_name'	=> 'required|string'
		]);
		
		if($request->display_position) {
			$this->validate($request, [
				'display_position'	=> 'numeric'
			]);
		}
		
		if ($request->hasFile('manufacturer_logo_image')) {
			if ($request->file('manufacturer_logo_image')->isValid()) {
				$data = $this->validate($request, [
					'manufacturer_logo_image' => 'required|mimes:jpeg,png,webp,jpg',
				]);
			}
		}
		
		if ($request->hasFile('manufacturer_page_header_image')) {
			if ($request->file('manufacturer_page_header_image')->isValid()) {
				$data = $this->validate($request, [
					'manufacturer_page_header_image' => 'required|mimes:jpeg,png,webp,jpg',
				]);
			}
		}
				
		$manufacturer = Manufacturer::findOrNew($manufacturer_id);
		$manufacturer->manufacturer_name =  $request->manufacturer_name;
		$manufacturer->manufacturer_description 	=  $request->manufacturer_description;
		$manufacturer->display_position 	=  (empty($request->display_position) AND trim($request->display_position) == '') ?  999999 : $request->display_position;		
		
		$manufacturer->status 	=  $request->status;
		
		$manufacturer->meta_title 	=  $request->meta_title;
		$manufacturer->meta_keywords 	=  $request->meta_keywords;
		$manufacturer->meta_description 	=  $request->meta_description;
		
		if($manufacturer->save()) {
			
			if ($request->hasFile('manufacturer_logo_image')) {
				if ($request->file('manufacturer_logo_image')->isValid()) {
					
					if(!file_exists(config('const.MANUFACTURER_IMAGE_PATH'))) {
						File::makeDirectory(config('const.MANUFACTURER_IMAGE_PATH'), $mode = 0777, true, true);
					}
					
					$image = $request->file('manufacturer_logo_image');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = strtolower(clearSpecialCharacters($original_filename))."_".$rand_num."_".$manufacturer->manufacturer_id.".".$image->getClientOriginalExtension();

					$image_name = $original_filename; //site_slug($brand->brand_name).'_'.$brand->brand_id.'_th'.'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.MANUFACTURER_IMAGE_PATH');
					$res = $image->move($destination_path, $image_name);
					
					$orig_saved_file_path = $destination_path.'/'.$image_name;
					// $image_resize = Image::make($orig_saved_file_path);  
					// $image_resize->resize(config('const.BRAND_IMAGE_THUMB_WIDTH'), config('const.BRAND_IMAGE_THUMB_HEIGHT'));
					// $image_resize->save($orig_saved_file_path);					
					// if(isset($manufacturer->manufacturer_logo_image) && !empty($manufacturer->manufacturer_logo_image)) {
                    //     $request->actType =  'delete_image';
					// 	$request->type = 'manufacturer_logo_image';
					// 	$request->id =  $manufacturer->manufacturer_id;
					// 	$request->subtype = 'manufacturer_logo_image'; 
					// 	$request->image_name = $manufacturer->manufacturer_logo_image;
                    //     $this->deleteImage($request);
					// }	 
					$manufacturer = Manufacturer::find($manufacturer->manufacturer_id);
					$manufacturer->manufacturer_logo_image = $image_name;
					$manufacturer->save();
				}
			}
			
			if ($request->hasFile('manufacturer_page_header_image')) {
				if ($request->file('manufacturer_page_header_image')->isValid()) {
					
					if(!file_exists(config('const.MANUFACTURER_IMAGE_PATH'))) {
						File::makeDirectory(config('const.MANUFACTURER_IMAGE_PATH'), $mode = 0777, true, true);
					}
					
					$image = $request->file('manufacturer_page_header_image');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = strtolower(clearSpecialCharacters($original_filename))."_".$rand_num."_".$manufacturer->manufacturer_id.".".$image->getClientOriginalExtension();

					$image_name = $original_filename; //site_slug($brand->brand_name).'_'.$brand->brand_id.'_th'.'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.MANUFACTURER_IMAGE_PATH');
					$res = $image->move($destination_path, $image_name);
					
					$orig_saved_file_path = $destination_path.'/'.$image_name;
					// $image_resize = Image::make($orig_saved_file_path);  
					// $image_resize->resize(config('const.BRAND_IMAGE_THUMB_WIDTH'), config('const.BRAND_IMAGE_THUMB_HEIGHT'));
					// $image_resize->save($orig_saved_file_path);					
					// if(isset($manufacturer->manufacturer_page_header_image) && !empty($manufacturer->manufacturer_page_header_image)) {
                    //     $request->actType =  'delete_image';
					// 	$request->type = 'manufacturer_page_header_image';
					// 	$request->id =  $manufacturer->manufacturer_id;
					// 	$request->subtype = 'manufacturer_page_header_image'; 
					// 	$request->image_name = $manufacturer->manufacturer_page_header_image;
                    //     $this->deleteImage($request);
					// }	 
					$manufacturer = Manufacturer::find($manufacturer->manufacturer_id);
					$manufacturer->manufacturer_page_header_image = $image_name;
					$manufacturer->save();
				}
			}
			if ($request->hasFile('manufacturer_page_mobile_header_image')) {
				if ($request->file('manufacturer_page_mobile_header_image')->isValid()) {
					
					if(!file_exists(config('const.MANUFACTURER_IMAGE_PATH'))) {
						File::makeDirectory(config('const.MANUFACTURER_IMAGE_PATH'), $mode = 0777, true, true);
					}
					
					$image = $request->file('manufacturer_page_mobile_header_image');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = strtolower(clearSpecialCharacters($original_filename))."_".$rand_num."_".$manufacturer->manufacturer_id.".".$image->getClientOriginalExtension();

					$image_name = $original_filename; //site_slug($brand->brand_name).'_'.$brand->brand_id.'_th'.'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.MANUFACTURER_IMAGE_PATH');
					$res = $image->move($destination_path, $image_name);
					
					$orig_saved_file_path = $destination_path.'/'.$image_name;
					// $image_resize = Image::make($orig_saved_file_path);  
					// $image_resize->resize(config('const.BRAND_IMAGE_THUMB_WIDTH'), config('const.BRAND_IMAGE_THUMB_HEIGHT'));
					// $image_resize->save($orig_saved_file_path);					
					// if(isset($manufacturer->manufacturer_page_mobile_header_image) && !empty($manufacturer->manufacturer_page_mobile_header_image)) {
                    //     $request->actType =  'delete_image';
					// 	$request->type = 'manufacturer_page_mobile_header_image';
					// 	$request->id =  $manufacturer->manufacturer_id;
					// 	$request->subtype = 'manufacturer_page_mobile_header_image'; 
					// 	$request->image_name = $manufacturer->manufacturer_page_mobile_header_image;
                    //     $this->deleteImage($request);
					// }	 
					$manufacturer = Manufacturer::find($manufacturer->manufacturer_id);
					$manufacturer->manufacturer_page_mobile_header_image = $image_name;
					$manufacturer->save();
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
		return redirect()->route('pnkpanel.manufacturer.edit', $manufacturer->manufacturer_id);
	}
	
	public function delete($id) {
		if($this->deleteManufacturer($id)) {
			session()->flash('site_common_msg', config('messages.msg_delete')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 
		}
		return redirect()->route('pnkpanel.manufacturer.list');
	}
	
	private function deleteManufacturer($id) {
		Manufacturer::where('manufacturer_id', $id)->delete();
		return true;
	}
	
	

	
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
			if($request->type == 'manufacturer_logo_image') {
				$destination_path = config('const.MANUFACTURER_IMAGE_PATH');
			} 
			if($request->type == 'manufacturer_page_header_image') {
				$destination_path = config('const.MANUFACTURER_IMAGE_PATH');
			} 

			if($request->type == 'manufacturer_page_mobile_header_image') {
				$destination_path = config('const.MANUFACTURER_IMAGE_PATH');
			} 
			//echo config('const.MANUFACTURER_IMAGE_PATH').$request->image_name; exit;
			$image_name = $request->image_name;
			//echo $destination_path.$request->image_name; exit;
			
			//echo $request->subtype; exit;
			if(File::delete($destination_path.$image_name)) {
				
				$manufacturer = Manufacturer::find($request->id);

				if($request->subtype == 'manufacturer_logo_image') {
					$manufacturer->manufacturer_logo_image = NULL;
				}
				if($request->subtype == 'manufacturer_page_header_image') {
					$manufacturer->manufacturer_page_header_image = NULL;
				}
				if($request->subtype == 'manufacturer_page_mobile_header_image') {
					$manufacturer->manufacturer_page_mobile_header_image = NULL;
				}
				$manufacturer->save();
				
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
