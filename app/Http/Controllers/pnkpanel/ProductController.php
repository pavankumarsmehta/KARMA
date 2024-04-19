<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ProductsCategory;
use App\Models\Product;
use App\Models\Manufacturer;
use App\Models\Brand;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
use File;
use Carbon\Carbon;

class ProductController extends Controller
{
	use CrudControllerTrait;

	public function model()
	{

		return Product::class;
	}

	public function list(Request $request)
	{
		if (request()->ajax()) {
			$category_id = $request->category_id;
			if (isset($category_id) && !empty($category_id)) {
				$categories = Category::where('category_id', '=', $category_id)->with('childrenRecursive')->get();
				$category_ids = getSubCategories($categories);

				$model = Product::join('hba_products_category', 'products_id', '=', 'hba_products.product_id')
					->select(['product_id', 'sku', 'product_name', 'retail_price', 'our_price', 'sale_price', 'display_rank', 'hba_products.status'])
					->whereIn('category_id', $category_ids);
			} else {
				$model = Product::select([
					'product_id',
					'sku',
					'product_name',
					'retail_price',
					'our_price',
					'sale_price',
					'display_rank',
					'status'
				]);
			}

			if ($request->clone == 1) {
				$model->where('is_clone', '1');
			}

			if (!request()->get('order') && $request->clone == 1) {
				$model->orderBy('added_datetime', 'desc');
			} elseif (!request()->get('order')) {
				$model->orderBy('product_id', 'desc');
			}

			$table = DataTables::eloquent($model);
			$table->addIndexColumn();
			$table->addColumn('checkbox', function ($row) {
				return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->product_id . '" />';
			});
			$table->editColumn('sku', function ($row) {
				return "<a href=" . route('pnkpanel.product.edit', $row->product_id) . ">" . $row->sku . "</a>";
			});
			$table->editColumn('display_rank', function ($row) {
				return "<input type='text' id='display_position_" . $row->product_id . "' value='" . $row->display_rank . "' class='form-control input-sm' size='8'>";
			});
			
			$table->editColumn('status', function ($row) {
				return ($row->status ? 'Active' : 'Inactive');
			});
			$table->addColumn('action', function ($row) {
				return (string)view('pnkpanel.component.datatable_action', ['id' => $row->product_id]);
			});
			$table->rawColumns(['checkbox', 'sku', 'display_rank', 'action']);
			return $table->make(true);
		}

		$pageData['page_title'] = "Product List";
		$pageData['meta_title'] = "Product List";
		$pageData['breadcrumbs'] = [
			[
				'title' => 'Product List',
				'url' => route('pnkpanel.product.list')
			]
		];

		return view('pnkpanel.product.list')->with($pageData);
	}

