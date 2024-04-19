<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ProductsCategory;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;
use File;
use App\Models\Frontmenu;
use Cache;

class CategoryController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return Category::class;
    }
    
    public function list(Request $request) {
		
		if(request()->ajax()) {
			$draw = $request->get('draw');
			$start = $request->get("start");
			$rowperpage = $request->get("length");

			$columnIndex_arr = $request->get('order');
			$columnName_arr = $request->get('columns');
			$order_arr = $request->get('order');
			$search_arr = $request->get('search');
			
			$columnName = 'category_id';
			if(isset($columnIndex_arr)) {
				$columnIndex = $columnIndex_arr[0]['column'];
				$columnName = $columnName_arr[$columnIndex]['data'];
			}
			
			$columnSortOrder = $order_arr[0]['dir'] ?? 'asc';
			$searchValue = $search_arr['value'] ?? $columnName_arr[1]['search']['value'];

			$where = [];
			if(isset($searchValue) && $searchValue != '') {
				$where[] = ['category_name', 'like', '%' . $searchValue . '%'];
			}
			$totalRecords = Category::select('count(*) as allcount')->count();
			$totalRecordswithFilter = Category::select('count(*) as allcount')
				->orWhere($where)
				->count();
				
			$data_arr = array();
			if(isset($searchValue) && $searchValue != '') {
				$records = Category::where($where)->with('childrenRecursive')->get();
			} else {
				$records = Category::where('parent_id', '=', '0')->with('childrenRecursive')->get();
			}
			$data_arr = self::getCategoryTreeGridData($records, 0, $columnName, $columnSortOrder);


			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $totalRecords,
				"iTotalDisplayRecords" => $totalRecordswithFilter,
				"aaData" => $data_arr
			);

			echo json_encode($response);
			exit;
		}
		
		$pageData['page_title'] = "Category List";
		$pageData['meta_title'] = "Category List";
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Category List',
				 'url' =>route('pnkpanel.category.list')
			 ]
		];
		
		return view('pnkpanel.category.list')->with($pageData);
	}
	
	public function edit($id = 0) {
        if($id > 0) {
			$category = Category::findOrFail($id);
			if($category->template_page=="category_list")
			{ 
				/*if($category->parent_id==0)
				{
					$catviewpage=config('const.SITE_URL').'/'.remove_special_chars(trim($category->category_name)).'.'.'html';
				}*/
				$catviewpage=config('const.SITE_URL').'/'.remove_special_chars(trim($category->category_name)).'.'.'html';
				
			}
			else if($category->template_page=="product_list"){
				
				if($category->parent_id==0)
				{
				 $catviewpage=config('const.SITE_URL').'/'.remove_special_chars(trim($category->category_name)) . '/cid/' . $category->category_id;
				}
				else
				{
				$parent_catname = Category::where('category_id', $category->parent_id)->get();
				//dd($parent_catname[0]->category_name);
				$catviewpage=config('const.SITE_URL').'/'.remove_special_chars(trim($parent_catname[0]->category_name)).'/'.remove_special_chars(trim($category->category_name)) . '/cid/' . $category->category_id;
				}
				
			}
		} else {
			$category =  new Category;
			$catviewpage='';
		}
		
		$prefix = ($id > 0 ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix.' Category';
		$pageData['meta_title'] = $prefix.' Category';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Category List',
				 'url' =>route('pnkpanel.category.list')
			 ],
			 [
				 'title' => $prefix.' Category',
				 'url' =>route('pnkpanel.category.edit', $id)
			 ]
		];
		
        return view('pnkpanel.category.edit', compact('category','catviewpage'))->with($pageData);;
    }
	
    public function update(Request $request) {
		//dd($request->all());
		
		$actType = $request->actType;
		$category_id = $request->category_id;
		$is_delete = $request->is_delete;
		$del_chk = $request->del_chk;
		$del_chk_arr = explode(",",$del_chk);
		
		if($actType == 'add') {
			$check_duplicate = Category::where('category_name', $request->category_name)->where('parent_id', $request->parent_id)->first();
		} else {
			$check_duplicate = Category::where('category_name', $request->category_name)->where('parent_id', $request->parent_id)->where('category_id', '<>', $category_id)->first();
		}
		if($check_duplicate)
		{
			session()->flash('site_common_msg_err', 'Category name already exists. Please change it');
			return redirect()->route('pnkpanel.category.edit', $category_id);
		}

		$this->validate($request, [
			'category_name'	=> 'required|string'
		]);
		
		if($request->display_position) {
			$this->validate($request, [
				'display_position'	=> 'numeric'
			]);
		}
		
		// Added code for Category Tile Images as on 06-10-2023 Start
		// $shop_count = $request->shop_count;
		// //echo $shop_count; exit;
		// if($shop_count > 0)
		// {
		// 	$no_data = array();
		// 	$category_beauty_json = array();
		// 	$flag = "false";
		// 	$del_key = '';

		// 	for($i=0;$i<$shop_count;$i++){

		// 		if(isset($_POST['shop_title'.$i]) && $_POST['shop_title'.$i] == ""){
		// 			$no_data[] = "yes";
		// 		}

		// 		if(isset($_POST['shop_rank'.$i]) && $_POST['shop_rank'.$i] == ""){
		// 			$no_data[] = "yes";
		// 		}

		// 		if(isset($_POST['shop_link'.$i]) && $_POST['shop_link'.$i] == ""){
		// 			$no_data[] = "yes";
		// 		}

		// 		if(isset($_POST['shop_status'.$i]) && $_POST['shop_status'.$i] == ""){
		// 			$no_data[] = "yes";
		// 		}
				
		// 		if ($_POST['shop_title'.$i]) {
		// 			$this->validate($request, [
		// 				'shop_title'.$i	=> 'required|string'
		// 			]);
		// 		}

		// 		if ($_POST['shop_link'.$i]) {
		// 			$this->validate($request, [
		// 				'shop_link'.$i	=> 'required|string'
		// 			]);
		// 		}

		// 		if ($_POST['shop_rank'.$i]) {
		// 			$this->validate($request, [
		// 				'shop_rank'.$i	=> 'required|numeric'
		// 			]);
		// 		}

		// 		if ($_POST['shop_status'.$i]) {
		// 			$this->validate($request, [
		// 				'shop_status'.$i	=> 'required|numeric'
		// 			]);
		// 		}

		// 		$image_name = "";
		// 		if ($request->hasFile('shop_image'.$i)) {
		// 			if ($request->file('shop_image'.$i)->isValid()) {
						
		// 				if(!file_exists(config('const.CAT_BEAUTY_IMAGE_PATH'))) {
		// 					File::makeDirectory(config('const.CAT_BEAUTY_IMAGE_PATH'), $mode = 0777, true, true);
		// 				}
						
		// 				$image = $request->file('shop_image'.$i);

		// 				$image_name = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName()).'_'.strtotime("now").".".$image->getClientOriginalExtension();
		// 				$image_name = str_replace(" ","_",$image_name);

		// 				$destination_path = config('const.CAT_BEAUTY_IMAGE_PATH');
		// 				$res = $image->move($destination_path, $image_name);
						
		// 				$orig_saved_file_path = $destination_path.'/'.$image_name;
		// 				$image_resize = Image::make($orig_saved_file_path);  
		// 				$image_resize->resize(config('const.CAT_IMAGE_BEAUTY_WIDTH'), config('const.CAT_IMAGE_BEAUTY_HEIGHT'));
		// 				$image_resize->save($orig_saved_file_path);
		// 			}
		// 		}else{
		// 			if(isset($_POST['old_shop_image'.$i]) && $_POST['old_shop_image'.$i] != ""){
		// 				$image_name = $_POST['old_shop_image'.$i];
		// 			}
		// 		}

		// 		if(isset($_POST['shop_chkbox'.$i]) && $_POST['shop_chkbox'.$i] != "" && $is_delete == "yes"){
		// 			$flag = "true";
		// 			$del_key = $_POST['shop_chkbox'.$i];
		// 		}
				
		// 		$Insert_Date = date('d M y');
		// 		//echo $Insert_Date; exit;
		// 		$category_beauty_json[] = '{"category_name": "'.$_POST['shop_title'.$i].'","category_beauty_image": "'.$image_name.'","category_link": "'.$_POST['shop_link'.$i].'","display_position": "'.$_POST['shop_rank'.$i].'","category_status": "'.$_POST['shop_status'.$i].'","Insert_Date": "'.$Insert_Date.'"}';
		// 	}
			
		// 	//echo $category_beauty_json; exit;
		// 	$category_beauty_json = "[".implode(",", $category_beauty_json)."]";

		// 	if($flag == "true" && count($del_chk_arr) > 0){
		// 		$data = json_decode($category_beauty_json, true);

		// 		foreach ($del_chk_arr as $del_value) {
		// 			unset($data[$del_value]);
		// 		}

		// 		$data = array_values($data);

		// 		$category_beauty_json = json_encode($data);
		// 	}

		// }else{
		// 	$category_beauty_json = "";
		// }
		$category_beauty_json = "";
		// Added code for Category Tile Images as on 06-10-2023 End
				
		$category = Category::findOrNew($category_id);
		$category->parent_id 	=  $request->parent_id;
		$category->category_name =  $request->category_name;
		$category->category_description 	=  $request->category_description;
		
		if ($request->hasFile('thumb_image')) {
			if ($request->file('thumb_image')->isValid()) {
				$data = $this->validate($request, [
					'thumb_image' => 'required|mimes:jpeg,png,webp,jpg',
				]);
			}
		}else{
			if(($request->display_on_home == 'Yes' || $request->display_on_category == 'Yes') && (empty($category->thumb_image) || is_null($category->thumb_image))){
				$this->validate($request, [
					'thumb_image'	=> 'required'
				]);
		    }
		}
		if ($request->hasFile('banner_image')) {
			if ($request->file('banner_image')->isValid()) {
				$data = $this->validate($request, [
					'banner_image' => 'required|mimes:jpeg,png,webp,jpg',
				]);
			}
		}
		if ($request->hasFile('promotion_banner_image')) {
			if ($request->file('promotion_banner_image')->isValid()) {
				$data = $this->validate($request, [
					'promotion_banner_image' => 'required|mimes:jpeg,png,webp,jpg',
				]);
			}
		}
		/*$category->is_topmenu 	=  $request->is_topmenu ?? 'No';
		$category->display_menu_position 	=  (empty($request->display_menu_position) AND trim($request->display_menu_position) == '') ?  99999 : $request->display_menu_position;
		$category->is_sub_cat 	=  $request->is_sub_cat ?? 'No';*/
		$category->display_position 	=  (empty($request->display_position) AND trim($request->display_position) == '') ?  99999 : $request->display_position;		
		
		$category->template_page 	=  $request->template_page ?? 'product_list';
		$category->status 	=  $request->status;
		
		$category->advertisement_title1 	=  $request->advertisement_title1;
		$category->advertisement_link1 		=  $request->advertisement_link1;
		
		$category->advertisement_title2 	=  $request->advertisement_title2;
		$category->advertisement_link2 		=  $request->advertisement_link2;
		
		$category->meta_title 	=  $request->meta_title;
		$category->meta_keywords 	=  $request->meta_keywords;
		$category->meta_description 	=  $request->meta_description;

		$category->display_on_category 	=  $request->display_on_category;
		$category->display_on_other_category 	=  $request->display_on_other_category;
		$category->display_on_home 	=  $request->display_on_home;
		$category->category_beauty_json 	=  $category_beauty_json;
		$category->banner_image_link 	=  $request->banner_image_link;
		$category->promotion_image_link 	=  $request->promotion_image_link;
		$category->promotion_title 	=  $request->promotion_title;
		$category->promotion_text 	=  $request->promotion_text;
		
		$category->banner_position 	=  $request->banner_position;
		
		if($category->save()) {
			$frontmenu = Frontmenu::where('category_id', $category->category_id)->get();
			
			if($frontmenu->isNotEmpty()){
				
				Cache::forget('getAllCategories_cache');
				$cat_url = self::getCatURL($category->category_id);
				//dd($cat_url);
				$frontmenu = Frontmenu::where('category_id', $category->category_id)->update(['menu_link'=>$cat_url]);
				Cache::forget('menu_array');
				Cache::forget('menu_array_common');
			}

			if ($request->hasFile('thumb_image')) {
				if ($request->file('thumb_image')->isValid()) {
					
					if(!file_exists(config('const.CAT_IMAGE_PATH'))) {
						File::makeDirectory(config('const.CAT_IMAGE_PATH'), $mode = 0777, true, true);
					}
					
					$image = $request->file('thumb_image');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = clearSpecialCharacters($original_filename)."_".$rand_num."_".$category->category_id.".".$image->getClientOriginalExtension();

					$image_name = $original_filename; //site_slug($category->category_name).'_'.$category->category_id.'_th'.'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.CAT_IMAGE_PATH');
					$res = $image->move($destination_path, $image_name);
					
					$orig_saved_file_path = $destination_path.'/'.$image_name;
					// $image_resize = Image::make($orig_saved_file_path);  
					// $image_resize->resize(config('const.CAT_IMAGE_THUMB_WIDTH'), config('const.CAT_IMAGE_THUMB_HEIGHT'));
					// $image_resize->save($orig_saved_file_path);					
					// if(isset($category->thumb_image) && !empty($category->thumb_image)) {
                    //     $request->actType =  'delete_image';
					// 	$request->type = 'category_image';
					// 	$request->id =  $category->category_id;
					// 	$request->subtype = 'thumb_image'; 
					// 	$request->image_name = $category->thumb_image;
                    //     $this->deleteImage($request);
					// }
					$category = Category::find($category->category_id);
					$category->thumb_image = $image_name;
					$category->save();
				}
			}
			if ($request->hasFile('banner_image')) {
				if ($request->file('banner_image')->isValid()) {
					
					if(!file_exists(config('const.CAT_BANNER_PATH'))) {
						File::makeDirectory(config('const.CAT_BANNER_PATH'), $mode = 0777, true, true);
					}
					
					$image = $request->file('banner_image');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = clearSpecialCharacters($original_filename)."_".$rand_num."_".$category->category_id.".".$image->getClientOriginalExtension();

					$banner_image_name = $original_filename; //site_slug($category->category_name).'_'.$category->category_id.'_th'.'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.CAT_BANNER_PATH');
					$res = $image->move($destination_path, $banner_image_name);
					
					$orig_saved_file_path = $destination_path.'/'.$banner_image_name;
					// $image_resize = Image::make($orig_saved_file_path);  
					// $image_resize->resize(config('const.CAT_IMAGE_THUMB_WIDTH'), config('const.CAT_IMAGE_THUMB_HEIGHT'));
					// $image_resize->save($orig_saved_file_path);					
					// if(isset($category->banner_image) && !empty($category->banner_image)) {
                    //     $request->actType =  'delete_image';
					// 	$request->type = 'category_banner';
					// 	$request->id =  $category->category_id;
					// 	$request->subtype = 'banner_image'; 
					// 	$request->image_name = $category->banner_image;
                    //     $this->deleteImage($request);
					// }
					$category = Category::find($category->category_id);
					$category->banner_image = $banner_image_name;
					$category->save();
				}
			}
			if ($request->hasFile('promotion_banner_image')) {
				if ($request->file('promotion_banner_image')->isValid()) {
					
					if(!file_exists(config('const.CAT_PROMOTION_PATH'))) {
						File::makeDirectory(config('const.CAT_PROMOTION_PATH'), $mode = 0777, true, true);
					}
					
					$image = $request->file('promotion_banner_image');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = clearSpecialCharacters($original_filename)."_".$rand_num."_".$category->category_id.".".$image->getClientOriginalExtension();

					$banner_image_name = $original_filename; //site_slug($category->category_name).'_'.$category->category_id.'_th'.'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.CAT_PROMOTION_PATH');
					$res = $image->move($destination_path, $banner_image_name);
					
					$orig_saved_file_path = $destination_path.'/'.$banner_image_name;
					// $image_resize = Image::make($orig_saved_file_path);  
					// $image_resize->resize(config('const.CAT_IMAGE_THUMB_WIDTH'), config('const.CAT_IMAGE_THUMB_HEIGHT'));
					// $image_resize->save($orig_saved_file_path);					
					// if(isset($category->promotion_banner_image) && !empty($category->promotion_banner_image)) {
                    //     $request->actType =  'delete_image';
					// 	$request->type = 'promotion_banner_image';
					// 	$request->id =  $category->category_id;
					// 	$request->subtype = 'promotion_banner_image'; 
					// 	$request->image_name = $category->promotion_banner_image;
                    //     $this->deleteImage($request);
					// }
					$category = Category::find($category->category_id);
					$category->promotion_banner_image = $banner_image_name;
					$category->save();
				}
			}
			
			if ($request->hasFile('advertisement_image1')) {
				if ($request->file('advertisement_image1')->isValid()) {
					
					if(!file_exists(config('const.CAT_ADS_IMAGE_PATH'))) {
						File::makeDirectory(config('const.CAT_ADS_IMAGE_PATH'), $mode = 0777, true, true);
					}
					
					$image = $request->file('advertisement_image1');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = clearSpecialCharacters($original_filename)."_".$rand_num."_".$category->category_id.".".$image->getClientOriginalExtension();

					//$image_name = site_slug($category->category_name).'_'.$category->category_id.'_th'.'.'.$image->getClientOriginalExtension();
					$image_name = $original_filename; //site_slug($category->category_name).'_'.$category->category_id.'_'.rand().'_ads'.'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.CAT_ADS_IMAGE_PATH');
					$res = $image->move($destination_path, $image_name);
					
					$orig_saved_file_path = $destination_path.'/'.$image_name;
					$image_resize = Image::make($orig_saved_file_path);  
					$image_resize->resize(config('const.CAT_ADS1_BANNER_WIDTH'), config('const.CAT_ADS_BANNER_HEIGHT'));
					$image_resize->save($orig_saved_file_path);
					// if(isset($category->advertisement_image1) && !empty($category->advertisement_image1)) {
                    //     $request->actType =  'delete_image';
					// 	$request->type = 'advertisement_image1';
					// 	$request->id =  $category->category_id;
					// 	$request->subtype = 'advertisement_image1'; 
					// 	$request->image_name = $category->advertisement_image1;
                    //     $this->deleteImage($request);
					// }

					$category = Category::find($category->category_id);
					$category->advertisement_image1 = $image_name;
					$category->save();
				}
			}
			
			if ($request->hasFile('advertisement_image2')) {
				if ($request->file('advertisement_image2')->isValid()) {
					
					if(!file_exists(config('const.CAT_ADS_IMAGE_PATH'))) {
						File::makeDirectory(config('const.CAT_ADS_IMAGE_PATH'), $mode = 0777, true, true);
					}
					
					$image = $request->file('advertisement_image2');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = clearSpecialCharacters($original_filename)."_".$rand_num."_".$category->category_id.".".$image->getClientOriginalExtension();

					$image_name = $original_filename; //site_slug($category->category_name).'_'.$category->category_id.'_'.rand().'_ads'.'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.CAT_ADS_IMAGE_PATH');
					$res = $image->move($destination_path, $image_name);
					
					$orig_saved_file_path = $destination_path.'/'.$image_name;
					$image_resize = Image::make($orig_saved_file_path);  
					$image_resize->resize(config('const.CAT_ADS1_BANNER_WIDTH'), config('const.CAT_ADS_BANNER_HEIGHT'));
					$image_resize->save($orig_saved_file_path);					
					// if(isset($category->advertisement_image2) && !empty($category->advertisement_image2)) {
                    //     $request->actType =  'delete_image';
					// 	$request->type = 'advertisement_image2';
					// 	$request->id =  $category->category_id;
					// 	$request->subtype = 'advertisement_image2'; 
					// 	$request->image_name = $category->advertisement_image2;
                    //     $this->deleteImage($request);
					// }
					$category = Category::find($category->category_id);
					$category->advertisement_image2 = $image_name;
					$category->save();
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
		return redirect()->route('pnkpanel.category.edit', $category->category_id);
	}
	
	public function delete($id) {
		if($this->deleteCategoryTree($id)) {
			session()->flash('site_common_msg', config('messages.msg_delete')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 
		}
		return redirect()->route('pnkpanel.category.list');
	}
	
	private function deleteCategoryTree($id) {
		Category::where('category_id', $id)->delete();
		ProductsCategory::where('category_id', $id)->delete();
		Category::where('category_id', $id)->delete();
		$this->deleteSubCategory($id);
		return true;
	}
	
	private function deleteSubCategory($id) {
		$catres = Category::where('parent_id', $id)->pluck('category_id')->toArray();
		if(count($catres) > 0)
		{	
			for($p=0;$p<count($catres);$p++)
			{
				$temp_category_id = $catres[$p];
				Category::where('category_id', $temp_category_id)->delete();
				ProductsCategory::where('category_id', $temp_category_id)->delete();
				Category::where('category_id', $temp_category_id)->delete();
				$this->deleteSubCategory($temp_category_id);
			}	
		}	
		return true;
	}
	
	public function bulkDelete(Request $request) {
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;

		$actType = $request->actType;
		if($actType == 'delete')
		{
			$id_str = $request->ids;
			if(empty($id_str))	
			{
				$success = false;
				$errors = ["message" => ["Please select record(s) to Delete."]];
				$messages = [];
				$response_http_code = 400;
			} else {
				$id_arr = explode(",", $id_str);
				
				$reccount = 0;
				for($i = 0; $i <= count($id_arr); $i++)
				{
					$category_id = $id_arr[$i]??'';
					if( !empty($category_id) and $category_id != "" )
					{
						$this->deleteCategoryTree($category_id);
						$reccount++;
					}
				}
				if($reccount) {
					$success = true;
					$errors = [];
					$messages = ["message" => [config("messages.msg_delete")]];
					$response_http_code = 200;
				} else {
					$success = false;
					$errors = ["message" => [config("messages.msg_delete_err")]];
					$messages = [];
					$response_http_code = 400;
				}
			}
		}
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}
	
	public function deleteImage(Request $request) {
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;
		
		$actType = $request->actType;
		
		if(in_array($actType, ['delete_image','banner_image']))
		{
			//echo $request->type; exit;
			$destination_path = '';
			$destination_landing_path = '';
			if($request->type == 'category_image') {
				$destination_path = config('const.CAT_IMAGE_PATH');
			}else if($request->type == 'category_banner'){
				$destination_path = config('const.CAT_BANNER_PATH');
			}else if($request->type == 'advertisement_image1'){
				$destination_path = config('const.CAT_ADS_IMAGE_PATH');
			}else if($request->type == 'advertisement_image2'){
				$destination_path = config('const.CAT_ADS_IMAGE_PATH');
			}  
			
			//echo config('const.CAT_IMAGE_PATH').$request->image_name; exit;
			$image_name = $request->image_name;
			//echo $request->image_name; exit;
			
			
			if(File::delete($destination_path.$image_name)) {
				
				$category = Category::find($request->id);

				if($request->subtype == 'thumb_image') {
					$category->thumb_image = NULL;
				}else if($request->subtype == 'banner_image'){
					$category->banner_image = NULL;
				}/*else if($request->subtype == 'advertisement_image1'){
					$category->advertisement_image1 = NULL;
				}else if($request->subtype == 'advertisement_image2'){
					$category->advertisement_image2 = NULL;
				}*/
				$category->save();
				
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

	public static function getCategoryTreeGridData($records = null, $level = 0, $columnName, $columnSortOrder) {
		$data_arr = array();
		if($columnSortOrder == 'asc') {
			$records = $records->sortBy($columnName);
		} else {
			$records = $records->sortByDesc($columnName);
		}
		foreach($records as $record){
			$catviewpage='';
			if($record->template_page=="category_list")
			{ 
				/*if($category->parent_id==0)
				{
					$catviewpage=config('const.SITE_URL').'/'.remove_special_chars(trim($category->category_name)).'.'.'html';
				}*/
				$catviewpage=config('const.SITE_URL').'/'.remove_special_chars(trim($record->category_name)).'.'.'html';
				
			}
			else if($record->template_page=="product_list"){
				
				if($record->parent_id==0)
				{
				 $catviewpage=config('const.SITE_URL').'/'.remove_special_chars(trim($record->category_name)) . '/cid/' . $record->category_id;
				}
				else
				{
				$parent_catname = Category::where('category_id', $record->parent_id)->get();
				$catviewpage=config('const.SITE_URL').'/'.remove_special_chars(trim($parent_catname[0]->category_name)).'/'.remove_special_chars(trim($record->category_name)) . '/cid/' . $record->category_id;
				}
				
			}
			if($record->parent_id == 0) {
				$level = 0;
			}
			$levelString = str_repeat("&emsp;&ensp;", $level);
			$checkbox = '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="'.$record->category_id.'" />';
			$category_id = $record->category_id;
			$parent_id = $record->parent_id;
			$category_name = $levelString . ' <a href="' . route("pnkpanel.category.edit", $record->category_id) . '">'  . ($record->parent_id == '0' ? '<strong>' : '') . '['  .( $level+1 ) . '] ' .  $record->category_name . ($record->parent_id == '0' ? '</strong>' : '') . '</a>';
			if($record->template_page=='category_list') {
				$template_page = "Category List";
			} elseif($record->template_page=='product_list') {
				$template_page = "Product List";
			} else {
				$template_page = $record->template_page;
			}
			$display_position = '<input type="text" id="display_position_'.$record->category_id.'" value="'.$record->display_position.'" class="form-control input-sm" size="8">';
			$status = ($record->status == '1' ? 'Active' : 'Inactive');
			$action = (string)view('pnkpanel.component.datatable_action', ['id' => $record->category_id,'cat_url' =>$catviewpage]);

			$data_arr[] = array(
				"checkbox" => $checkbox,
				"category_id" => $category_id,
				"parent_id" => $parent_id,
				"category_name" => $category_name,
				"template_page" => $template_page,
				"display_position" => $display_position,
				"status" => $status,
				"action" => $action
			);
			
			if($record->childrenRecursive && count($record->childrenRecursive)>0) {
				$data_arr = array_merge($data_arr, self::getCategoryTreeGridData($record->childrenRecursive, $level+1, $columnName, $columnSortOrder)); ;
			}
		}
		return $data_arr;
	}
	
	public static function drawCategoryTreeDropdown($records = null, $level = 0, $defaultSelectedCategoryId = '') {
		$html = array();
		foreach($records as $record){
			if($record->parent_id == 0) {
				$level = 0;
			}
			$levelString = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level).($level?"|".$level."|&nbsp;&nbsp;":"&raquo;&nbsp;");
			
			$html[] = "<option value=\"" . $record->category_id."\" " . ($record->category_id == $defaultSelectedCategoryId ? " selected" : "") . ">". $levelString . $record->category_name . "</option>";
			
			if($record->childrenRecursive && count($record->childrenRecursive)>0) {
				$html = array_merge($html, self::drawCategoryTreeDropdown($record->childrenRecursive, $level+1, $defaultSelectedCategoryId)); ;
			}
		}
		return $html;
	}

	public static function drawCategoryTreeDropdownWithLink($records = null, $level = 0, $defaultSelectedCategoryId = '') {
		$html = array();
		
		foreach($records as $record){
			$cat_full_url = self::getCatURL($record->category_id);
			if($record->parent_id == 0) {
				$level = 0;
			}
			$levelString = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level).($level?"|".$level."|&nbsp;&nbsp;":"&raquo;&nbsp;");
			$cat_url =replaceMultipleDashes(str_replace(" " ,"-",(strtolower($record->category_name))));
			$html[] = "<option data-url='$cat_full_url' data-parent_id='$record->parent_id' data-cat_url='".$cat_url."' value=\"" . $record->category_id."\" " . ($record->category_id == $defaultSelectedCategoryId ? " selected" : "") . ">". $levelString . $record->category_name . "</option>";
			
			if($record->childrenRecursive && count($record->childrenRecursive)>0) {
				$html = array_merge($html, self::drawCategoryTreeDropdownWithLink($record->childrenRecursive, $level+1, $defaultSelectedCategoryId)); ;
			}
		}
		return $html;
	}

	public static function getCatURL($category_id){
		$CatDetails = GetCatTree();
		if(isset($CatDetails['CatForProd'][$category_id]) && !empty($CatDetails['CatForProd'][$category_id])){
			$category_url = $CatDetails['CatForProd'][$category_id]['category_url'];
			$urlParts = explode('/', $category_url);
			$urlParts = array_map('replaceMultipleDashes', $urlParts);
			$newUrl = implode('/', $urlParts);
			return $newUrl;
		}else{
			return '';
		}	
	}
}
