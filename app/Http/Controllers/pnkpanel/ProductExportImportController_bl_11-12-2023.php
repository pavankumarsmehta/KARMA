<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductsCategory;
use App\Models\Manufacturer;
use App\Models\Material;
use App\Models\ProductsMaterial;
use App\Models\ProductsStyle;
use App\Models\Style;
use DB;
use File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Traits\generalTrait;
use Cache;
use Artisan;

class ProductExportImportController extends Controller
{
    use generalTrait;
    public function export_view()
    {
        $product = new Product;
        $manufacture = Manufacturer::orderBy('manufacturer_name', 'ASC')->get();
        $pageData['page_title'] = "Export Product CSV";
        $pageData['meta_title'] = "Export Product CSV";
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Export Product CSV',
                'url' => route('pnkpanel.product.export')
            ]
        ];
        return view('pnkpanel.export_products.export_product', compact('product', 'manufacture'))->with($pageData);
    }

    public function export(Request $request)
    {

        set_time_limit('-1');
        ini_set('memory_limit', '-1');
        $gen_required_fields = array('sku', 'product_name', 'category');
        $status = $request->status;
        $export_col_names = config('exportproduct');
        $cat_table = "";
        $addsql = "";
        $str_cat1 = "";
        $str_manu = "";

        if (Cache::has('exportfilename')) {
            $export_file_name    = Cache::get('exportfilename');
            $export_file_path    = config('const.EXPORT_CSV_PATH') . Cache::get('exportfilename');
        } else {
            $export_file_name     = "producteexport_" . date('M_d_Y') . 'new' . ".csv";
            Cache::put('exportfilename', $export_file_name);
            $export_file_path    = config('const.EXPORT_CSV_PATH') . Cache::get('exportfilename');
        }

        $start_limit         = isset($request->start_limit) && $request->start_limit != 0 ? $request->start_limit : 33400;
        $end_limit            = 200;
        $process_batch        = isset($request->process_batch) && $request->process_batch != 0 ? $request->process_batch : 0;
        $total_batch         = isset($request->total_batch) && $request->total_batch != 0 ? $request->total_batch : 0;
        $manufacture_id = array();
        $category_id = array();

        $first_header_rows_arr = array();
        $exported_field_str = "";
        $tot_cols = $export_col_names;

        if ($request->session()->has('arr_manufacture_id')) {
            $manufacture_id = $request->session()->get('arr_manufacture_id');
        } else {
            $manufacture_id = $request->arr_manufacture_id;
        }

        // if ($request->session()->has('arr_category_id')) {
        //     $category_id = $request->session()->get('arr_category_id');
        // } else {
        //     $category_id = $request->arr_category_id;
        // }


        if (isset($manufacture_id)) {
            if (count($manufacture_id) > 0) {
                $new_manu_str = "";

                for ($j = 0; $j < count($manufacture_id); $j++) {
                    $str_manu_id =  $manufacture_id[$j];
                    $new_manu_str .=  $str_manu_id . ",";
                }

                $str_manu = substr($new_manu_str, 0, -1);
            }
        }

        // if (isset($category_id)) {
        //     if (count($category_id) > 0) {
        //         $new_cat_str = "";

        //         for ($j = 0; $j < count($category_id); $j++) {
        //             $str_cat =  $category_id[$j];
        //             $str_category_id = $str_cat;
        //             $str_category_id .= GetSubCategory($str_cat, $category_id);
        //             $new_cat_str .=  $str_category_id . ",";
        //         }

        //         $str_cat1 = substr($new_cat_str, 0, -1);
        //     }
        // }

        // if (isset($category_id)) {
        //     if (count($category_id) > 0) {
        //         $new_cat_str = "";

        //         for ($j = 0; $j < count($category_id); $j++) {
        //             $str_cat =  $category_id[$j];
        //             $str_category_id = $str_cat;
        //             $caaid = $this->getChildCatIdArray($str_cat);
        //             $str_category_id =  implode(',', array_unique(array_values($caaid)));
        //             $new_cat_str .=  $str_category_id . ",";
        //         }

        //         $str_cat1 = substr($new_cat_str, 0, -1);
        //     }
        // }

        if ($start_limit == 33400) {
            $request->session()->forget('exported_field_str');
            $request->session()->forget('exported_cols');
            $request->session()->forget('arr_manufacture_id');
            $request->session()->forget('arr_category_id');

            if (File::exists($export_file_path)) {
                File::delete($export_file_path);
            }
            //echo "<pre>"; print_r($export_col_names); exit;
            foreach ($export_col_names as $key => $val) {
                $exported_field_str .= "`" . $export_col_names[$key]['export_field'] . "`" . ",";

                $first_header_rows_arr[] = $key;
            }
            $exported_field_str = substr($exported_field_str, 0, -1);
            //$sql = DB::table('products_new');
            $sql = DB::table('hba_products');

            // if (isset($request->arr_category_id)) {
            //     if (count($category_id) > 0) {
            //         $cat_table_array = explode(',', $str_cat1);
            //         //$sql->leftJoin('hba_products_category', 'products_new.product_id', '=', 'hba_products_category.products_id');
            //         $sql->leftJoin('hba_products_category', 'hba_products.product_id', '=', 'hba_products_category.products_id');
            //         $sql->whereIn('hba_products_category.category_id', $cat_table_array);
            //         $total_records  = count($sql->get()->toArray());
            //     }
            // } elseif (isset($request->arr_manufacture_id)) {
            //     $manu_table_array = explode(',', $str_manu);
            //     //$sql->whereIn('products_new.manufacture_id', $manu_table_array);
            //     $sql->whereIn('hba_products.manufacture_id', $manu_table_array);
            //     $total_records  = count($sql->get()->toArray());
            // } else {
            //     $total_records  = $sql->get()->count();
            // }
            $total_records  = $sql->get()->count();
            $total_batch     = ceil($total_records / $end_limit) + 1;

            ## Insert Header

            $export_insert_arr = array();
            $first_header_rows_arr = array();
            foreach ($tot_cols as $key => $val) {
                $exported_field_str .= "`" . $tot_cols[$key]['export_field'] . "`" . ",";

                $first_header_rows_arr[] = $key;
            }

            $exported_cols         = $first_header_rows_arr;
            $exported_field_str = substr($exported_field_str, 0, -1);


            $tot_cols = count($export_col_names);
            for ($mm = 0; $mm < $tot_cols; $mm++) {
                $export_insert_arr[trim($export_col_names[$exported_cols[$mm]]['export_field'])] = trim($export_col_names[$exported_cols[$mm]]['export_header_val']);
            }
            // echo "<pre>";
            // print_r($export_insert_arr);
            // exit;
            $request->session()->put('exported_field_str', $exported_field_str);
            $request->session()->put('exported_cols', $exported_cols);
            $request->session()->put('arr_manufacture_id', $request->arr_manufacture_id);
            // $request->session()->put('arr_category_id', $request->arr_category_id);
            //DB::table('export_products_new')->truncate();
            //$insert_data = DB::table('export_products_new');

            // dd($export_insert_arr);
            DB::table('hba_export_products')->truncate();
            $insert_data = DB::table('hba_export_products');

            $insert_data->insert($export_insert_arr);
        }

        $exported_field_str = $request->session()->get('exported_field_str');
        $exported_cols         = $request->session()->get('exported_cols');

        //$result = DB::table('products_new');
        $result = DB::table('hba_products');

        if ($status == '1') {
            $result->where('hba_products.status', 1);
        }
        if ($status == '0') {
            $result->where('hba_products.status', 0);
        }

        //$result->join('hba_products_category', 'hba_products_new.product_id', '=', 'hba_products_category.products_id');
        // $result->join('hba_products_category', 'hba_products.product_id', '=', 'hba_products_category.products_id');

        // if (isset($category_id)) {
        //     $cat_table_array = explode(',', $str_cat1);
        //     if (count($cat_table_array) > 0) {
        //         $result->whereIn('hba_products_category.category_id', $cat_table_array);
        //     }
        // }

        $result = $result->orderBy('hba_products.product_id')->offset($start_limit)->limit($end_limit)->get()->toArray();

        for ($i = 0; $i < count($result); $i++) {
            $product_id = $result[$i]->product_id;
            $sku = $result[$i]->sku;
            $parent_sku = $result[$i]->parent_sku;
            $related_sku = $result[$i]->related_sku;
            $type = $result[$i]->type;
            $category = $result[$i]->category;
            $parent_category_id = $result[$i]->parent_category_id;
            $product_name = $result[$i]->product_name;
            $product_url = $result[$i]->product_url;
            $collection = $result[$i]->collection;
            $weave = $result[$i]->weave;
            $construction = $result[$i]->construction;
            $brand_id = $result[$i]->brand_id;
            $description = $result[$i]->description;
            $specification = $result[$i]->specification;
            $seat_specification = $result[$i]->seat_specification;
            $table_dimension = $result[$i]->table_dimension;
            $bench_dimension = $result[$i]->bench_dimension;
            $chair_dimensions = $result[$i]->chair_dimensions;
            $brought_together = $result[$i]->brought_together;
            $color_family_id = $result[$i]->color_family_id;
            $color_family_name = $result[$i]->color_family_name;
            $colorname = $result[$i]->colorname;
            $color_id = $result[$i]->color_id;
            $size_dimension = $result[$i]->size_dimension;
            $shape_id = $result[$i]->shape_id;
            $material = $result[$i]->material;
            $material2 = $result[$i]->material2;
            $product_width = $result[$i]->product_width;
            $product_depth = $result[$i]->product_depth;
            $product_height = $result[$i]->product_height;
            $product_length = $result[$i]->product_length;
            $feature = $result[$i]->feature;
            $product_match = $result[$i]->product_match;
            $room = $result[$i]->room;
            $style_id = $result[$i]->style_id;
            $stylename = $result[$i]->stylename;
            $metal_finish = $result[$i]->metal_finish;
            $country = $result[$i]->country;
            $care = $result[$i]->care;
            $retail_price = $result[$i]->retail_price;
            $our_price = $result[$i]->our_price;
            $sale_price = $result[$i]->sale_price;
            $WholeSalePrice = $result[$i]->WholeSalePrice;
            $freight_amount = $result[$i]->freight_amount;
            $sale_start_date = $result[$i]->sale_start_date;
            $sale_end_date = $result[$i]->sale_end_date;
            $is_sale = $result[$i]->is_sale;
            $shipping_text = $result[$i]->shipping_text;
            $shipping_days = $result[$i]->shipping_days;
            $acessories = $result[$i]->acessories;
            $product_weight = $result[$i]->product_weight;
            $weight_capacity = $result[$i]->weight_capacity;

            $swatch_image = $result[$i]->swatch_image;
            $swatch_image_thumb = $result[$i]->swatch_image_thumb;
            $swatch_image_small = $result[$i]->swatch_image_small;
            $swatch_image_large = $result[$i]->swatch_image_large;

            $main_image = $result[$i]->main_image;
            $main_image_thumb = $result[$i]->main_image_thumb;
            $main_image_small = $result[$i]->main_image_small;
            $main_image_large = $result[$i]->main_image_large;

            $rollover_image = $result[$i]->rollover_image;
            $rollover_image_thumb = $result[$i]->rollover_image_thumb;
            $rollover_image_small = $result[$i]->rollover_image_small;
            $rollover_image_large = $result[$i]->rollover_image_large;

            $additional_images = $result[$i]->additional_images;
            $additional_images_thumb = $result[$i]->additional_images_thumb;
            $additional_images_small = $result[$i]->additional_images_small;
            $additional_images_large = $result[$i]->additional_images_large;

            $video_url = $result[$i]->video_url;
            $assembly = $result[$i]->assembly;
            $badge = $result[$i]->badge;
            $rank = $result[$i]->rank;
            $group_rank = $result[$i]->group_rank;
            $display_rank = $result[$i]->display_rank;
            $meta_title = $result[$i]->meta_title;
            $meta_keywords = $result[$i]->meta_keywords;
            $meta_description = $result[$i]->meta_description;
            $upc = $result[$i]->upc;
            $quantity = $result[$i]->quantity;
            $sold_qunantity = $result[$i]->sold_qunantity;
            $sales_unit = $result[$i]->sales_unit;
            $status = $result[$i]->status;
            $general_information = $result[$i]->general_information;
            $added_datetime = $result[$i]->added_datetime;
            $updated_datetime = $result[$i]->updated_datetime;
            $is_topsellers = $result[$i]->is_topsellers;
            $is_newarrival = $result[$i]->is_newarrival;
            $pile_height = $result[$i]->pile_height;
            $thickness = $result[$i]->thickness;
            $backing = $result[$i]->backing;
            $brand = $result[$i]->brand;
            $size_family = $result[$i]->size_family;
            $metal_color = $result[$i]->metal_color;
            $metal_type = $result[$i]->metal_type;
            $size_unit = $result[$i]->size_unit;
            $accessories = $result[$i]->accessories;
            $shop_the_look = $result[$i]->shop_the_look;
            $price_var = $result[$i]->price_var;
            $product_feed_update_track = $result[$i]->product_feed_update_track;
            $img_executed_var = $result[$i]->img_executed_var;
            $img_missing_var = $result[$i]->img_missing_var;
            $img_rollover_executed_var = $result[$i]->img_rollover_executed_var;
            $img_rollover_missing_var = $result[$i]->img_rollover_missing_var;
            $img_swatch_executed_var = $result[$i]->img_swatch_executed_var;
            $img_swatch_missing_var = $result[$i]->img_swatch_missing_var;
            $spars_update_qty = $result[$i]->spars_update_qty;
            $spars_update_price = $result[$i]->spars_update_price;
            $sale_price_update = $result[$i]->sale_price_update;
            // $products_category_id = $result[$i]->products_category_id;
            // $products_id = $result[$i]->products_id;
            // $category_id = $result[$i]->category_id;

            ## Product Categories Start
            $product_category = '';
            $category_res = Get_Product_Category($product_id);
            if (is_array($category_res)) {
                for ($c = 0; $c < count($category_res); $c++) {
                    if (!empty($category_res[$c]["category_id"]))
                        $product_category .= Get_Category_Structure($category_res[$c]["category_id"]) . "#";
                }
            }
            $product_category = substr($product_category, 0, strlen($product_category) - 1);
            ## Product Categories End


            ## Product Brand Start
            $product_brand = '';
            /*if ((int)$result[$i]->brand_id"] > 0) {
                $product_brand = Get_Brand_Name($result[$i]->brand_id"]);
            }*/

            $color = '';
            if ((int)$result[$i]->color_id > 0) {
                $color = Get_Color_Name($result[$i]->color_id);
            }

            $shape = '';
            if ((int)$result[$i]->shape_id > 0) {
                $shape = Get_Shape_Name($result[$i]->shape_id);
            }

            $style = '';
            $allstylenamearray = array();
            if ($result[$i]->style_id) {
                $stylearray = explode(',', $result[$i]->style_id);
                $stylearr = Get_Style_Names($stylearray);
                foreach ($stylearr as $key => $val) {
                    array_push($allstylenamearray, $val['style_name']);
                }
            }

            $color_family = "";
            $weight = "";
            $content = "";
            $content2 = "";
            $width = $depth = $height = $length = "";
            $match = "";
            $style_1 = "";
            $position = "";
            $seat_dimension = "";
            $price = "";
            $shipping = "";
            ## Product Brand End
            $export_insert_arr = array();
            $tot_cols = count($exported_cols);
            // dd($tot_cols);
            $gen_csv_fields_arr = $export_col_names;

            // if ($result[$i]['room'] != "") {
            //     $style_room = explode(":", $result[$i]['room']);
            //     $style_1 = isset($style_room[1]) ? $style_room[1] : "";
            // }
            if (count($allstylenamearray) > 0) {
                $style_1 = implode(':', $allstylenamearray);
            }

            // echo "<pre>";
            // print_r($gen_csv_fields_arr);
            // echo "--------------";
            // print_r($exported_cols);
            // exit;
            for ($hk = 0; $hk < $tot_cols; $hk++) {
                //echo $product_id;

                // echo $result[$i]->product_weight"];//$exported_cols[$hk];                

                // echo $exported_cols[$hk];
                // echo "<br>";
                // echo "---------------";
                // echo "<br>";
                //$info = "country:China#lamp_color:Brass#shade_color:Brass Gold#body_material:Metal#shade_dimensions:8\" x 8\"#light_bulb_bass:E12#cord_length:72\"#bulb_type:B11#watts:4W#metal_finish:Electroplating";
                $general_info = $this->general_info_arr($result[$i]->general_information);
                if ($exported_cols[$hk] == "category") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $product_category;
                } elseif ($exported_cols[$hk] == "brand_name") {
                    //$export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = "Test Brand"; //$product_brand;
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $product_brand;
                } elseif ($exported_cols[$hk] == "content") {
                    //$export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->product_id"];
                    //$export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = "Test Content"; 
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $this->get_product_material($result[$i]->product_id);
                } else if ($exported_cols[$hk] == "content2") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->material2;
                } else if ($exported_cols[$hk] == "weight") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->product_weight != "0" ? $result[$i]->product_weight : "";
                } else if ($exported_cols[$hk] == "width") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->product_width != "0" ? $result[$i]->product_width : "";
                } else if ($exported_cols[$hk] == "depth") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->product_depth != "0" ? $result[$i]->product_depth : "";
                } else if ($exported_cols[$hk] == "height") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->product_height != "0" ? $result[$i]->product_height : "";
                } else if ($exported_cols[$hk] == "length") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->product_length != "0" ? $result[$i]->product_length : "";
                } else if ($exported_cols[$hk] == "size_unit") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->size_unit != "0" ? $result[$i]->size_unit : "";
                } else if ($exported_cols[$hk] == "match") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->product_match != "0" ? $result[$i]->product_match : "";
                } else if ($exported_cols[$hk] == "style_1") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $style_1; //"Test Style";
                } else if ($exported_cols[$hk] == "metal_color") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : "";
                } else if ($exported_cols[$hk] == "metal_type") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : "";
                } else if ($exported_cols[$hk] == "country") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Body Material"; //can get it from general information
                } else if ($exported_cols[$hk] == "main_image") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->main_image != "0" ? $result[$i]->main_image : "";
                } else if ($exported_cols[$hk] == "main_image_thumb") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->main_image_thumb != "0" ? $result[$i]->main_image_thumb : "";
                } else if ($exported_cols[$hk] == "main_image_small") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->main_image_small != "0" ? $result[$i]->main_image_small : "";
                } else if ($exported_cols[$hk] == "main_image_large") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->main_image_large != "0" ? $result[$i]->main_image_large : "";
                } else if ($exported_cols[$hk] == "rollover_image") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->rollover_image != "0" ? $result[$i]->rollover_image : "";
                } else if ($exported_cols[$hk] == "rollover_image_thumb") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->rollover_image_thumb != "0" ? $result[$i]->rollover_image_thumb : "";
                } else if ($exported_cols[$hk] == "rollover_image_small") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->rollover_image_small != "0" ? $result[$i]->rollover_image_small : "";
                } else if ($exported_cols[$hk] == "rollover_images_large") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->rollover_images_large != "0" ? $result[$i]->rollover_images_large : "";
                } else if ($exported_cols[$hk] == "additional_images") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->additional_images != "0" ? $result[$i]->additional_images : "";
                } else if ($exported_cols[$hk] == "additional_images_thumb") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->additional_images_thumb != "0" ? $result[$i]->additional_images_thumb : "";
                } else if ($exported_cols[$hk] == "additional_images_small") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->additional_images_small != "0" ? $result[$i]->additional_images_small : "";
                } else if ($exported_cols[$hk] == "additional_images_large") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->additional_images_large != "0" ? $result[$i]->additional_images_large : "";
                } else if ($exported_cols[$hk] == "video_url") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->video_url != "0" ? $result[$i]->video_url : "";
                } else if ($exported_cols[$hk] == "status") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->status == '1' ? '1' : "0";
                } else if ($exported_cols[$hk] == "lamp_color") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : "";  //"Test Lamp Color"; //can get it from general information
                } else if ($exported_cols[$hk] == "shade_color") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Shade Color"; //can get it from general information
                } else if ($exported_cols[$hk] == "base_type") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; // "Test Base Type";
                } else if ($exported_cols[$hk] == "body_material") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Body Material"; //can get it from general information
                } else if ($exported_cols[$hk] == "shade_dimensions") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Shade Dimension"; //can get it from general information
                } else if ($exported_cols[$hk] == "canopy_dimensions") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : "";  //"Test Canopy Dimension"; //can get it from general information
                } else if ($exported_cols[$hk] == "light_bulb_bass") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Light Bulb Bass"; //can get it from general information
                } else if ($exported_cols[$hk] == "cord_length") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Cord Length"; //can get it from general information
                } else if ($exported_cols[$hk] == "chain_rod_length") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Cord Length"; //can get it from general information
                } else if ($exported_cols[$hk] == "maximum_hanging_length") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Maximum Hanging Length"; //can get it from general information
                } else if ($exported_cols[$hk] == "minimum_hanging_length") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Minimum Hanging Length"; //can get it from general information
                } else if ($exported_cols[$hk] == "bulb_type") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Bulb Type"; //can get it from general information
                } else if ($exported_cols[$hk] == "watts") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test watts"; //can get it from general information
                } else if ($exported_cols[$hk] == "special_functions") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Special Functions"; //can get it from general information
                } else if ($exported_cols[$hk] == "wood_color") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Wood Color"; //can get it from general information
                } else if ($exported_cols[$hk] == "wood_content") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Wood Content"; //can get it from general information
                } else if ($exported_cols[$hk] == "finish_surface_treatment") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Finish Surface Treatment"; //can get it from general information
                } else if ($exported_cols[$hk] == "fabric_color") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Fabric Color"; //can get it from general information
                } else if ($exported_cols[$hk] == "fabric_material") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Fabric Material"; //can get it from general information
                } else if ($exported_cols[$hk] == "fill_material") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Fill Material"; //can get it from general information
                } else if ($exported_cols[$hk] == "seat_dimension") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->seat_specification;
                    // $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Seat Dimension"; //can get it from general information
                } else if ($exported_cols[$hk] == "no_of_shelves") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test No of Shelves"; //can get it from general information
                } else if ($exported_cols[$hk] == "shelf_dimensions") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Shelf Dimensions"; //can get it from general information
                } else if ($exported_cols[$hk] == "no_of_drawers") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test No of Drawers"; //can get it from general information
                } else if ($exported_cols[$hk] == "drawer_dimensions") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Drawer Dimensions"; //can get it from general information
                } else if ($exported_cols[$hk] == "back_cover") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Back Cover"; //can get it from general information
                } else if ($exported_cols[$hk] == "closure") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Closure"; //can get it from general information
                } else if ($exported_cols[$hk] == "insert") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Insert"; //can get it from general information
                } else if ($exported_cols[$hk] == "glass_thickness") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Glass Thickness"; //can get it from general information
                } else if ($exported_cols[$hk] == "mirror_dimensions") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = isset($general_info[$exported_cols[$hk]]) ? $general_info[$exported_cols[$hk]] : ""; //"Test Mirror Dimensions"; //can get it from general information
                } else if ($exported_cols[$hk] == "price") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->our_price; //can get it from general information
                } else if ($exported_cols[$hk] == "shipping") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->shipping_text; //can get it from general information
                } else if ($exported_cols[$hk] == "is_sale") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = ($result[$i]->is_sale == 999999 ? "" : $result[$i]->is_sale); //can get it from general information
                } else if ($exported_cols[$hk] == "display_rank") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = ($result[$i]->display_rank == 999999 ? "" : $result[$i]->display_rank); //can get it from general information
                } else if ($exported_cols[$hk] == "group_rank") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = ($result[$i]->group_rank == 999999 ? "" : $result[$i]->group_rank); //can get it from general information
                } else {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = trim(${$gen_csv_fields_arr[$exported_cols[$hk]]['product_field']});
                }
            }

            $delimiter = ',';
            $enclosure = '"';
            $escape_char = "\\";
            // $file = fopen($export_file_path, "a+");
            //$insert_data = DB::table('export_products_new');
            $insert_data = DB::table('hba_export_products');
            $insert_data->insert($export_insert_arr);
            // fclose($file);
            // dd("here");
        }

        $this->Make_Product_CSV("hba_export_products", $export_file_path, $start_limit, $end_limit, $exported_field_str, $exported_cols, $export_col_names);

        $start_limit    = $start_limit + $end_limit;
        $process_batch  = $process_batch + 1;

        if ($process_batch == $total_batch) {
            $request->session()->forget('exported_field_str');
            $request->session()->forget('exported_cols');
            $request->session()->forget('arr_manufacture_id');
            $request->session()->forget('arr_category_id');
            $request->session()->forget('filenamecustom');
            Cache::forget('exportfilename');
            return redirect()->route('pnkpanel.product.export')->with(['filename' => $export_file_name]);
        } else {
            return view('pnkpanel.export_products.post_export_product', compact('start_limit', 'end_limit', 'process_batch', 'total_batch'));
        }
    }

    public function export_new_on_the_spot(Request $request)
    {

        set_time_limit('-1');
        $status = $request->status;
        $export_col_names = config('exportproduct');
        $str_cat1 = "";
        $category_id = array();

        $tot_cols = $export_col_names;

        if ($request->session()->has('arr_category_id')) {
            $category_id = $request->session()->get('arr_category_id');
        } else {
            $category_id = $request->arr_category_id;
        }

        if (isset($category_id)) {
            if (count($category_id) > 0) {
                $new_cat_str = "";

                for ($j = 0; $j < count($category_id); $j++) {
                    $str_cat =  $category_id[$j];
                    $str_category_id = $str_cat;
                    $caaid = $this->getChildCatIdArray($str_cat);
                    $str_category_id =  implode(',', array_unique(array_values($caaid)));
                    $new_cat_str .=  $str_category_id . ",";
                }

                $str_cat1 = substr($new_cat_str, 0, -1);
            }
        }

        $result = DB::table('hba_products');
        if ($status == '1') {
            $result->where('hba_products.status', 1);
        }
        if ($status == '0') {
            $result->where('hba_products.status', 0);
        }

        $result->join('hba_products_category', 'hba_products.product_id', '=', 'hba_products_category.products_id');

        if (isset($category_id)) {
            $cat_table_array = explode(',', $str_cat1);
            if (count($cat_table_array) > 0) {
                $result->whereIn('hba_products_category.category_id', $cat_table_array);
            }
        }

        $result = $result->orderBy('hba_products.product_id')->get()->toArray();

        $result = json_decode(json_encode($result), true);

        $Csv_data_str = "SKU,Parent SKU,Name,Category,Collection,Weave,Construction,Content,Content2,Shape,Pile Height,Thickness,Backing,Brand,Description,Specification,Color Family,Color,Size Dimension,Size Family,Weight ,Metal Color,Metal Type,Width,Depth,Height,Length,Size Unit,Feature,Match,Room,Style,Country,Care,Accessories,Swatch Image,Main Image,Rollover Image,Additional Images,Video URL,Assembly,Badge,Display Rank,Group Rank,Meta Title,Meta Keywords,Meta Description,UPC,Quantity,Sales Unit,Status,Lamp Color,Shade Color,Base Type,Body Material,Shade Dimensions,Canopy Dimensions,Light Bulb Bass,Cord Length ,Chain Rod Length,Maximum Hanging Length,Minimum Hanging Length,Bulb Type,Watts,Special Functions,Wood Color,Wood Content,Finish Surface Treatment,Fabric Color,Fabric Material,Fill Material,Seat Dimension,Table Dimension,Bench Dimension,Chair Dimensions,Weight Capacity,No_Of_Shelves,Shelf Dimensions,No_Of_Drawers,Drawer Dimensions,Metal Finish,Back Cover,Closure,Insert,Glass Thickness,Mirror Dimensions,Retail Price,Price,Sale Price,Sale Start Date,Sale End Date,Shipping,Shipping Days,Is_Sale,Product_URL\n";


        for ($i = 0; $i < count($result); $i++) {
            $data_arr = $result[$i];
            extract($data_arr);
            $product_id = $result[$i]["product_id"];

            ## Product Categories Start
            $product_category = '';
            $category_res = Get_Product_Category($product_id);
            if (is_array($category_res)) {
                for ($c = 0; $c < count($category_res); $c++) {
                    if (!empty($category_res[$c]["category_id"]))
                        $product_category .= Get_Category_Structure($category_res[$c]["category_id"]) . "#";
                }
            }
            $product_category = substr($product_category, 0, strlen($product_category) - 1);
            ## Product Categories End


            ## Product Brand Start
            $product_brand = '';
            /*if ((int)$result[$i]["brand_id"] > 0) {
                $product_brand = Get_Brand_Name($result[$i]["brand_id"]);
            }*/

            $ex_color = '';
            if ((int)$result[$i]["color_id"] > 0) {
                $ex_color = Get_Color_Name($result[$i]["color_id"]);
            }

            $shape = '';
            if ((int)$result[$i]["shape_id"] > 0) {
                $shape = Get_Shape_Name($result[$i]["shape_id"]);
            }

            $style = '';
            if ((int)$result[$i]["style_id"] > 0) {
                $style = Get_Style_Name($result[$i]["style_id"]);
            }

            $style_1 = "";
            ## Product Brand End

            // $tot_cols = count($exported_cols);


            if ($result[$i]['room'] != "") {
                $style_room = explode(":", $result[$i]['room']);
                $style_1 = isset($style_room[1]) ? $style_room[1] : "";
            }
            //exit;
            for ($hk = 0; $hk < 10; $hk++) {

                $general_info = $this->general_info_arr($result[$i]["general_information"]);

                $ex_product_sku = $result[$i]["sku"];
                $ex_parent_sku = $result[$i]["parent_sku"];
                $ex_product_name = $result[$i]["product_name"];
                $ex_category = $product_category;
                $ex_collection = $result[$i]["collection"];
                $ex_weave = $result[$i]["weave"];
                $ex_construction = $result[$i]["construction"];

                $ex_content = $this->get_product_material($result[$i]["product_id"]);

                $ex_content2 = $result[$i]["material2"];

                $ex_shape = $shape;
                $ex_pile_height = $result[$i]["pile_height"];

                $ex_glass_thickness = isset($general_info[$hk]['glass_thickness']) ? $general_info[$hk]['glass_thickness'] : ""; //"Test Glass Thickness"; //can get it from general information

                $ex_backing = $result[$i]["backing"];

                $ex_product_brand = $product_brand;

                $ex_description = $result[$i]["description"];
                $ex_specification = $result[$i]["specification"];
                $ex_color_family_name = $result[$i]["color_family_name"];
                $ex_product_weight = $result[$i]["product_weight"] != "0" ? $result[$i]["product_weight"] : "";
                $ex_product_width = $result[$i]["product_width"] != "0" ? $result[$i]["product_width"] : "";
                $ex_product_depth = $result[$i]["product_depth"] != "0" ? $result[$i]["product_depth"] : "";
                $ex_product_height = $result[$i]["product_height"] != "0" ? $result[$i]["product_height"] : "";
                $ex_product_length = $result[$i]["product_length"] != "0" ? $result[$i]["product_length"] : "";
                $ex_size_unit = $result[$i]["size_unit"] != "0" ? $result[$i]["size_unit"] : "";
                $ex_feature = $result[$i]["feature"];
                $ex_product_match = $result[$i]["product_match"] != "0" ? $result[$i]["product_match"] : "";
                $ex_room = $result[$i]["room"];
                $ex_style = $style;
                $ex_care = $result[$i]["care"];
                $ex_accessories = $result[$i]["accessories"];
                $ex_swatch_image = $result[$i]["swatch_image"];
                $ex_main_image = $result[$i]["main_image"] != "0" ? $result[$i]["main_image"] : "";
                $ex_rollover_image = ($result[$i]["rollover_image"] != "0" && substr($result[$i]["rollover_image"], -2) == "#0") ? $result[$i]["rollover_image"] : "";
                $ex_additional_images = ($result[$i]["additional_images"] != "0" && substr($result[$i]["additional_images"], -2) == "#0") ? substr($result[$i]["additional_images"], 0, -2)  : "";
                $ex_video_url = $result[$i]["video_url"] != "0" ? $result[$i]["video_url"] : "";
                $ex_assembly = $result[$i]["assembly"];
                $ex_badge = $result[$i]["badge"];
                $ex_display_rank = ($result[$i]["display_rank"] == 999999 ? "" : $result[$i]["display_rank"]); //can get it from general information
                $ex_group_rank = ($result[$i]["group_rank"] == 999999 ? "" : $result[$i]["group_rank"]); //can get it from general information
                $ex_meta_title = $result[$i]["meta_title"];
                $ex_meta_keywords = $result[$i]["meta_keywords"];
                $ex_meta_description = $result[$i]["meta_description"];
                $ex_upc = $result[$i]["upc"];
                $ex_quantity = $result[$i]["quantity"];
                $ex_sales_unit = $result[$i]["sales_unit"];
                $ex_status = $result[$i]["status"] == '1' ? '1' : "0";

                $ex_lamp_color = isset($general_info[$hk]['lamp_color']) ? $general_info[$hk]['lamp_color'] : "";  //"Test Lamp Color"; //can get it from general information

                $ex_shade_color = isset($general_info[$hk]['shade_color']) ? $general_info[$hk]['shade_color'] : ""; //"Test Shade Color"; //can get it from general information

                $ex_base_type = isset($general_info[$hk]['base_type']) ? $general_info[$hk]['base_type'] : ""; // "Test Base Type";

                $ex_body_material = isset($general_info[$hk]['body_material']) ? $general_info[$hk]['body_material'] : ""; //"Test Body Material"; //can get it from general information

                $ex_shade_dimensions = isset($general_info[$hk]['shade_dimensions']) ? $general_info[$hk]['shade_dimensions'] : ""; //"Test Shade Dimension"; //can get it from general information

                $ex_canopy_dimensions = isset($general_info[$hk]['canopy_dimensions']) ? $general_info[$hk]['canopy_dimensions'] : "";  //"Test Canopy Dimension"; //can get it from general information

                $ex_light_bulb_bass = isset($general_info[$hk]['light_bulb_bass']) ? $general_info[$hk]['light_bulb_bass'] : ""; //"Test Light Bulb Bass"; //can get it from general information

                $ex_cord_length = isset($general_info[$hk]['cord_length']) ? $general_info[$hk]['cord_length'] : ""; //"Test Cord Length"; //can get it from general information

                $ex_chain_rod_length = isset($general_info[$hk]['chain_rod_length']) ? $general_info[$hk]['chain_rod_length'] : ""; //"Test Cord Length"; //can get it from general information

                $ex_maximum_hanging_length = isset($general_info[$hk]['maximum_hanging_length']) ? $general_info[$hk]['maximum_hanging_length'] : ""; //"Test Maximum Hanging Length"; //can get it from general information

                $ex_minimum_hanging_length = isset($general_info[$hk]['minimum_hanging_length']) ? $general_info[$hk]['minimum_hanging_length'] : ""; //"Test Minimum Hanging Length"; //can get it from general information

                $ex_bulb_type = isset($general_info[$hk]['bulb_type']) ? $general_info[$hk]['bulb_type'] : ""; //"Test Bulb Type"; //can get it from general information

                $ex_watts = isset($general_info[$hk]['watts']) ? $general_info[$hk]['watts'] : ""; //"Test watts"; //can get it from general information

                $ex_special_functions = isset($general_info[$hk]['special_functions']) ? $general_info[$hk]['special_functions'] : ""; //"Test Special Functions"; //can get it from general information

                $ex_wood_color = isset($general_info[$hk]['wood_color']) ? $general_info[$hk]['wood_color'] : ""; //"Test Wood Color"; //can get it from general information

                $ex_wood_content = isset($general_info[$hk]['wood_content']) ? $general_info[$hk]['wood_content'] : ""; //"Test Wood Content"; //can get it from general information

                $ex_finish_surface_treatment = isset($general_info[$hk]['finish_surface_treatment']) ? $general_info[$hk]['finish_surface_treatment'] : ""; //"Test Finish Surface Treatment"; //can get it from general information

                $ex_fabric_color = isset($general_info[$hk]['fabric_color']) ? $general_info[$hk]['fabric_color'] : ""; //"Test Fabric Color"; //can get it from general information

                $ex_fabric_material = isset($general_info[$hk]['fabric_material']) ? $general_info[$hk]['fabric_material'] : ""; //"Test Fabric Material"; //can get it from general information

                $ex_fill_material = isset($general_info[$hk]['fill_material']) ? $general_info[$hk]['fill_material'] : ""; //"Test Fill Material"; //can get it from general information

                $ex_seat_dimension = isset($general_info[$hk]['seat_dimension']) ? $general_info[$hk]['seat_dimension'] : ""; //"Test Seat Dimension"; //can get it from general information

                $ex_table_dimension = $result[$i]["table_dimension"];
                $ex_bench_dimension = $result[$i]["bench_dimension"];
                $ex_chair_dimensions = $result[$i]["chair_dimensions"];

                $ex_weight_capacity = $result[$i]["weight_capacity"];


                $ex_no_of_shelves = isset($general_info[$hk]['no_of_shelves']) ? $general_info[$hk]['no_of_shelves'] : ""; //"Test No of Shelves"; //can get it from general information

                $ex_shelf_dimensions = isset($general_info[$hk]['shelf_dimensions']) ? $general_info[$hk]['shelf_dimensions'] : ""; //"Test Shelf Dimensions"; //can get it from general information

                $ex_no_of_drawers = isset($general_info[$hk]['no_of_drawers']) ? $general_info[$hk]['no_of_drawers'] : ""; //"Test No of Drawers"; //can get it from general information

                $ex_drawer_dimensions = isset($general_info[$hk]['drawer_dimensions']) ? $general_info[$hk]['drawer_dimensions'] : ""; //"Test Drawer Dimensions"; //can get it from general information

                $ex_metal_finish = $result[$i]["metal_finish"];

                $ex_back_cover = isset($general_info[$hk]['back_cover']) ? $general_info[$hk]['back_cover'] : ""; //"Test Back Cover"; //can get it from general information

                $ex_closure = isset($general_info[$hk]['closure']) ? $general_info[$hk]['closure'] : ""; //"Test Closure"; //can get it from general information

                $ex_insert = isset($general_info[$hk]['insert ']) ? $general_info[$hk]['insert '] : ""; //"Test Insert"; //can get it from general information

                $ex_glass_thickness = $result[$i]["thickness"];

                $ex_mirror_dimensions = isset($general_info[$hk]['mirror_dimensions']) ? $general_info[$hk]['mirror_dimensions'] : ""; //"Test Mirror Dimensions"; //can get it from general information

                $ex_retail_price = $result[$i]["retail_price"];

                $ex_size_dimension = $result[$i]["size_dimension"];

                $ex_size_family = $result[$i]["size_family"];



                $ex_our_price = $result[$i]["our_price"]; //can get it from general information
                $ex_sale_price = $result[$i]["sale_price"];


                $ex_sale_start_date = $result[$i]["sale_start_date"];
                $ex_sale_end_date = $result[$i]["sale_end_date"];

                $ex_shipping = $result[$i]["shipping_text"]; //can get it from general information

                $ex_shipping_days = $result[$i]["shipping_days"]; //can get it from general information

                $ex_is_sale = ($result[$i]["is_sale"] == 999999 ? "" : $result[$i]["is_sale"]); //can get it from general information


                $Product_URL = $result[$i]["product_url"];

                $ex_meta_country = $result[$i]["country"];
                // $ex_meta_room = $result[$i]["room"];
                // $ex_meta_feature = $result[$i]["feature"];
                $ex_meta_metal_type = $result[$i]["metal_type"];
                $ex_meta_metal_color = $result[$i]["metal_color"];



                $ex_style_1 = $style_1; //"Test Style";

                $ex_metal_color = isset($general_info[$hk]['metal_color']) ? $general_info[$hk]['metal_color'] : "";

                $ex_metal_type = isset($general_info[$hk]['metal_type ']) ? $general_info[$hk]['metal_type '] : "";

                $ex_country = isset($general_info[$hk]['country']) ? $general_info[$hk]['country'] : ""; //"Test Body Material"; //can get it from general information

            }

            // $Csv_data_str = ",,,,,,,,,,,,,,,,,\n";
            $Csv_data_str .= '"' . $ex_product_sku . '","' . $ex_parent_sku . '","' . $ex_product_name . '","' . $ex_category . '","' . $ex_collection . '","' . $ex_weave . '","' . $ex_construction . '","' . $ex_content . '","' . $ex_content2 . '","' .
                $ex_shape . '","' . $ex_pile_height . '","' . $ex_glass_thickness . '","' . $ex_backing . '","' . $ex_product_brand . '","' . $ex_description . '","' . $ex_specification . '","' . $ex_color_family_name . '","' . $ex_color . '",' .
                $ex_size_dimension . ',"' . $ex_size_family . '","' . $ex_product_weight . '","' . $ex_metal_color . '","' . $ex_metal_type . '","' . $ex_product_width . '","' . $ex_product_depth . '","' . $ex_product_height . '","' . $ex_product_length . '","' .
                $ex_size_unit . '","' . $ex_feature . '","' . $ex_product_match . '","' . $ex_room . '","' . $ex_style . '","' . $ex_meta_country . '","' . $ex_care . '","' . $ex_accessories . '","' . $ex_swatch_image . '","' .
                $ex_main_image . '","' . $ex_rollover_image . '","' . $ex_additional_images . '","' . $ex_video_url . '","' . $ex_assembly . '","' . $ex_badge . '","' . $ex_display_rank . '","' . $ex_group_rank . '","' .
                $ex_meta_title . '","' . $ex_meta_keywords . '","' . $ex_meta_description . '","' . $ex_upc . '","' . $ex_quantity . '","' . $ex_sales_unit . '","' . $ex_status . '","' . $ex_lamp_color . '","' . $ex_shade_color . '","' .
                $ex_base_type . '","' . $ex_body_material . '","' . $ex_shade_dimensions . '","' . $ex_canopy_dimensions . '","' . $ex_light_bulb_bass . '","' . $ex_cord_length . '","' . $ex_chain_rod_length . '","' . $ex_maximum_hanging_length . '","' .
                $ex_minimum_hanging_length . '","' . $ex_bulb_type . '","' . $ex_watts . '","' . $ex_special_functions . '","' . $ex_wood_color . '","' . $ex_wood_content . '","' . $ex_finish_surface_treatment . '","' . $ex_fabric_color . '","' .
                $ex_fabric_material . '","' . $ex_fill_material . '","' . $ex_seat_dimension . '","' . $ex_table_dimension . '","' . $ex_bench_dimension . '","' . $ex_chair_dimensions . '","' . $ex_weight_capacity . '","' .
                $ex_no_of_shelves . '","' . $ex_shelf_dimensions . '","' . $ex_no_of_drawers . '","' . $ex_drawer_dimensions . '","' . $ex_metal_finish . '","' . $ex_back_cover . '","' . $ex_closure . '","' .
                $ex_insert . '","' . $ex_glass_thickness . '","' . $ex_mirror_dimensions . '","' . $ex_retail_price . '","' . $ex_our_price . '","' . $ex_sale_price . '","' . $ex_sale_start_date . '","' . $ex_sale_end_date . '","' .
                $ex_shipping . '","' . $ex_shipping_days . '","' . $ex_is_sale . '","' . $Product_URL . '"';
            $Csv_data_str .= "\n";
        }

        $filename = "hbasales_" . date('M_d_Y') . ".csv";
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo $Csv_data_str;
    }


    public function headerexport_view()
    {
        $product = new Product;
        $manufacture = Manufacture::orderBy('manufacture_name', 'ASC')->get();
        $pageData['page_title'] = "Export Product CSV";
        $pageData['meta_title'] = "Export Product CSV";
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Export Product CSV',
                'url' => route('pnkpanel.product.headerexport')
            ]
        ];
        return view('pnkpanel.export_products.export_product', compact('product', 'manufacture'))->with($pageData);
    }

    public function headerexport(Request $request)
    {

        set_time_limit('-1');
        ini_set('memory_limit', '-1');
        $export_col_names = array();
        $export_col_namesArray = config('exportproduct');

        if (isset($request->arr_header_name)) {
            Cache::forget('arr_header_name_cache');
            Cache::put('arr_header_name_cache', $request->arr_header_name);
            $headerArrayName = Cache::get('arr_header_name_cache');
        } else {
            if (Cache::has('arr_header_name_cache')) {
                $headerArrayName = Cache::get('arr_header_name_cache');
            }
        }

        if (isset($headerArrayName)) {
            Cache::forget('exporfield_cache');
            foreach ($headerArrayName as $ex_c) {
                $keyname = $ex_c;
                if ($ex_c === 'our_price') {
                    $keyname = 'price';
                } elseif ($ex_c === 'product_name') {
                    $keyname = 'name';
                }
                $export_col_names[$keyname] = $export_col_namesArray[$keyname];
                Cache::put('exporfield_cache', $export_col_names);
                $export_col_names = Cache::get('exporfield_cache');
            }
        } else {
            if (Cache::has('exporfield_cache')) {
                $export_col_names = Cache::get('exporfield_cache');
            }
        }

        $cat_table = "";
        $addsql = "";
        $str_cat1 = "";
        $str_manu = "";
        // $export_file_name     = "hbasales_custom_" . rand(5.2) . date('M_d_Y') . ".csv";
        // $export_file_path    = config('const.EXPORT_CSV_PATH') . $export_file_name;

        if (Cache::has('customexportfilename')) {
            $export_file_name    = Cache::get('customexportfilename');
            $export_file_path    = config('const.EXPORT_CSV_PATH') . Cache::get('customexportfilename');
        } else {
            $export_file_name     = "productcustomexport_" . date('M_d_Y') . ".csv";
            Cache::put('customexportfilename', $export_file_name);
            $export_file_path    = config('const.EXPORT_CSV_PATH') . Cache::get('customexportfilename');
        }

        $start_limit         = isset($request->start_limit) && $request->start_limit != 0 ? $request->start_limit : 0;
        $end_limit            = 200;
        $process_batch        = isset($request->process_batch) && $request->process_batch != 0 ? $request->process_batch : 0;
        $total_batch         = isset($request->total_batch) && $request->total_batch != 0 ? $request->total_batch : 0;
        $first_header_rows_arr = array();
        $exported_field_str = "";
        $tot_cols = $export_col_names;
        $status = $request->status;
        $str_cat1 = "";

        if ($request->session()->has('arr_category_id')) {
            $category_id = $request->session()->get('arr_category_id');
        } else {
            $category_id = $request->arr_category_id;
        }

        $result = DB::table('hba_products');
        if ($status == '1') {
            $result->where('hba_products.status', '1');
        }
        if ($status == '0') {
            $result->where('hba_products.status', '0');
        }

        $result->join('hba_products_category', 'hba_products.product_id', '=', 'hba_products_category.products_id');

        $result->addSelect('product_id');
        foreach ($headerArrayName as $ex_c) {
            $result = $result->addSelect($ex_c);
        }

        if (isset($category_id)) {
            if (count($category_id) > 0) {
                $new_cat_str = "";

                for ($j = 0; $j < count($category_id); $j++) {
                    $str_cat =  $category_id[$j];
                    $str_category_id = $str_cat;
                    $caaid = $this->getChildCatIdArray($str_cat);
                    $str_category_id =  implode(',', array_unique(array_values($caaid)));
                    $new_cat_str .=  $str_category_id . ",";
                }
                $str_cat1 = substr($new_cat_str, 0, -1);
            }
        }

        if ($start_limit == 0) {
            $request->session()->forget('exported_field_str');
            $request->session()->forget('exported_cols');
            $request->session()->forget('arr_category_id');

            if (File::exists($export_file_path)) {
                File::delete($export_file_path);
            }

            foreach ($export_col_names as $key => $val) {
                $exported_field_str .= "`" . $export_col_names[$key]['export_field'] . "`" . ",";
                $first_header_rows_arr[] = $key;
            }
            $exported_field_str = substr($exported_field_str, 0, -1);
            $sql = DB::table('hba_products');
            $sql->addSelect('product_id');
            foreach ($headerArrayName as $ex_c) {
                $sql->addSelect($ex_c);
            }
            if (isset($category_id)) {
                if (count($category_id) > 0) {
                    $cat_table_array = explode(',', $str_cat1);
                    $sql->leftJoin('hba_products_category', 'hba_products.product_id', '=', 'hba_products_category.products_id');
                    $sql->whereIn('hba_products_category.category_id', $cat_table_array);
                    $total_records  = count($sql->get()->toArray());
                }
            } else {
                $total_records  = $sql->get()->count();
            }
            $total_batch     = ceil($total_records / $end_limit) + 1;

            $export_insert_arr = array();
            $first_header_rows_arr = array();
            foreach ($tot_cols as $key => $val) {
                $exported_field_str .= "`" . $tot_cols[$key]['export_field'] . "`" . ",";
                $first_header_rows_arr[] = $key;
            }

            $exported_cols         = $first_header_rows_arr;
            $exported_field_str = substr($exported_field_str, 0, -1);

            $tot_cols = count($export_col_names);
            for ($mm = 0; $mm < $tot_cols; $mm++) {
                $export_insert_arr[trim($export_col_names[$exported_cols[$mm]]['export_field'])] = trim($export_col_names[$exported_cols[$mm]]['export_header_val']);
            }

            $request->session()->put('exported_field_str', $exported_field_str);
            $request->session()->put('exported_cols', $exported_cols);
            $request->session()->put('arr_category_id', $category_id);

            DB::table('hba_custom_export_products')->truncate();
            $insert_data = DB::table('hba_custom_export_products');
            $insert_data->insert($export_insert_arr);
        }

        $exported_field_str = $request->session()->get('exported_field_str');
        $exported_cols         = $request->session()->get('exported_cols');

        if (isset($category_id)) {
            $cat_table_array = explode(',', $str_cat1);
            if (count($cat_table_array) > 0) {
                $result->whereIn('hba_products_category.category_id', array_unique(array_values($cat_table_array)));
            }
        }

        $result  = $result->orderBy('hba_products.product_id')->offset($start_limit)->limit($end_limit)->get()->toArray();

        // $result = json_decode(json_encode($result), true);

        for ($i = 0; $i < count((array)$result); $i++) {
            // $data_arr = $result[$i];
            // extract($data_arr);

            $product_id = $result[$i]->product_id;
            $sku = isset($result[$i]->sku) ? $result[$i]->sku : "";
            $category = isset($result[$i]->category) ? $result[$i]->category : "";
            $product_name = isset($result[$i]->product_name) ? $result[$i]->product_name : "";
            $product_url = isset($result[$i]->product_url) ? $result[$i]->product_url : "";
            $retail_price = isset($result[$i]->retail_price) ? $result[$i]->retail_price : "";
            $our_price = isset($result[$i]->our_price) ? $result[$i]->our_price : "";
            $sale_price = isset($result[$i]->sale_price) ? $result[$i]->sale_price : "";
            $swatch_image = isset($result[$i]->swatch_image) ? $result[$i]->swatch_image : "";
            $main_image = isset($result[$i]->main_image) ? $result[$i]->main_image : "";
            $rollover_image = isset($result[$i]->rollover_image) ? $result[$i]->rollover_image : "";
            $additional_images = isset($result[$i]->additional_images) ? $result[$i]->additional_images : "";

            $product_category = '';
            $category_res = Get_Product_Category($product_id);
            if (is_array($category_res)) {
                for ($c = 0; $c < count($category_res); $c++) {
                    if (!empty($category_res[$c]->category_id))
                        $product_category .= Get_Category_Structure($category_res[$c]->category_id) . "#";
                }
            }
            $product_category = substr($product_category, 0, strlen($product_category) - 1);
            $price = "";

            $export_insert_arr = array();
            $tot_cols = count($exported_cols);

            $gen_csv_fields_arr = $export_col_names;

            for ($hk = 0; $hk < $tot_cols; $hk++) {
                if ($exported_cols[$hk] == "category") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $product_category;
                } else if ($exported_cols[$hk] == "main_image") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->main_image != "0" ? $result[$i]->main_image : "";
                } else if ($exported_cols[$hk] == "rollover_image") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->rollover_image != "0" ? $result[$i]->rollover_image : "";
                } else if ($exported_cols[$hk] == "additional_images") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->additional_images != "0" ? $result[$i]->additional_images : "";
                } else if ($exported_cols[$hk] == "price") {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = $result[$i]->our_price;
                } else {
                    $export_insert_arr[trim($gen_csv_fields_arr[$exported_cols[$hk]]['export_field'])] = trim(${$gen_csv_fields_arr[$exported_cols[$hk]]['product_field']});
                }
            }

            $delimiter = ',';
            $enclosure = '"';
            $escape_char = "\\";
            $insert_data = DB::table('hba_custom_export_products');
            $insert_data->insert($export_insert_arr);
        }

        $this->Make_Product_CSV("hba_custom_export_products", $export_file_path, $start_limit, $end_limit, $exported_field_str, $exported_cols, $export_col_names);

        $start_limit    = $start_limit + $end_limit;
        $process_batch  = $process_batch + 1;

        if ($process_batch == $total_batch) {
            $request->session()->forget('exported_field_str');
            $request->session()->forget('exported_cols');
            $request->session()->forget('arr_category_id');
            $request->session()->forget('filename');
            Cache::forget('customexportfilename');
            return redirect()->route('pnkpanel.product.headerexport')->with(['filenamecustom' => $export_file_name]);
        } else {
            return view('pnkpanel.export_products.post_custom_export_product', compact('start_limit', 'end_limit', 'process_batch', 'total_batch'));
        }
    }

    function general_info_arr($general_information)
    {
        $info_arr = array();
        if ($general_information != '') {
            $arr = explode("#", $general_information);
            for ($i = 0; $i < count($arr); $i++) {
                $arr2 = explode(":", $arr[$i]);
                if (isset($arr2[0]) && isset($arr2[1])) {
                    $info_arr = array_merge($info_arr, array($arr2[0] => $arr2[1]));
                }
            }
        }
        return $info_arr;
    }

    function get_product_material($product_id)
    {
        $result = ProductsMaterial::select(DB::raw('group_concat(material_id) AS material_ids'))->where('products_id', $product_id)->get()->toArray();
        $material_ids = $result[0]['material_ids'];
        if ($material_ids != "") {
            $mat_id_arr = explode(",", $material_ids);
            $mateial_result = Material::whereIn('material_id', $mat_id_arr)->select(DB::raw('group_concat(material_name) AS material_names'))->get()->toArray();
            if (count($mateial_result) > 0) {
                return str_replace(",", ":", $mateial_result[0]['material_names']);
            }
        }
        return "";

        //$result = ProductsMaterial::where('products_id', $product_id)->select(group_concat(material_id) as material_ids)->get()->toArray();
        //return $material_ids; //json_encode($result);

        //$result = DB::table("hba_material")->select('material_name')->where('material_id', '1')->get()->toArray();
        //$result = DB::table("")
        //return $product_id;
        /*$result = Material::whereIn('material_id',function($query){
            $query->select('material_id')
                ->from(with(new ProductsMaterial)->getTable())
                ->where('products_id',$products_id)
                ->where('status','1');
        })->get();
        if(count($result) > 0){
            print_r($result);
            echo "------------";
        }*/
    }

    function Make_Product_CSV($export_table, $export_file_path, $start_limit, $end_limit, $exported_field_str, $exported_cols, $gen_csv_fields_arr)
    {

        $time_limit = ini_get('max_execution_time');
        $memory_limit = ini_get('memory_limit');

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        // set_time_limit(0);
        if ($export_table) {
            $fp = fopen($export_file_path, "a+");

            $startlimit = $start_limit - 33400;
            // $result = DB::table($export_table)->select('*')->orderBy('import_product_id')->offset($start_limit)->limit($end_limit)->get()->toArray();
            $result = DB::table($export_table)->select('*')->orderBy('import_product_id')->offset($startlimit)->limit($end_limit)->get()->toArray();
            $total_fields = count($exported_cols);
            $str = "";
            for ($m = 0; $m < count($result); $m++) {
                $prod_data = $result[$m];

                for ($h = 0; $h < $total_fields; $h++) {
                    $field_val = $prod_data->{$gen_csv_fields_arr[$exported_cols[$h]]['export_field']};

                    $field_val = str_replace('"', '""', $field_val);

                    if ($h == $total_fields - 1) {
                        $str .= "\"" . $field_val . "\"";
                    } elseif ($h == 0) {
                        $str .= "\"" . $field_val . "\",";
                    } else {
                        $str .= "\"" . $field_val . "\",";
                    }
                }
                $str  .= "\n";
            }

            fwrite($fp, $str);
            fclose($fp);
        }

        set_time_limit($time_limit);
        ini_set('memory_limit', $memory_limit);
    }

    public function import_view()
    {
      
        $checkFilds = "SHOW COLUMNS FROM hba_import_products";
        $fieldArr = DB::select($checkFilds);
        $gen_csv_fields_arr = config('importproduct');
        foreach($fieldArr as $fieldArrKey => $fieldArrValue){
            //dd($fieldArrValue->Field);
            if(isset($gen_csv_fields_arr[$fieldArrValue->Field])){
                $fieldArr[$fieldArrKey]->fieldName = $gen_csv_fields_arr[$fieldArrValue->Field]['import_header_val'];
            }else{
                $fieldArr[$fieldArrKey]->fieldName = $fieldArrValue->Field;
            }
        }
        //dd($fieldArr);
        $pageData['columns'] = $fieldArr;

        //dd($pageData);
        $pageData['page_title'] = "Import Products";
        $pageData['meta_title'] = "Import Products";
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Import Products',
                'url' => route('pnkpanel.product.import')
            ]
        ];
        return view('pnkpanel.import_products.import_product_view')->with($pageData);
    }

    public function import(Request $request)
    {
        set_time_limit('-1');
        ini_set('memory_limit', '-1');

        ini_set('upload_max_filesize', '1024M');
        ini_set('post_max_size', '1024M');
        ini_set('max_input_time', 360000);
        ini_set('max_execution_time', 360000);
        global $gen_csv_fields_arr, $field_list;

        $gen_csv_fields_arr = config('importproduct');

        $gen_required_fields = config('requiredfields.gen_required_fields');

        $field_list = '';
        $tablename = "hba_import_products";
        $first_header_rows_arr = array();

        $filename = $request->file('import_product_file')->getClientOriginalName();
        $file_path = config('const.IMPORT_CSV_PATH') . $filename;


        if (File::exists($file_path)) {
            File::delete($file_path);
        }
        $file = $request->file('import_product_file');
        $file->move(config('const.IMPORT_CSV_PATH'), $filename);

        ########### Empty Import Tables
        DB::table($tablename)->truncate();

        if (File::exists($file_path)) {
            $handle = fopen($file_path, "rb");
            $rec_counter = 1;
            $tot_success = 0; ## success to insert record counter
            $tot_failure = 0; ## fail to insert record counter

            $getfilesizevar = filesize($file_path);

            while ($data = fgetcsv($handle, $getfilesizevar, ",")) {

                ## To process the CSV header rows Start 
                if ($rec_counter == 1) {
					
                    foreach ($data as $field_num => $field_value) {
                        $col_header_name = trim($field_value);
                        $col_header_name = trim(str_replace(" ", "_", strtolower($col_header_name)));
						//dd($col_header_name,$gen_csv_fields_arr);
                        if (!array_key_exists($col_header_name, $gen_csv_fields_arr) && !in_array($col_header_name, array())) {

                            $err_msg  = "<br>Invalid column name <B>'" . $field_value . "'</B> found.";
                            $err_msg .= "Please follow the sample csv format.";
                            // $err_msg = rawurlencode($err_msg);
                            return redirect()->route('pnkpanel.product.import')->with('error', $err_msg);
                        }

                        ## stored the first header row column names in the array
                        $first_header_rows_arr[] = $col_header_name;
                    }
                    ## To check required columns in the products_new csv start

                    $tot_cols = count($gen_required_fields);

                    for ($tc = 0; $tc < $tot_cols; $tc++) {
                        if (!in_array($gen_required_fields[$tc], $first_header_rows_arr)) {
                            $err_msg  = ucwords(str_replace("_", " ", $gen_required_fields[$tc]));
                            $err_msg .= " column(s) are required. ";
                            $err_msg .= "Please follow the sample csv format.";
                            // $err_msg = rawurlencode($err_msg);
                            return redirect()->route('pnkpanel.product.import')->with('error', $err_msg);
                            // exit;
                        }
                    }
                    ## To check required columns in the products_new csv end 

                    ## Generate the sql query strings database fields
                    $a = array_map('map_array', $first_header_rows_arr);

                    $field_list = substr($field_list, 0, -1);

                    $field_list = str_replace("`", "", $field_list);
                    $request->session()->put('sess_import_csv_field', $field_list);
                }

                $field_listval = explode(",", $field_list);

                ## To process the CSV header rows end 
                
                $request->session()->put('sess_first_header_row_arr', $first_header_rows_arr);

                $insert_imp_arr = array();
                $general_info_string = "";

                for ($tc = 0; $tc < count($first_header_rows_arr); $tc++) {

                    if (array_key_exists($first_header_rows_arr[$tc], $gen_csv_fields_arr)) {
                        // $import_field_name = $gen_csv_fields_arr[$first_header_rows_arr[$tc]]['import_field'];

                        // $fieldvalue = trim(addslashes($data[$tc]));

                        $import_field_name = $gen_csv_fields_arr[$first_header_rows_arr[$tc]]['import_field'];



                        if ($import_field_name == 'product_name' || $import_field_name == 'short_description' || $import_field_name == 'product_description' ) {
                            $str = stripslashes($data[$tc]);
                            $str = utf8_encode($str);
                            $str = str_replace("", "'", $str);
                            $fieldvalue = trim($str);
                        } else {
                            $fieldvalue = trim(addslashes($data[$tc]));
                        }

                        if ($import_field_name != '') {
                            $insert_imp_arr[trim($import_field_name)] = $fieldvalue;
                        }
                    } else {
                        
                        if ($data[0] != 'Sku') {

                            $general_info_string = stripslashes($general_info_string);
							$general_info_string = utf8_encode($general_info_string);
							$general_info_string = str_replace("", "'", $general_info_string);
							$general_info_string = trim($general_info_string);
							if($data[$tc] != "")
							{
								echo "<pre>"; print_r($general_info_string); 
							}
                            isset($data[$tc]) && !empty($data[$tc]) ? $general_info_string .= $first_header_rows_arr[$tc] . ":" . trim(addslashes($data[$tc])) . "#" : '';
                        }
                        //}
                    }
                   
                }

                  DB::table($tablename)->insert($insert_imp_arr);

                $rec_counter = $rec_counter + 1;
            }
            
            ## While End
            return redirect()->route('pnkpanel.product.post_import');
        }
        ## If End file exits
    }

    public function import_batch(Request $request)
    {
        global     $err_fp;
        //echo "11"; exit;
        $tablename = "hba_import_products";
        $gen_csv_fields_arr = config('importproduct');
        if ($request->start_limit != '') {
            $start_limit = $request->start_limit;
        } else {
            $start_limit = 0;
        }
        $end_limit = 100;
        $total_record         = $request->total_record != '' ? $request->total_record : 0;
        $show_error_report     = $request->show_error_report;

        if ($start_limit == 0) {
            $total_record     = DB::table($tablename)->select('*')->where('sku', '<>', "SKU")->count();
            $show_error_report = 0;
            ## Generated error report during processing products_new start
            $error_report_file_path = config('const.IMPORT_CSV_PATH') . "Error_Report.csv";
            if (File::exists($error_report_file_path)) {
                File::delete($error_report_file_path);
            }

            $err_fp = fopen($error_report_file_path, "a+");

            $csv_fields_arr = $request->session()->get('sess_first_header_row_arr');
            
            $tot_csv_fields = count($csv_fields_arr);

            if ($tot_csv_fields > 0) {
                $products_header_str = '"Error Type","Error Message",';

                for ($hd = 0; $hd < $tot_csv_fields; $hd++) {
                    if (isset($gen_csv_fields_arr[$csv_fields_arr[$hd]]['import_header_val'])) {
                        $products_header_str .= '"' . str_replace('"', '""', $gen_csv_fields_arr[$csv_fields_arr[$hd]]['import_header_val']) . '",';
                    }
                }

                if (trim($products_header_str) != '') {
                    $products_header_str = substr($products_header_str, 0, -1);
                    $products_header_str .= "\n";
                    fwrite($err_fp, $products_header_str);
                    fclose($err_fp);
                }
            }
            ## Generated error report during processing products_new end	
        }
        // Log::info(print_r("start_limit intial = ".$start_limit, true));
        Log::info(print_r("total_record = " . $total_record, true));
        if ($start_limit <= $total_record) {
            $csv_fields = $request->session()->get('sess_import_csv_field');
            $result = DB::table($tablename)->select('*')->where('sku', '<>', "SKU")->orderBy('import_product_id')->offset($start_limit)->limit($end_limit)->get()->toArray();

           $error_report_file_path =  config('const.IMPORT_CSV_PATH') . "Error_Report.csv";

            $err_fp = fopen($error_report_file_path, "a+");

            if (isset($result) && count($result) > 0) {
                $this->ProductDataExecution($result, $request);
                // sleep(2);
            }

            fclose($err_fp);
            return view("pnkpanel.import_products.import_process", compact('start_limit', 'end_limit', 'show_error_report', 'total_record'));
            } else {

            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            $email_subject = "Product Data Import";
            $email_body = 'Product Data Import ' . date('Y-m-d H:i:s') . ' Approx Count : ' . $start_limit;
          //  $from = "cs@hbasales.com";
           // $to = 'sachin.qualdev@gmail.com';
            @mail($to, $email_subject, $email_body, $from);
            @mail('sachin.qualdev@gmail.com', $email_subject, $email_body, $from);

            $request->session()->forget('sess_import_csv_field');
            $request->session()->forget('sess_first_header_row_arr');
            return redirect()->route('pnkpanel.product.import')->with(['message' => "Products Imported Successfully."]);
        }
    }

   
    function ProductDataExecution($result, $request)
    {
       
        global $gen_csv_fields_arr, $gen_required_fields, $show_error_report, $err_fp, $manufacture_id;
        $gen_csv_fields_arr = config('importproduct');
        $gen_required_fields = config('requiredfields.gen_required_fields');
        $csv_fields_arr         = array();
        $csv_fields_arr         =  $request->session()->get('sess_first_header_row_arr');
     
        $err_report_data_row     = '';
        for ($i = 0; $i < count($result); $i++) {

            $product_arr = $result[$i];

            $sku                  = trim($product_arr->sku);
			$sku 					= ltrim($sku,"H-");
			//echo $sku; exit;
            $imp_data_arr          = array();
            $missing_col_val_arr = array();

            $error_flag  = false;
            $err_report_prod_data     = '';
            $field_array = array();
            ##  csv fields for loop Start
            for ($cnt = 0; $cnt < count($csv_fields_arr); $cnt++) {
            if (isset($gen_csv_fields_arr[$csv_fields_arr[$cnt]]['import_field'])) {
                    $import_field_name     = $gen_csv_fields_arr[$csv_fields_arr[$cnt]]['import_field'];
                    //dd($gen_csv_fields_arr);
                    $product_field_name = $gen_csv_fields_arr[$csv_fields_arr[$cnt]]['product_field'];
                    
                    $fieldvalue         = trim($product_arr->$import_field_name);
                    //echo "<pre>"; print_r($import_field_name); exit;
                    $product_search_keywords = "";
                    array_push($field_array, $product_field_name);
                    if (in_array($csv_fields_arr[$cnt], $gen_required_fields) && $fieldvalue == '') {
                        $error_flag = true;
                        $missing_col_val_arr[] = $gen_csv_fields_arr[$csv_fields_arr[$cnt]]['import_header_val'];
                    } 
                    else 
                    {
                        
                       if ($product_field_name != '') 
                           {
                            if($product_field_name == 'brand') 
                            {
                                $brand_id    =    "";
                                if ($fieldvalue != '') {
                                    $brand_id    =    Check_Brand_Exist(addslashes($fieldvalue));
                                }
                                
                                ## Add Brand Name In Prod Table
                                // $imp_data_arr['brand_name'] = addslashes(Get_Brand_Name((int)$brand_id));
                                $imp_data_arr['brand_id'] = $brand_id;
                            } 
                            if ($product_field_name == 'manufacturer'){
                                $manufacturer_id    =    "";
                                if ($fieldvalue != '') {
                                    $manufacturer_id    =    Check_Manufacture_Exist(addslashes($fieldvalue));
                                }
                                
                                ## Add Brand Name In Prod Table
                                // $imp_data_arr['brand_name'] = addslashes(Get_Brand_Name((int)$brand_id));
                                $imp_data_arr['manufacturer_id'] = $manufacturer_id;
                            } 
                            if ($product_field_name == "size_dimension"){
                                $str = str_replace('?', 'X', $fieldvalue);
                                $str = str_replace('×', 'X', $str);
                                $str = stripslashes($str);
                                $str = preg_replace('/\\\\/', '', $str);
                                //$str = html_entity_decode($str);

                                $imp_data_arr[$product_field_name] = stripslashes($str);
                                //echo $imp_data_arr[$product_field_name]."<br>++++++++<br>";
                            } 
                            elseif ($product_field_name == "product_name"){
                                $pname = str_replace('//', '', $fieldvalue);
                                $imp_data_arr[$product_field_name] = stripslashes($pname);
                            } 
                            elseif ($product_field_name == "product_description") {
                                $imp_data_arr[$product_field_name] = stripslashes($fieldvalue);
                            } elseif ($product_field_name == "retail_price") {
                                $imp_data_arr[$product_field_name] = str_replace("$", "", $fieldvalue);
                            } elseif ($product_field_name == "our_price") {
                                $imp_data_arr[$product_field_name] = str_replace("$", "", $fieldvalue);
                            } elseif ($product_field_name == "sale_price") {
                                $imp_data_arr[$product_field_name] = str_replace("$", "", $fieldvalue);
                            } elseif ($product_field_name == "status") {
                                $imp_data_arr[$product_field_name] = "1";
                            } 
                           
                            else {
                                $imp_data_arr[$product_field_name] = addslashes($fieldvalue);
                            }
                        }
                    }
                    $err_report_prod_data .= '"' . str_replace('"', '""', $fieldvalue) . '",';
           } else {
                  //  $ginfo        = trim($product_arr->general_information);

                    $ginfo = str_replace('?', 'X', $ginfo);
                    $ginfo = str_replace('×', 'X', $ginfo);
                    $ginfo = stripslashes($ginfo);
                    $ginfo = preg_replace('/\\\\/', '', $ginfo);
                    $ginfo = html_entity_decode($ginfo);
                    //echo "<pre>"; print_r($fieldvalue); exit;
                   // $imp_data_arr["general_information"] = $ginfo;
            }
                //echo $product_arr["general_information"]; exit;
            }
            //exit;
            ## csv fields for loop end

            /*if (isset($imp_data_arr['main_image']) && empty($imp_data_arr['main_image'])) {
                $imp_data_arr['status'] = '0';
            }*/

            $err_report_prod_data = substr($err_report_prod_data, 0, -1);

            // $checkImageExist[0]
			//dd($imp_data_arr);
            ## If No Error Insert Or Update Prodcut Start
            if (!$error_flag) {
                ## check the product is alredy exists or not 
                $checkProduct = Product::select('product_id')->where('sku', strtolower($sku))->get()->toArray();
                $checkProduct = json_decode(json_encode($checkProduct), true);
                // dd($checkProduct);
                $imp_data_arr['product_feed_update_track'] = 0;
                $imp_data_arr['is_sumitted_yotpo'] = 0;
                $imp_data_arr['sku'] = ltrim($imp_data_arr['sku'],"H-");
				if(empty($imp_data_arr['product_group_code']))
				{
					$imp_data_arr['product_group_code'] = $imp_data_arr['sku'];
				}
                if (count($checkProduct) > 0) {

                    /* for spars stop update - start */
                    unset($imp_data_arr['quantity']);
                    unset($imp_data_arr['product_url']);
                    $imp_data_arr['product_feed_update_track'] = 2;

                    
                    $product_id = intval($checkProduct[0]["product_id"]);
                    /****************************************************/
                   
                    if ($imp_data_arr['status'] == 2) {
                        
                    }
                    /****************************************************/
                    $imp_data_arr['updated_datetime'] = Carbon::now();
                    $update_product = Product::find($product_id);
                    
                    $update_product->update($imp_data_arr);
                } else {
                    $imp_data_arr['product_feed_update_track'] = 1;
                    unset($imp_data_arr['quantity']);
                  
                    if ($imp_data_arr['status'] == 2) {
                        continue;
                    }
                    /****************************************************/

                    $imp_data_arr['added_datetime'] = Carbon::now();
                    $update_product = Product::create($imp_data_arr);
                    $product_id = $update_product->product_id;
                }

                ## Category Relation Insert Start ##
                //$category_name = $product_arr["type"] . ":" . $product_arr["category"];
                $category_name = $product_arr->category;

                if (in_array("category", $csv_fields_arr) && trim($category_name) != "" && $product_id > 0) {
                    
                    ProductsCategory::where('products_id', $product_id)->delete();

                    $cat_array = explode("#", trim($category_name));

                    for ($x = 0; $x < count($cat_array); $x++) {
                        $categoryArray = array();
                        if (trim($cat_array[$x]) != '') {
                            $categoryArray = Check_Product_Category(trim($cat_array[$x])); # Category Insert

                            $category_id = $categoryArray['categoryId'];
                            $parentId = $categoryArray['parentId'];
                            $parent_product_id = $categoryArray['parent_product_id'];

                            Insert_In_Prod_Cat($category_id, $product_id);     # Product Category Relation
                            if ($x == 0) {
                                $update_parent_id = Product::find($product_id);
                                $update_parent_id->parent_category_id = $parent_product_id;
                                $update_parent_id->save();
                            }
                        }
                    }
                }
                ## Category Relation Insert End ##
             }

            ## If Error Then Write Reports based on the required fields values and valid categories Start
            if ($error_flag) {
                $err_report_data_row .= '"Error",';

                $data_err_msg = '';
                if (count($missing_col_val_arr) > 0)
                    $data_err_msg .= "[" . implode(",", $missing_col_val_arr) . "] column(s) values must be required.";

                $err_report_data_row .= '"' . $data_err_msg . '",';
                $err_report_data_row .= $err_report_prod_data;
                $err_report_data_row .= "\n";

                $show_error_report = 1;
                return redirect()->route('pnkpanel.product.import')->with('error', $err_report_data_row);
            }

            ## If Error Then Write Reports based on the required fields values and valid categories End
        }

        ## Write to Error Reporting file.
        fwrite($err_fp, $err_report_data_row);
    }   
}