	public function edit($id = 0)
	{
		$parent_sku = "";
		if ($id > 0) {
			$product = Product::with('productsCategory')->findOrFail($id);

			$parent_sku = isset($product->parent_sku) ? $product->parent_sku : "";

		}else {
			$product =  new Product;
		}

		$brands = Brand::select('brand_id', 'brand_name')->where('status','=','1')->orderBy('brand_name')->get();
		$manufacturer = Manufacturer::select('manufacturer_id', 'manufacturer_name')->where('status','=','1')->orderBy('manufacturer_name')->get();

		$prefix = ($id > 0 ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix . ' Product';
		$pageData['meta_title'] = $prefix . ' Product';
		$pageData['breadcrumbs'] = [
			[
				'title' => 'Product List',
				'url' => route('pnkpanel.product.list')
			],
			[
				'title' => $prefix . ' Product',
				'url' => route('pnkpanel.product.edit', $id)
			]
		];
		//return view('pnkpanel.product.edit', compact('product','manufactures','brands'))->with($pageData);;
		return view('pnkpanel.product.edit', compact('product', 'parent_sku','brands','manufacturer'))->with($pageData);
	}

	public function update(Request $request)
	{

		//echo "<pre>";
		//print_r($request);
		//exit;
		
		$actType = $request->actType;
		$product_id = (int) $request->product_id;
		$sku = trim($request->sku);
		/*
		if($actType == 'add') {
			$check_duplicate = Product::select('product_id')->whereRaw('LOWER(`sku`) = ? ',[trim(strtolower($sku))])->first();
		} else {
			$check_duplicate = Product::select('product_id')->whereRaw('LOWER(`sku`) = ? ',[trim(strtolower($sku))])->where('product_id', '<>', $product_id)->first();
		}
		if($check_duplicate)
		{
			session()->flash('site_common_msg_err', 'Can not Add product Details.. Duplicate Product Group Code Found, Please change it.');
			return redirect()->route('pnkpanel.product.edit', $product_id);
		}
		*/

		/*$this->validate($request, [
			'category_name'	=> 'required|string'
		]);*/

		$product = Product::findOrNew($product_id);
		$prodctTableName =  'hba_products';
		$this->validate($request, [
			'product_name' => 'required|string',
			// 'sku' => 'required|string|unique:products_new,sku,' . $product->product_id . ',product_id',
			 'sku' 			=> 'required|unique:'.$prodctTableName.',sku,'.$product_id.',product_id',
			'retail_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
			'our_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
			// 'sale_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
			//'sale_price' => 'required|regex:/^\d*(\.\d{2})?$/',
		], [
			'product_name.required' => 'Please enter Product Name.',
			'sku.required' => 'Please enter Product SKU.',
			'retail_price.required' => 'Please enter Retail Price.',
			'our_price.required' => 'Please enter Our Price.',
			// 'sale_price.required' => 'Please enter Sale Price.',
			// 'sku.unique' => 'Can not Add product Details.. Duplicate Product Group Code Found, Please change it.',
		]);
		
		
		if ($request->hasFile('image_name')) {
			if ($request->file('image_name')->isValid()) {
				$data = $this->validate($request, [
					'image_name' => 'required|mimes:jpeg,png,webp,avif',
				]);
			}
		}
		
		for($i = 1; $i <= $request->total_extra_image; $i++) {
			if ($request->hasFile('extra_image'.$i)) {
				if ($request->file('extra_image'.$i)->isValid()) {
					$data = $this->validate($request, [
						'extra_image'.$i => 'required|mimes:jpeg,png,webp,avif',
					]);
				}
			}
		}

		if ($request->hasFile('ingredients_pdf')) {
			if ($request->file('ingredients_pdf')->isValid()) {
				$data = $this->validate($request, [
					'ingredients_pdf' => 'required|mimes:pdf',
				]);
			}
		}
			
		
		$prod_cat_id = "";
		$prod_cat_nm = "";
		if ($request->arr_category_id != "") {
			$cat_id = 0;
			$cat_arr = $request->arr_category_id;
			$cat_arr_max = max(array_keys($cat_arr));
			if (isset($cat_arr[$cat_arr_max])) {
				$cat_id = $cat_arr[$cat_arr_max];
				$cat_data = Category::findOrNew($cat_id);
				if ($cat_data['parent_id'] > 0) {
					$cat_data = Category::findOrNew($cat_data['parent_id']);
				}
				$prod_cat_id = $cat_data['category_id'];
				$prod_cat_nm = $cat_data['category_name'];
			}
		}

		// dd($prod_cat_nm);

		//exit;
		//$category = Category::findOrNew($category_id);
		
		$product->sku						=  $request->sku;
		$product->parent_sku				=  $request->parent_sku;
		$product->related_sku				=  $request->related_sku;
		if(!empty($request->product_group_code))
		{
			$product->product_group_code				=  $request->product_group_code;
		}
		else
		{
			$product->product_group_code				=  $request->parent_sku;
		
		}
		$product->product_type				=  $request->product_type;
		$product->product_name 				=  $request->product_name;
		$product->brand_id 					=  $request->brand_id ?? 0;
		if(!empty($request->brand_id))
		{
		$get_brandname=$this->getbrandname($request->brand_id);
		$product->brand 					=  $get_brandname[0]['brand_name'];
		}
		$product->manufacturer_id 			=  $request->manufacturer_id ?? 0;
		if(!empty($request->manufacturer_id))
		{
		$get_manufacturer=$this->getmanufacturername($request->manufacturer_id);
		$product->manufacturer				=  $get_manufacturer[0]['manufacturer_name'];
		}
		$product->product_description 		=  $request->product_description;
		$product->short_description 		=  $request->short_description;
		$product->general_information 		=  $request->general_information;
		$product->retail_price 				= $request->retail_price;
		$product->our_price 				= $request->our_price;
		$product->sale_price 				= $request->sale_price;
		$product->on_sale 				    = $request->on_sale;
		$product->wholesale_price 			= $request->wholesale_price;
		$product->wholesale_markup_percent 	= $request->wholesale_markup_percent;
		$product->our_cost 					= $request->our_cost;
		$product->color 					= $request->color;
		$product->shipping_text				= $request->shipping_text;
		$product->shipping_days				= $request->shipping_days;
		$product->video_url 				= $request->video_url;
		$product->display_rank 				= (isset($request->display_rank) && $request->display_rank != 0 ? $request->display_rank : '999999');
		$product->meta_title 				= $request->meta_title;
		$product->meta_keyword 			    = $request->meta_keywords;
		$product->meta_description			= $request->meta_desc;
		$product->upc 						= $request->upc;
		$product->clearance 				= $request->clearance;
		$product->best_seller 				= $request->best_seller;
		$product->display_deal_of_week 		= $request->display_deal_of_week;
		$product->new_arrival 				= $request->new_arrival;
		$product->featured 					= $request->featured;
		$product->seasonal_specials 		= $request->seasonal_specials;
		$product->product_availability 	    = $request->product_availability;
		$product->is_atomizer 				= $request->is_atomizer;
		$product->gender 					= $request->gender;
		$product->status 					= $request->status;

		$product->ingredients 				= $request->ingredients;
		$product->uses 						= $request->uses;
		$product->key_features 				= $request->key_features;
		$product->metric_size 				= $request->metric_size;
		$product->product_weight 			= $request->product_weight;
		$product->product_length 			= $request->product_length;
		$product->product_width 			= $request->product_width;
		$product->product_height 			= $request->product_height;
		$product->shipping_weight 			= $request->shipping_weight;
		$product->shipping_length 			= $request->shipping_length;
		$product->shipping_width 			= $request->shipping_width;
		$product->shipping_height 			= $request->shipping_height;
		$product->country_of_origin 		= $request->country_of_origin;
		$product->is_hazmat 				= $request->is_hazmat;
		$product->is_set 				    = $request->is_set;
		$product->is_multipack 				= $request->is_multipack;
		$product->variant 				    = $request->variant;
		$product->age_group 				= $request->age_group;
		$product->current_stock 		    = $request->current_stock;
		$product->size 				        = $request->size;
		$product->pack_size 				= $request->pack_size;
		$product->flavour 				    = $request->flavour;
		$product->skin_type 				= $request->skin_type;
		$product->multi_pack_sku 			= $request->multi_pack_sku;
		$product->temp 			            = $request->temp;
		$product->nioxin_size 			    = $request->nioxin_size;
		$product->nioxin_system 			= $request->nioxin_system;
		$product->nioxin_type 			    = $request->nioxin_type;
		$product->ship_international 		= $request->ship_international;
		$product->free_text_1 			    = $request->free_text_1;
		$product->free_text_2 			    = $request->free_text_2;
		

		$product->category 					= $prod_cat_nm;
		$product->parent_category_id		= $prod_cat_id;
		$product->is_updated_yotpo 			=  '0';
		
		if ($product->save()) {
			
			/*if ($request->hasFile('image_name')) {
				if ($request->file('image_name')->isValid()) {
					
					if(!File::exists(config('const.PRD_ZOOM_IMG_PATH'))) {
						File::makeDirectory(config('const.PRD_ZOOM_IMG_PATH'), $mode = 0777, true, true);
					}
					//echo var_dump(config('const.PRD_ZOOM_IMG_PATH')); exit;
					
					$image = $request->file('image_name');
					$image_name = $product->sku.".jpg";
					//$image_name = site_slug($category->category_name).'_'.$category->category_id.'_th'.'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.PRD_ZOOM_IMG_PATH');
					$res = $image->move($destination_path, $image_name);
					
					
					
					if($res) {
						$orig_saved_file_path = $destination_path.'/'.$image_name;

						# Resize Large Image
						if(!File::exists(config('const.PRD_LAGRE_IMG_PATH'))) {
							File::makeDirectory(config('const.PRD_LAGRE_IMG_PATH'), $mode = 0777, true, true);
						}
						$image_resize = Image::make($orig_saved_file_path);  
						$image_resize->resize(config('const.PRD_LARGE_IMG_WIDTH'), config('const.PRD_LARGE_IMG_HEIGHT'));
						$image_resize->save(config('const.PRD_LAGRE_IMG_PATH').$image_name);
						
						# Resize Medium Image
						if(!File::exists(config('const.PRD_MEDIUM_IMG_PATH'))) {
							File::makeDirectory(config('const.PRD_MEDIUM_IMG_PATH'), $mode = 0777, true, true);
						}
						$image_resize = Image::make($orig_saved_file_path);  
						$image_resize->resize(config('const.PRD_MEIDUM_IMG_WIDTH'), config('const.PRD_MEIDUM_IMG_HEIGHT'));
						$image_resize->save(config('const.PRD_MEDIUM_IMG_PATH').$image_name);
						
						# Resize Thumb Image
						//echo var_dump(config('const.PRD_THUMB_IMG_PATH')); exit;
						if(!File::exists(config('const.PRD_THUMB_IMG_PATH'))) {
							File::makeDirectory(config('const.PRD_THUMB_IMG_PATH'), $mode = 0777, true, true);
						}
						$image_resize = Image::make($orig_saved_file_path);  
						$image_resize->resize(config('const.PRD_THUMB_IMG_WIDTH'), config('const.PRD_THUMB_IMG_HEIGHT'));
						$image_resize->save(config('const.PRD_THUMB_IMG_PATH').$image_name);
						
						# Resize Small Image
						if(!File::exists(config('const.PRD_SMALL_IMG_PATH'))) {
							File::makeDirectory(config('const.PRD_SMALL_IMG_PATH'), $mode = 0777, true, true);
						}
						$image_resize = Image::make($orig_saved_file_path);  
						$image_resize->resize(config('const.PRD_SMALL_IMG_WIDTH'), config('const.PRD_SMALL_IMG_HEIGHT'));
						$image_resize->save(config('const.PRD_SMALL_IMG_PATH').$image_name);						
					}
					echo $image_name; exit;
					$product = Product::find($product->products_id);
					$product->image_name = $image_name;
					$product->save();
				}
			}*/
			
			if ($request->hasFile('image_name')) {
				if ($request->file('image_name')->isValid()) {
					
					if(!file_exists(config('const.PRD_ZOOM_IMG_PATH'))) {
						File::makeDirectory(config('const.PRD_ZOOM_IMG_PATH'), $mode = 0777, true, true);
					}
					
					//Main image
					$image = $request->file('image_name');
					
					$rand_num = random_int(1000, 9999);
					//$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = $product->sku;
				   
					$original_filename = strtolower(clearSpecialCharacters($original_filename))."_".$rand_num."_".$product->product_id.".".$image->getClientOriginalExtension();
					//$original_filename = $product->sku.".".$image->getClientOriginalExtension();
				    $image_name = $original_filename; 
					//echo $image_name; exit;
					$destination_path = config('const.PRD_ZOOM_IMG_PATH');
					
					$res = $image->move($destination_path, $image_name);
					
					if($res) {
						$orig_saved_file_path = $destination_path.'/'.$image_name;

						# Resize Large Image
						if(!File::exists(config('const.PRD_LARGE_IMG_PATH'))) {
							File::makeDirectory(config('const.PRD_LARGE_IMG_PATH'), $mode = 0777, true, true);
						}
						$image_resize = Image::make($orig_saved_file_path);  
						$image_resize->resize(config('const.PRD_LARGE_IMG_WIDTH'), config('const.PRD_LARGE_IMG_HEIGHT'));
						$image_resize->save(config('const.PRD_LARGE_IMG_PATH').$image_name);
						
						# Resize Medium Image
						if(!File::exists(config('const.PRD_MEDIUM_IMG_PATH'))) {
							File::makeDirectory(config('const.PRD_MEDIUM_IMG_PATH'), $mode = 0777, true, true);
						}
						$image_resize = Image::make($orig_saved_file_path);  
						$image_resize->resize(config('const.PRD_MEIDUM_IMG_WIDTH'), config('const.PRD_MEIDUM_IMG_HEIGHT'));
						$image_resize->save(config('const.PRD_MEDIUM_IMG_PATH').$image_name);
						
						# Resize Thumb Image
						//echo var_dump(config('const.PRD_THUMB_IMG_PATH')); exit;
						if(!File::exists(config('const.PRD_THUMB_IMG_PATH'))) {
							File::makeDirectory(config('const.PRD_THUMB_IMG_PATH'), $mode = 0777, true, true);
						}
						$image_resize = Image::make($orig_saved_file_path);  
						$image_resize->resize(config('const.PRD_THUMB_IMG_WIDTH'), config('const.PRD_THUMB_IMG_HEIGHT'));
						$image_resize->save(config('const.PRD_THUMB_IMG_PATH').$image_name);
						
						# Resize Small Image
						if(!File::exists(config('const.PRD_SMALL_IMG_PATH'))) {
							File::makeDirectory(config('const.PRD_SMALL_IMG_PATH'), $mode = 0777, true, true);
						}
						$image_resize = Image::make($orig_saved_file_path);  
						$image_resize->resize(config('const.PRD_SMALL_IMG_WIDTH'), config('const.PRD_SMALL_IMG_HEIGHT'));
						$image_resize->save(config('const.PRD_SMALL_IMG_PATH').$image_name);						
					}
					
					/*$orig_saved_file_path = $destination_path.'/'.$image_name;
					$image_resize = Image::make($orig_saved_file_path);  
					$image_resize->resize(config('const.PRD_ZOOM_IMG_WIDTH'), config('const.PRD_ZOOM_IMG_HEIGHT'));
					$image_resize->save($orig_saved_file_path);	*/				
					// if(isset($product->image_name) && !empty($product->image_name)) {
                    //     $request->actType =  'delete_image';
					// 	$request->type = 'image_name';
					// 	$request->id =  $product->product_id;
					// 	$request->subtype = 'image_name'; 
					// 	$request->image_name = $product->image_name;
                    //     $this->deleteImage($request);
					// }
					
					$product = Product::find($product->product_id);
					$product->image_name = $image_name;
					$product->save();
				}
			}
			
			
			$arr_extra_image_name = array();
			
			if($actType == 'update') {
				if(trim($product->extra_images) != '')
				{
					$arr_extra_image_name = explode("#", $product->extra_images);
				}
			}
			
			for($i = 0; $i < $request->total_extra_image; $i++)
			{
				if ($request->hasFile('extra_image'.$i))
				{
					//$arr_extra_image_name[$i] = '';
					//dd($i,$arr_extra_image_name);
					
					if($request->file('extra_image'.$i)->isValid())
					{
					// 	echo $request->total_extra_image_count.'=='.count($arr_extra_image_name);
					// exit;
					     if($request->total_extra_image_count == count($arr_extra_image_name)) {
							$request->actType =  'delete_image';
							$request->type = 'product_image';
							$request->id =  $product->product_id;
							$request->subtype = 'extra_image'; 
							if(isset($arr_extra_image_name[$i])){
							 $request->image_name = $arr_extra_image_name[$i];
                             $this->deleteImage($request);
							 unset($arr_extra_image_name[$i]);
						    }
						 }else{

						 }
						if(!File::exists(config('const.PRD_ZOOM_IMG_PATH'))) {
							File::makeDirectory(config('const.PRD_ZOOM_IMG_PATH'), $mode = 0777, true, true);
						}
						$rand_num = random_int(1000, 9999);
						$image = $request->file('extra_image'.$i);
						$image_name = $product->sku."_".$rand_num."_".($i+1).".jpg";
						$destination_path = config('const.PRD_ZOOM_IMG_PATH');
						$res = $image->move($destination_path, $image_name);
						
						if($res) {
							$orig_saved_file_path = $destination_path.'/'.$image_name;
							
							# Resize Large Image
							if(!File::exists(config('const.PRD_LARGE_IMG_PATH'))) {
								File::makeDirectory(config('const.PRD_LARGE_IMG_PATH'), $mode = 0777, true, true);
							}
							$image_resize = Image::make($orig_saved_file_path);  
							$image_resize->resize(config('const.PRD_LARGE_IMG_WIDTH'), config('const.PRD_THUMB_IMG_HEIGHT'));
							$image_resize->save(config('const.PRD_LARGE_IMG_PATH').$image_name);

							# Resize Medium Image
							if(!File::exists(config('const.PRD_MEDIUM_IMG_PATH'))) {
								File::makeDirectory(config('const.PRD_MEDIUM_IMG_PATH'), $mode = 0777, true, true);
							}
							$image_resize = Image::make($orig_saved_file_path);  
							$image_resize->resize(config('const.PRD_MEIDUM_IMG_WIDTH'), config('const.PRD_MEIDUM_IMG_HEIGHT'));
							$image_resize->save(config('const.PRD_MEDIUM_IMG_PATH').$image_name);
							
							# Resize Thumb Image
							if(!File::exists(config('const.PRD_THUMB_IMG_PATH'))) {
								File::makeDirectory(config('const.PRD_THUMB_IMG_PATH'), $mode = 0777, true, true);
							}
							$image_resize = Image::make($orig_saved_file_path);  
							$image_resize->resize(config('const.PRD_THUMB_IMG_WIDTH'), config('const.PRD_THUMB_IMG_HEIGHT'));
							$image_resize->save(config('const.PRD_THUMB_IMG_PATH').$image_name);
							
							# Resize Small Image
							if(!File::exists(config('const.PRD_SMALL_IMG_PATH'))) {
								File::makeDirectory(config('const.PRD_SMALL_IMG_PATH'), $mode = 0777, true, true);
							}
							$image_resize = Image::make($orig_saved_file_path);  
							$image_resize->resize(config('const.PRD_SMALL_IMG_WIDTH'), config('const.PRD_SMALL_IMG_HEIGHT'));
							$image_resize->save(config('const.PRD_SMALL_IMG_PATH').$image_name);

							if($actType == 'add') {
								$arr_extra_image_name[] = $image_name;
							} else {
								if(!in_array($image_name, $arr_extra_image_name)) {
									
									$arr_extra_image_name[] = $image_name;
									$out = array_splice($arr_extra_image_name, (count($arr_extra_image_name)-1), 1);
									array_splice($arr_extra_image_name, $i, 0, $out);
								}
							}
						}

					}
				}
			}
			
			if(count($arr_extra_image_name) > 0)
			{
				$extra_images = implode('#', $arr_extra_image_name);
				//dd($extra_images);
				$product = Product::find($product->product_id);
				$product->extra_images = implode('#', $arr_extra_image_name);
				$product->save();
			}
			
			$product->product_url = Get_Product_URL($product->product_id, $request->product_name, "No", $prod_cat_id, $request->sku, $prod_cat_nm);

			//ingredients pdf
			if ($request->hasFile('ingredients_pdf')) {
				if ($request->file('ingredients_pdf')->isValid()) {
					
					if(!file_exists(config('const.PRD_INGREDIENTS_PDF_PATH'))) {
						File::makeDirectory(config('const.PRD_INGREDIENTS_PDF_PATH'), $mode = 0777, true, true);
					}
					
					$image = $request->file('ingredients_pdf');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = strtolower(clearSpecialCharacters($original_filename))."_".$rand_num."_".$product->product_id.".".$image->getClientOriginalExtension();

					$image_name = $original_filename; 
					$destination_path = config('const.PRD_INGREDIENTS_PDF_PATH');
					$res = $image->move($destination_path, $image_name);
					
					$orig_saved_file_path = $destination_path.'/'.$image_name;
					
					$product = Product::find($product->product_id);
					$product->ingredients_pdf = $image_name;
					$product->save();
				}
			}

			//ingredients pdf
			if ($actType == 'add') {
				session()->flash('site_common_msg', config('messages.msg_add'));
			} else {
				session()->flash('site_common_msg', config('messages.msg_update'));
			}

			$products_id = $product->product_id;
			if ($actType != "add") {
				ProductsCategory::where('products_id', $products_id)->delete();
			}
			if (isset($request->arr_category_id) && !empty($request->arr_category_id)) {
				foreach ($request->arr_category_id as $category_id) {
					if ($category_id != '0' && !empty($category_id)) {
						$productsCategory = new ProductsCategory;
						$productsCategory->products_id = $products_id;
						$productsCategory->category_id = $category_id;
						$productsCategory->save();
					}
				}
			}

		} else {
			session()->flash('site_common_msg_err', config('messages.msg_add_err'));
		}
		return redirect()->route('pnkpanel.product.edit', $product->product_id);
	}
	public function getbrandname($brand_Id)
	{
		$brands = Brand::select('brand_name')->where('status','=','1')->where('brand_id','=',$brand_Id)->get();
		return $brands;
		
	}
	public function getmanufacturername($manufacturer_Id)
	{
		$manufacturer = Manufacturer::select('manufacturer_name')->where('status','=','1')->where('manufacturer_id','=',$manufacturer_Id)->get();
		return $manufacturer;
		
	}
	
	public function delete($id)
	{
		if (Product::findOrFail($id)->delete()) {
			session()->flash('site_common_msg', config('messages.msg_delete'));
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_delete_err'));
		}
		return redirect()->route('pnkpanel.product.list');
	}

	public function deleteImage(Request $request)
	{
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;

		$actType = $request->actType;
		if (in_array($actType, ['delete_image'])) {
			if ($request->type == 'product_image' ||  $request->type == 'image_name') {
				$image_name = $request->image_name;
				File::delete(config('const.PRD_ZOOM_IMG_PATH') . $image_name);
				File::delete(config('const.PRD_LARGE_IMG_PATH') . $image_name);
				File::delete(config('const.PRD_MEDIUM_IMG_PATH') . $image_name);
				File::delete(config('const.PRD_THUMB_IMG_PATH') . $image_name);
				File::delete(config('const.PRD_INGREDIENTS_PDF_PATH') . $image_name);


				$product = Product::find($request->id);

				if ($request->subtype == 'image_name') {
					$product->image_name = NULL;
				} elseif ($request->subtype == 'extra_image') {
					
					$extra_images_arr = explode("#", $product->extra_images);
					if (($key = array_search($image_name, $extra_images_arr)) !== false) {
						unset($extra_images_arr[$key]);
					}
					if (count($extra_images_arr) > 0) {
						$product->extra_images = implode('#', $extra_images_arr);
					} else {
						$product->extra_images = NULL;
					}
				}elseif ($request->subtype == 'ingredients_pdf') {
					$product->ingredients_pdf = NULL;
				}
				$product->save();

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

		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}
	public function deletePdf(Request $request)
	{
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;
		
		$actType = $request->actType;
		if (in_array($actType, ['delete_pdf'])) {
			if ($request->type == 'image_pdf') {
				$image_name = $request->image_name;
					File::delete(config('const.PRD_INGREDIENTS_PDF_PATH') . $image_name);


				$product = Product::find($request->id);

				if ($request->subtype == 'ingredients_pdf') {
					$product->ingredients_pdf = NULL;
				}
				$product->save();

				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_delete_pdf")]];
				$response_http_code = 200;
			} else {
				$success = false;
				$errors = ["message" => [config("messages.msg_delete_pdf_err")]];
				$messages = [];
				$response_http_code = 400;
			}
		}

		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}

	public static function drawCategoryTreeDropdown($records = null, $level = 0, $selectedCategoryIdArr = [])
	{
		// dd($records); exit;
		$html = array();
		foreach ($records as $record) {
			if ($record->parent_id == 0) {
				$level = 0;
			}
			$levelString = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level) . ($level ? "|" . $level . "|&nbsp;&nbsp;" : "&raquo;&nbsp;");

			if (isset($selectedCategoryIdArr) && !empty($selectedCategoryIdArr)) {
				$html[] = "<option value=\"" . $record->category_id . "\" " . (in_array($record->category_id,  $selectedCategoryIdArr) ? " selected" : "") . ">" . $levelString . $record->category_name . "</option>";
			} else {
				$html[] = "<option value=\"" . $record->category_id . "\" >" . $levelString . $record->category_name . "</option>";
			}


			if ($record->childrenRecursive && count($record->childrenRecursive) > 0) {
				$html = array_merge($html, self::drawCategoryTreeDropdown($record->childrenRecursive, $level + 1, $selectedCategoryIdArr));;
			}
		}
		return $html;
	}

	public function bulkUpdateRank(Request $request)
	{
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;

		$actType = $request->actType;


		if ($actType == 'update_rank') {
			$ids_obj = $request->ids;
			if (empty($ids_obj)) {
				$success = false;
				$errors = ["message" => ["Please select record(s) to Update Rank."]];
				$messages = [];
				$response_http_code = 400;
			} else {
				//$this->model()::whereIn("id",explode(",", $id_str))->update(['status' => ($actType == 'active' ? '1' : '0')]);
				foreach ($ids_obj as $record) {
					$this->model()::whereKey($record['id'])->update(['display_rank' => $record['display_position']]);
				}
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_rank")]];
				$response_http_code = 200;
			}
		}

		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}
	public function bulkUpdateGroupRank(Request $request)
	{

		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;

		$actType = $request->actType;


		if ($actType == 'update_group_rank') {
			$ids_obj = $request->ids;

			if (empty($ids_obj)) {
				$success = false;
				$errors = ["message" => ["Please select record(s) to Update Rank."]];
				$messages = [];
				$response_http_code = 400;
			} else {
				//$this->model()::whereIn("id",explode(",", $id_str))->update(['status' => ($actType == 'active' ? '1' : '0')]);
				foreach ($ids_obj as $record) {
					$this->model()::whereKey($record['id'])->update(['group_rank' => $record['display_group_position']]);
				}
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_rank")]];
				$response_http_code = 200;
			}
		}

		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}

	public function bulkUpdateSaleRank(Request $request)
	{

		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;

		$actType = $request->actType;


		if ($actType == 'update_sale_rank') {
			$ids_obj = $request->ids;

			if (empty($ids_obj)) {
				$success = false;
				$errors = ["message" => ["Please select record(s) to Sale Rank."]];
				$messages = [];
				$response_http_code = 400;
			} else {
				//$this->model()::whereIn("id",explode(",", $id_str))->update(['status' => ($actType == 'active' ? '1' : '0')]);
				foreach ($ids_obj as $record) {
					$this->model()::whereKey($record['id'])->update(['is_sale' => $record['display_group_position']]);
				}
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_rank")]];
				$response_http_code = 200;
			}
		}

		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}

	public function bulkCreateProductClone(Request $request)
	{
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;

		$actType = $request->actType;
		if ($actType == 'create_clone') {
			$id_str = $request->ids;
			if (empty($id_str)) {
				$success = false;
				$errors = ["message" => ["Please select record(s) to Create Clone Of Product(s)."]];
				$messages = [];
				$response_http_code = 400;
			} else {
				$product_ids = explode(",", $id_str);
				foreach ($product_ids as $product_id) {
					$product = $this->model()::find($product_id);
					$clone_product = $product->replicate();
					$clone_product->sku = $product->sku . '_' . rand();
					$clone_product->added_datetime = Carbon::now()->format('d-m-Y H:i:s');
					$clone_product->updated_datetime = Carbon::now()->format('d-m-Y H:i:s');
					$clone_product->is_clone = '1';
					$clone_product->push();
					$product->relations = [];
					$product->load('productsCategory');
					$relations = $product->getRelations();
					foreach ($relations as $relation) {
						foreach ($relation as $relationRecord) {
							$newRelationship = $relationRecord->replicate();
							$newRelationship->product_id = $clone_product->product_id;
							$newRelationship->push();
						}
					}
				}
				$success = true;
				$errors = [];
				$messages = ["message" => ["Product(s) Clone Added Successfully!"]];
				$response_http_code = 200;
			}
		}
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}

	public function getBrandsDropdown($manufacture_id = 0)
	{
		/*
		$options[] = '<option value="0">Select Brand</option>';
		$brands = Brand::select('brand_id', 'brand_name')->where('manufacture_id', $manufacture_id)->orderBy('brand_name')->get();
		
		foreach($brands as $brand)
		{
			$options[] = '<option value="' . $brand->brand_id . '">' . $brand->brand_name . '</option>';
		}
		return implode('', $options);
		*/

		//return Brand::select('brand_id', 'brand_name')->where('manufacture_id', $manufacture_id)->orderBy('brand_name')->get()->toJson();
		return Brand::select('brand_id', 'brand_name')->orderBy('brand_name')->get()->toJson();
	}

	public function bulkImageUpload()
	{
		$pageData['page_title'] = ' Product Bulk Image Upload';
		$pageData['meta_title'] = ' Product Bulk Image Upload';
		$pageData['breadcrumbs'] = [
			[
				'title' => 'Product Bulk Image Upload',
				'url' => route('pnkpanel.product.bulk_image_upload')
			]
		];
		return view('pnkpanel.product.bulk_image_upload')->with($pageData);;
	}

	public function postBulkImageUpload(Request $request)
	{
		$actType = $request->actType;
		if ($actType == 'ImageUpload') {

			// Validation
			$this->validate(
				$request,
				[
					'zipfile' => 'required|mimes:zip|max:' . (200 * 1024)
				],
				[
					'zipfile.required' => 'Please browse Products Images ZIP file',
					//'zipfile.file' => 'Please browse Products Images ZIP file123',
					'zipfile.mimes' => 'Please upload only the ZIP file',
					'zipfile.size' => 'File size must be less than 200 MB',
				]
			);


			if ($request->hasFile('zipfile')) {
				if ($request->file('zipfile')->isValid()) {

					$ftppath = config('const.PRD_ZOOM_IMG_PATH') . "images.zip";
					if (File::exists($ftppath)) {
						File::delete($ftppath);
					}

					$zipfile = $request->file('zipfile');
					if ($zipfile->move(config('const.PRD_ZOOM_IMG_PATH'), 'images.zip')) {
						chmod($ftppath, 0777);
						$zipfile = zip_open($ftppath);
						if ($zipfile) {
							while ($entry = zip_read($zipfile)) {
								$entry_name = zip_entry_name($entry);

								// only proceed if the file is not 0 bytes long
								if (zip_entry_filesize($entry)) {
									$file = basename($entry_name);
									$file_extention = strtolower(pathinfo($file, PATHINFO_EXTENSION));

									if (zip_entry_open($zipfile, $entry) && in_array($file_extention, ['jpg', 'jpeg'])) {
										//@unlink(config('const.PRD_LARGE_IMG_PATH').$file);
										if (File::exists(config('const.PRD_LARGE_IMG_PATH') . $file)) {
											File::delete(config('const.PRD_LARGE_IMG_PATH') . $file);
										}

										if (!File::exists(config('const.PRD_LARGE_IMG_PATH'))) {
											File::makeDirectory(config('const.PRD_LARGE_IMG_PATH'), $mode = 0777, true, true);
										}
										if ($fh = fopen(config('const.PRD_LARGE_IMG_PATH') . $file, 'w')) {
											if (fwrite($fh, zip_entry_read($entry, zip_entry_filesize($entry)))) {
												#Resize large Image
												$path_large = config('const.PRD_LARGE_IMG_PATH') . $file;
												$image_size = getimagesize($path_large);

												if ($image_size[0] > config('const.PRD_LARGE_MAX_WIDTH') or $image_size[1] > config('const.PRD_LARGE_MAX_WIDTH')) {
													// Resize large image
												}
												chmod($path_large, 0777);

												$orig_saved_file_path = $path_large;

												# Resize thumb Image
												if (!File::exists(config('const.PRD_THUMB_IMG_PATH'))) {
													File::makeDirectory(config('const.PRD_THUMB_IMG_PATH'), $mode = 0777, true, true);
												}
												$path_thumb = config('const.PRD_THUMB_IMG_PATH') . $file;
												if (File::exists($path_thumb)) {
													File::delete($path_thumb);
												}
												$image_resize = Image::make($orig_saved_file_path);
												$image_resize->resize(config('const.PRD_THUMB_MAX_WIDTH'), config('const.PRD_THUMB_MAX_HEIGHT'));
												$image_resize->save($path_thumb);
												chmod($path_thumb, 0777);

												# Resize medium Image
												if (!File::exists(config('const.PRD_MEDIUM_IMG_PATH'))) {
													File::makeDirectory(config('const.PRD_MEDIUM_IMG_PATH'), $mode = 0777, true, true);
												}
												$path_medium = config('const.PRD_MEDIUM_IMG_PATH') . $file;
												//@unlink($path_medium);
												if (File::exists($path_medium)) {
													File::delete($path_medium);
												}
												$image_resize = Image::make($orig_saved_file_path);
												$image_resize->resize(config('const.PRD_MEDIUM_MAX_WIDTH'), config('const.PRD_MEDIUM_MAX_HEIGHT'));
												$image_resize->save($path_medium);
												chmod($path_medium, 0777);

												fclose($fh);
											}
										} else {
											//error_log("can't open $dir/$file: $php_errormsg");
										}
										zip_entry_close($entry);
									} else {
										//error_log("can't open entry $entry_name: $php_errormsg");
									}
								}
							}
							zip_close($zipfile);
						} else {
							session()->flash('site_common_msg_err', 'Unable to read Zip File.');
							return redirect()->route('pnkpanel.product.bulk_image_upload');
						}
					} else {
						session()->flash('site_common_msg_err', 'Unable to upload Zip File.');
						return redirect()->route('pnkpanel.product.bulk_image_upload');
					}
				} else {
					session()->flash('site_common_msg_err', 'Please upload only the ZIP file.');
					return redirect()->route('pnkpanel.product.bulk_image_upload');
				}

				if (File::exists($ftppath)) {
					File::delete($ftppath);
				}
			} else {
				session()->flash('site_common_msg_err', 'Please browse Products Images ZIP file.');
				return redirect()->route('pnkpanel.product.bulk_image_upload');
			}
		} else {
			session()->flash('site_common_msg_err', 'Please browse Products Images ZIP file.');
			return redirect()->route('pnkpanel.product.bulk_image_upload');
		}

		session()->flash('site_common_msg', 'Product Images Uploaded Successfully.');
		return redirect()->route('pnkpanel.product.bulk_image_upload');
	}

	public function getColorByColorFamilyId(Request $request)
	{
		$color_parent_family_id = explode(',', $request->color_id);
		if ($color_parent_family_id) {


			$colorsquery = Color::select('color_id', 'color_name');
			$colorConditionString = '';
			if (isset($color_parent_family_id) && !empty($color_parent_family_id)) {
				if (count($color_parent_family_id) > 1) {
					$fc = 0;
					foreach ($color_parent_family_id as $key => $value) {
						if ($fc == 0) {
							$colorConditionString .= 'FIND_IN_SET(' . $value . ', color_parent_family_id)';
						} else {
							$colorConditionString .= 'OR FIND_IN_SET(' . $value . ', color_parent_family_id)';
						}
						$fc++;
					}
					$colorsquery->whereRaw('(' . $colorConditionString . ')');
				} else {
					$colorsquery->whereRaw('FIND_IN_SET(' . $color_parent_family_id[0] . ', color_parent_family_id)');
				}
			}

			$colorquery = $colorsquery->orderBy('color_name');
			$colors = $colorquery->get();
		}

		$this->PageData['colors'] = $colors;
		$colorData = view('pnkpanel.product.coloroptionajax')->with($this->PageData)->render();

		return response()->json(
			array(
				'colorData' => $colorData,
			)
		);
	}
}
