<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeProducts;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;
use File;

class HomeProductsController extends Controller
{
    //
    use CrudControllerTrait;

    public function model()
    {
        return HomeProducts::class;
    }
    
	public function index(Request $request) {
		$result = HomeProducts::orderBy('home_title_id')->get();
		$pageData['page_title'] = 'Home Page Section';
		$pageData['meta_title'] = 'Home Page Section';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Home Page Section',
				 'url' =>route('pnkpanel.home-products.index')
			 ]
		];
		
        return view('pnkpanel.home-products.edit', compact('result'))->with($pageData);;
    }
	
    public function update(Request $request) {
		// $actType = $request->actType;
		HomeProducts::truncate();

		$Label_Count = 3;
		$save = 0;
		for($i=0;$i<=$Label_Count;$i++)
		{
			$home_products =  new HomeProducts;
			$home_products->title 	=  $request['title'.$i];
			$home_products->var_name 	=  strtoupper(preg_replace('/[^A-Za-z0-9\-]/','_',$request['title'.$i]));
			$home_products->text 	=  $request['text'.$i];
			$home_products->button_name 	=  $request['button_name'.$i];
			$home_products->link 	=  $request['link'.$i];
			$home_products->sku 	=  $request['sku'.$i];			
			$home_products->image_name 	=  $request['image_name_old'.$i];
			//$home_products->filterStartDate 	=  date("Y-m-d",strtotime($request['filterStartDate'.$i]));
			//$home_products->filterEndDate 	=  date("Y-m-d",strtotime($request['filterEndDate'.$i]));
			$home_products->image_name_mob 	=  $request['image_name_mob_old'.$i];
			if($i == 0)
			{ $home_products->home_flag = 'FREESHIPPING';}
			else if($i == 1)
			{ $home_products->home_flag = 'QUIZ';}
			else if($i == 2)
			{ $home_products->home_flag = 'ABOUT';}
		
			//$home_products->home_flag 	=  $request['image_name_mob_old'.$i];
			//echo date("Y-m-d",strtotime($request['filterStartDate'.$i]));
			if ($request->hasFile('image_name'.$i)) {
				
				if ($request->file('image_name'.$i)->isValid()) {
					
					$image = $request->file('image_name'.$i);

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = clearSpecialCharacters($original_filename)."_".$rand_num."_".$i.".".$image->getClientOriginalExtension();

					$image_name = $original_filename; //str_replace(' ','',$home_products->title).time().'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.SITE_IMAGES_PATH').'homeimg/';
					$res = $image->move($destination_path, $image_name);
					
					$orig_saved_file_path = $destination_path.$image_name;
					$image_resize = Image::make($orig_saved_file_path);  
					//$image_resize->resize(49, 49);
					$image_resize->save($orig_saved_file_path);

					$home_products->image_name = $image_name;					
				}
			}
			if ($request->hasFile('image_name_mob'.$i)) {
				
				if ($request->file('image_name_mob'.$i)->isValid()) {
					
					$image_mob = $request->file('image_name_mob'.$i);

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image_mob->getClientOriginalExtension(),"",$image_mob->getClientOriginalName());
					$original_filename = clearSpecialCharacters($original_filename)."_".$rand_num."_".$i.".".$image_mob->getClientOriginalExtension();

					$image_name_mob = $original_filename; 
					//echo $image_name_mob; exit;
					//str_replace(' ','',$home_products->title).time().'.'.$image_mob->getClientOriginalExtension();
					$destination_path = config('const.SITE_IMAGES_PATH').'homeimg/';
					$res = $image_mob->move($destination_path, $image_name_mob);
					
					$orig_saved_file_path = $destination_path.$image_name_mob;
					$image_resize = Image::make($orig_saved_file_path);  
					//$image_resize->resize(49, 49);
					$image_resize->save($orig_saved_file_path);

					$home_products->image_name_mob = $image_name_mob;					
				}
			}
			//if($i == 1)
				//dd($home_products);
			
			if($home_products->save()) {
				$save = 1;
			}
			//dd($save);
		}
		//exit;
		if($save == 1) {
			session()->flash('site_common_msg', config('messages.msg_update')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_add_err'));
		}
		return redirect()->route('pnkpanel.home-products.index');
	}
	
	public function deleteImage(Request $request) {
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;
		
		$actType = $request->actType;
		//echo config('const.SITE_IMAGES_PATH'); exit;
		if(in_array($actType, ['delete_image']))
		{
			//echo $request->type; exit;
			if($request->type == 'cat_img') {
				$image_name = $request->image_name;
				if(File::delete(config('const.SITE_IMAGES_PATH').'homeimg/' . $image_name))
				{
					$home_products = HomeProducts::find($request->id);
					$home_products->image_name = '';
					$home_products->save();
				}
				
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_delete_image")]];
				$response_http_code = 200;
			}
			else if($request->type == 'cat_img_mob') {
				$image_name = $request->image_name;
				if(File::delete(config('const.SITE_IMAGES_PATH').'homeimg/' . $image_name))
				{
					$home_products = HomeProducts::find($request->id);
					$home_products->image_name_mob = '';
					$home_products->save();
				}
				
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_delete_image")]];
				$response_http_code = 200;
			}
			else {
				$success = false;
				$errors = ["message" => [config("messages.msg_delete_image_err")]];
				$messages = [];
				$response_http_code = 400;
			}
		}
		
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}
	
}

