<?php



namespace App\Http\Controllers\Pnkpanel;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\TaxArea;

use App\Models\TaxAreaRates;

use App\Models\Country;

use App\Models\State;

use App\Imports\TaxImports;

use App\Models\ImportTaxRules;

use App\Models\ShippingMode;

use App\Models\ShippingCharge;

use App\Models\PaymentMethod;

use App\Models\StaticPages;

use App\Models\Product;

use App\Models\ShippingSetting;

use App\Models\ShippingRule;

use App\Models\ShippingRate;
use App\Models\InstagramSettings;

use Carbon\Carbon;

use DataTables;

use File;

use DB;

use Illuminate\Support\Facades\Validator;

use Intervention\Image\ImageManagerStatic as Image;

use Maatwebsite\Excel\Facades\Excel;

use App\Exports\QuoteExports;

use App\Exports\SampleRequestExports;

use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;
use GlobalHelper;






class StoreSettingsController extends Controller

{

    use CrudControllerTrait;



    public function model()

    {   

        $action_method = explode('/', url()->full());

       

        if (in_array("testimonial", $action_method)) {

            return Testimonial::class;

        } elseif (in_array("tax-area", $action_method)) {

            return TaxArea::class;

        } elseif (in_array("tax-rate-edit", $action_method)) {

            return TaxAreaRates::class;

        } elseif (in_array("shipping-method", $action_method)) {

            return ShippingMode::class;

        } elseif (in_array("shipping-method-charge", $action_method)) {

            return ShippingCharge::class;

        } elseif (in_array("payment-method", $action_method)) {

            return PaymentMethod::class;

        } elseif (in_array("manage-static-page", $action_method)) {

            return StaticPages::class;

        }elseif (in_array("shipping-rule", $action_method)) {

            return ShippingRule::class;

        }



    }



    public function tax_area_list()

    {

        if (request()->ajax()) {

            $model = TaxArea::select('*');

            $table = DataTables::eloquent($model);

            $table->addIndexColumn();

            $table->addColumn('checkbox', function ($row) {

                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->tax_areas_id . '" />';

            });

            $table->editColumn('status', function ($row) {

                return ($row->status == 1 ? 'Active' : 'Inactive');

            });

            $table->editColumn('action', function ($row) {

                $tax_area_rate = '<a href="javascript:void(0);" data-toggle="tooltip" data-id="' . $row->tax_areas_id . '" data-original-title="EditTaxRate" title="Edit Tax Rate" class="btn btn-sm btn-primary btnEditTaxAreaRateRecord">Add/Edit Tax Rate</i></a>';

                $action = $tax_area_rate . ' ' . (string)view('pnkpanel.component.datatable_action', ['id' => $row->tax_areas_id]);

                return $action;

            });

            $table->rawColumns(['checkbox', 'action']);

            return $table->make(true);

        }



        $pageData['page_title'] = "Tax Area";

        $pageData['meta_title'] = "Tax Area";

        $pageData['breadcrumbs'] = [

            [

                'title' => 'Tax Area',

                'url' => route('pnkpanel.tax-area.list')

            ]

        ];



        return view('pnkpanel.tax_area.tax_area_list')->with($pageData);

    }



    public function tax_area_edit($id = 0)

    {

        if ($id > 0) {

            $tax_area = TaxArea::findOrFail($id);

            $country = Country::where('status', '1')->get();

            $state = State::all();

        } else {

            $tax_area =  new TaxArea;

            $country = Country::where('status', '1')->get();

            $state = State::all();

        }

        $prefix = ($id > 0 ? 'Edit' : 'Add New');

        $pageData['page_title'] = $prefix . ' Tax Area';

        $pageData['meta_title'] = $prefix . ' Tax Area';

        $pageData['breadcrumbs'] = [

            [

                'title' => 'Tax Area',

                'url' => route('pnkpanel.tax-area.list')

            ],

            [

                'title' => $prefix . ' Tax Area',

                'url' => route('pnkpanel.tax-area.edit', $id)

            ]

        ];

        return view('pnkpanel.tax_area.tax_area_edit', compact('tax_area', 'country', 'state'))->with($pageData);

    }



    public function tax_area_rate_list($id)

    {

        if (request()->ajax()) {

            $model = TaxAreaRates::select('*')->where('tax_areas_id', $id);

            $table = DataTables::eloquent($model);

            $table->addIndexColumn();

            $table->addColumn('checkbox', function ($row) {

                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->tax_rates_id . '" />';

            });

            $table->editColumn('action', function ($row) {

                $action = (string)view('pnkpanel.component.datatable_action', ['id' => $row->tax_rates_id]);

                return $action;

            });

            $table->rawColumns(['checkbox', 'action']);

            return $table->make(true);

        } else {

            return redirect(route('pnkpanel.tax-area.list'));

        }

    }



    public function tax_area_rate_edit($id = 0, $editid = 0)

    {

        if ($editid > 0) {

            $tax_area = TaxArea::findOrFail($id);

            $tax_area_rate = TaxAreaRates::findOrFail($editid);

            $country = Country::where('countries_iso_code_2', 'US')->get();

            $state = State::all();

        } else {

            $tax_area = TaxArea::findOrFail($id);

            $tax_area_rate = new TaxAreaRates;

            $country = Country::where('countries_iso_code_2', 'US')->get();

            $state = State::all();

        }

        $prefix = ($editid > 0 ? 'Edit' : 'Add New');

        $pageData['page_title'] = $prefix . ' Tax Area';

        $pageData['meta_title'] = $prefix . ' Tax Area';

        $pageData['breadcrumbs'] = [

            [

                'title' => 'Tax Area',

                'url' => route('pnkpanel.tax-area.list')

            ],

            [

                'title' => $prefix . ' Tax Area',

                'url' => route('pnkpanel.tax-area.edit', $editid)

            ]

        ];

        return view('pnkpanel.tax_area.tax_area_rate_edit', compact('tax_area', 'tax_area_rate', 'country', 'state'))->with($pageData);;

    }



    public function tax_area_rate_update(Request $request)

    {

        $actType = $request->actType;

        $tax_areas_id = $request->id;

        $tax_rates_id = $request->tax_rates_id;

        $tax_area_rate = TaxAreaRates::findOrNew($tax_rates_id);

        $this->validate($request, [

            'amount_from' => 'required',

            'charge_amount' => 'required',

            'amount_in_percent' => 'required'

        ],

        [

            'amount_from.required' => 'Amount from is required',

            'charge_amount.required' => 'Charge Percentage is required',

            'amount_in_percent.required' => 'Percentage is required',

        ]);

        $tax_area_rate->amount_from = $request->amount_from;

        $tax_area_rate->charge_amount = $request->charge_amount;

        $tax_area_rate->amount_in_percent = $request->amount_in_percent;

        $tax_area_rate->tax_areas_id = $tax_areas_id;

        if ($tax_area_rate->save()) {

            if ($actType == 'add') {

                session()->flash('site_common_msg', config('messages.msg_add'));

                return redirect()->route('pnkpanel.tax-area.tax_area_rate_edit', [$tax_areas_id, $tax_area_rate->tax_rates_id]);

            } else {

                session()->flash('site_common_msg', config('messages.msg_update'));

            }

        } else {

            session()->flash('site_common_msg_err', config('messages.msg_add_err'));

        }

        return redirect()->route('pnkpanel.tax-area.list', $tax_area_rate->tax_rates_id);

    }



    public function tax_area_update(Request $request)

    {

        $actType = $request->actType;

        $id = $request->id;

        $tax_area = TaxArea::findOrNew($id);

        $this->validate($request, [

            'country' => 'required|string',

            'state' => 'required|string',

            'status' => 'required'

        ],

        [

            'country.required' => 'Country is required',

            'state.required' => 'State is required',

            'status' => 'Status is required'

        ]);

        $tax_area->country = $request->country;

        $tax_area->states = $request->state;

        $tax_area->tax_region_name = $request->tax_region_name ? $request->tax_region_name : '';

        isset($request->zip_from) ? $tax_area->zip_from = $request->zip_from : '';

        isset($request->zip_to) ? $tax_area->zip_to = $request->zip_to : '';

        $tax_area->status = $request->status;

        if ($tax_area->save()) {

            if ($actType == 'add') {

                session()->flash('site_common_msg', config('messages.msg_add'));

                return redirect()->route('pnkpanel.tax-area.list', $tax_area->tax_areas_id);

            } else {

                session()->flash('site_common_msg', config('messages.msg_update'));

            }

        } else {

            session()->flash('site_common_msg_err', config('messages.msg_add_err'));

        }

        return redirect()->route('pnkpanel.tax-area.list', $tax_area->tax_areas_id);

    }



    public function tax_area_delete($id)

    {

        if (TaxArea::findOrFail($id)->delete()) {

            session()->flash('site_common_msg', config('messages.msg_delete'));

        } else {

            session()->flash('site_common_msg_err', config('messages.msg_delete_err'));

        }

        return redirect()->route('pnkpanel.tax-area.list');

    }



    public function tax_area_rate_delete($id)

    {

        if (TaxAreaRates::findOrFail($id)->delete()) {

            session()->flash('site_common_msg', config('messages.msg_delete'));

        } else {

            session()->flash('site_common_msg_err', config('messages.msg_delete_err'));

        }

        return redirect()->route('pnkpanel.tax-area.list');

    }



    public function import_tax_rules_and_rates()

    {

        $pageData['page_title'] = "Import Tax Rules And Rates";

        $pageData['meta_title'] = "Import Tax Rules And Rates";

        $pageData['breadcrumbs'] = [

            [

                'title' => 'Tax Area',

                'url' => route('pnkpanel.tax-area.list')

            ],

            [

                'title' => ' Import Tax Rules And Rates',

                'url' => route('pnkpanel.tax-area.import_tax_rules_and_rates')

            ],

        ];

        $country = Country::where('status','1')->get();

        return view('pnkpanel.tax_area.import_tax_rules_rates', compact('country'))->with($pageData);

    }



    public function import_tax_csv_files(Request $request)

    {

        $gen_csv_fields_arr = config('importtaxfields');

        $gen_required_fields = config('importtaxreqfield.gen_required_fields');



        $validator = Validator::make($request->all(), [

            'csv_file' => 'required|mimes:csv,txt'		

        ],[

            'csv_file.required' => "Please browse CSV file",

            'csv_file.mimes' => "Please upload only the CSV file"

            ]

        );

        if($validator->fails())

        {

            return redirect()->back()->withErrors($validator)->withInput();

        }



        $filename = $request->file('csv_file')->getClientOriginalName();

        $file_path = config('const.IMPORT_CSV_PATH') . $filename;

        if (File::exists($file_path)) {

            File::delete($file_path);

        }

        $oldfile = $request->file('csv_file');

        $file = $request->file('csv_file');

        $file->move(config('const.IMPORT_CSV_PATH'), $filename);

        $handle = fopen($file_path, "rb");

        $rec_counter = 1;



        $getfilesizevar = filesize($file_path);

        $filedata = fgetcsv($handle, $getfilesizevar, ",");

        while($data = fgetcsv($handle, $getfilesizevar, ",")) 

        { 

            ## To process the CSV header rows Start 

            if($rec_counter==1)

            {  

                $count = 0;

                foreach($filedata as $field_num => $field_value)

                {	

                    $col_header_name = trim($field_value);

                    $col_header_name = trim(str_replace(" ","_",strtolower($col_header_name)));                  

                    if($count==0){

                        if(!array_key_exists($col_header_name,$gen_csv_fields_arr))

                        {

                            $err_msg  = "<br>Invalid column name <B>'".$field_value."'</B> found.";

                            $err_msg .= "Please follow the sample csv format."; 

                            

                            session()->flash('site_common_msg_err', $err_msg);

                            return redirect()->route('pnkpanel.tax-area.import_tax_rules_and_rates');

                        }

                    }

                    $count++;

                    ## stored the first header row column names in the array

                    $first_header_rows_arr[] = $col_header_name;				

                }

                

                ## To check required columns in the products csv start

                $tot_cols = count($gen_required_fields);

                

                for($tc=0;$tc<$tot_cols;$tc++)

                {

                    

                    if(!in_array($gen_required_fields[$tc],$first_header_rows_arr))

                    {

                        $err_msg  = ucwords(str_replace("_"," ",$gen_required_fields[$tc]));

                        $err_msg .= " column(s) are required. "; 

                        $err_msg .= "Please follow the sample csv format."; 

                        session()->flash('site_common_msg_err', $err_msg);

                        return redirect()->route('pnkpanel.tax-area.import_tax_rules_and_rates');

                    }

                }

            } 

            $rec_counter++;

        }





        $truncate = ImportTaxRules::truncate();

        Excel::import(new TaxImports, config('const.IMPORT_CSV_PATH').'/'.$filename);

        $import_tax_rules = ImportTaxRules::all();

        foreach ($import_tax_rules as $rules) {

            if ($rules->Country == '') {

                $rules->Country = "US";

            }

            $tax_areas = TaxArea::select('tax_areas_id')->where('zip_from', $rules->ZipCode)->where('zip_to', $rules->ZipCode)->where('states', $rules->State)->where('country', $rules->Country)->get();

            if (count($tax_areas) > 0) {

                if(empty($rules->ZipCode) || empty($rules->TaxRegionName) || empty($rules->EstimatedCombinedRate)){

                    session()->flash('site_common_msg_err', config('messages.msg_column_err'));

                    return redirect()->route('pnkpanel.tax-area.import_tax_rules_and_rates');

                }

                $taxRatesId = TaxAreaRates::where('tax_areas_id',$tax_areas[0]->tax_areas_id)->first();

                $taxRatesId->amount_from  = 0.01;

                $taxRatesId->amount_in_percent = "Y";

                $taxRatesId->charge_amount = $rules->EstimatedCombinedRate;

                $taxRatesId->save();

            } else {

                if(empty($rules->ZipCode) || empty($rules->TaxRegionName) || empty($rules->EstimatedCombinedRate)){

                    session()->flash('site_common_msg_err', config('messages.msg_column_err'));

                    return redirect()->route('pnkpanel.tax-area.import_tax_rules_and_rates');

                }

                $taxArea = new TaxArea();

                $taxArea->country = $rules->Country;

                $taxArea->states = $rules->State;

                $taxArea->zip_from = $rules->ZipCode;

                $taxArea->zip_to = $rules->ZipCode;

                $taxArea->tax_region_name = $rules->TaxRegionName;

                $taxArea->save();

                $taxa_area_id = $taxArea->tax_areas_id;



                $taxRatesId = new TaxAreaRates();

                $taxRatesId->tax_areas_id = $taxa_area_id;

                $taxRatesId->amount_from  = 0.01;

                $taxRatesId->amount_in_percent = "Y";

                $taxRatesId->charge_amount = $rules->EstimatedCombinedRate;

                $taxRatesId->save();

            }

        }



        if (count($import_tax_rules) > 0) {

            session()->flash('site_common_msg', config('messages.msg_tax_import'));

        } else {

            session()->flash('site_common_msg_err', config('messages.msg_tax_import_err'));

        }



        return redirect()->route('pnkpanel.tax-area.import_tax_rules_and_rates');

    }

    

	public function shipping_method_list(){

        if (request()->ajax()) {

            $model = ShippingMode::select('*');

            $table = DataTables::eloquent($model);

            $table->addIndexColumn();

            $table->addColumn('checkbox', function ($row) {

                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->shipping_mode_id . '" />';

            });

           

            $table->editColumn('shipping_title', function ($row) {
                $shipping_title = $row->shipping_title;
                return $shipping_title;
            });



            $table->editColumn('display_position', function ($row) {

                $display_position = '<input type="text" id="display_position_' . $row->shipping_mode_id . '" value="' . $row->display_position . '" class="form-control input-sm" size="8">';

                return $display_position;

            });

            $table->editColumn('status', function ($row) {

                return ($row->status == 1 ? 'Active' : 'Inactive');

            });

           

            $table->editColumn('action', function ($row) {

                $action = (string)view('pnkpanel.component.datatable_action', ['id' => $row->shipping_mode_id]);

                return $action;

            });

           // $table->rawColumns(['checkbox', 'name', 'display_position','action']);
           $table->rawColumns(['checkbox', 'shipping_title','display_position','status','action']);

            return $table->make(true);

        }



        $pageData['page_title'] = "Shipping Method List";

        $pageData['meta_title'] = "Shipping Method List";

        $pageData['breadcrumbs'] = [

            [

                'title' => 'Shipping Method List',

                'url' => route('pnkpanel.shipping-method.list')

            ]

        ];

       // $shipping_method = ShippingSetting::all();

        return view('pnkpanel.shipping_method.shipping_method_list')->with($pageData);

    }



    public function shipping_method_edit($id = 0) {

        if($id > 0) {

            $shipping_mode = ShippingMode::findOrFail($id);

        } else {

            $shipping_mode =  new ShippingMode;

        }

        $countryArray = getCountryBoxArray();

        $stateArray = getStateBoxArray();

        

        $prefix = ($id > 0 ? 'Edit' : 'Add New');

        $pageData['page_title'] = $prefix.' Shipping Mode';

        $pageData['meta_title'] = $prefix.' Shipping Mode';

        $pageData['breadcrumbs'] = [

             [

                 'title' => 'Shipping Method List',

                 'url' =>route('pnkpanel.shipping-method.list')

             ],

             [

                 'title' => $prefix.' Shipping Method',

                 'url' =>route('pnkpanel.shipping-method.edit', $id)

             ]

        ];

        return view('pnkpanel.shipping_method.edit', compact('id', 'shipping_mode', 'countryArray', 'stateArray'))->with($pageData);;

    }



    public function shipping_method_update(Request $request){

        $actType = $request->actType;

		$shipping_mode_id = $request->shipping_mode_id;



		$shipping_mode = ShippingMode::findOrNew($shipping_mode_id);

		

		$this->validate($request, [

			'shipping_title'	 			=> 'required|string',

			'detail'				=> 'required'

		]);

		

		$shipping_mode->shipping_title 	=  $request->shipping_title;

		$shipping_mode->detail = $request->detail;

		$shipping_mode->display_position 	=  $request->display_position;

		$shipping_mode->status 	=  $request->status;

		

		if($shipping_mode->save()) {

			if($actType == 'add') {

				session()->flash('site_common_msg', config('messages.msg_add'));

			} else {

				session()->flash('site_common_msg', config('messages.msg_update')); 

			}

		} else {

			session()->flash('site_common_msg_err', config('messages.msg_add_err'));

		}

        return redirect()->route('pnkpanel.shipping-method.list');

    }



    public function shipping_method_delete($id){

		if(ShippingMode::findOrFail($id)->delete()) {

			session()->flash('site_common_msg', config('messages.msg_delete')); 

		} else {

			session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 

		}

		return redirect()->route('pnkpanel.shipping-method.list');

	}

    //shipping rule

    public function shipping_rule_list(){

    if (request()->ajax()) {

            $model = ShippingRule::select('*');



            $table = DataTables::eloquent($model);

            $table->addIndexColumn();

            $table->addColumn('checkbox', function ($row) {

                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->shipping_rule_id . '" />';

            });
            $table->addColumn('shipping_mode_id', function ($row) {
                $ShippingMode = ShippingMode::where('shipping_mode_id', $row->shipping_mode_id)->get();
                $shipping_title = $ShippingMode[0]->shipping_title;
                return $shipping_title;
            });
           

         $table->editColumn('action', function ($row) {

                $action = (string)view('pnkpanel.component.datatable_action', ['id' => $row->shipping_rule_id]);

                return $action;

            });

            $table->rawColumns(['checkbox','action']);

            return $table->make(true);

        }



        $pageData['page_title'] = "Shipping Rule List";

        $pageData['meta_title'] = "Shipping Rule List";

        $pageData['breadcrumbs'] = [

            [

                'title' => 'Shipping Rule List',

                'url' => route('pnkpanel.shipping-rule.list')

            ]

        ];

        $shipping_method = ShippingSetting::all();

        return view('pnkpanel.shipping_rule.shipping_rule_list')->with($pageData);

    }



    public function shipping_rule_edit($id = 0) {

        if($id > 0) {

            $shipping_rule = ShippingRule::findOrFail($id);

           // dd($shipping_rule['state']);

             $country = Country::where('status', '1')->get();

             $state = State::all();

             $shipping_rate = ShippingRate::where('shipping_rule_id',$id)->get()->toarray();

             $cnt_shipping_rate=count($shipping_rate)-1;
             $shipping_country=explode(',',$shipping_rule['country']);
             $shipping_state=explode(',',$shipping_rule['state']);

            // dd($shipping_state);

             

        } else {

            $shipping_rule =  new ShippingRule;

            $country = Country::where('status', '1')->get();

            $state = State::all();

            $shipping_rate='';

            $cnt_shipping_rate=0;

            $shipping_state=array();
            
            $shipping_country=array();

            

        }

      //  dd($cnt_shipping_rate);

        $shipping_method = ShippingMode::all();

        $prefix = ($id > 0 ? 'Edit' : 'Add New');

        $pageData['page_title'] = $prefix.' Shipping Rule';

        $pageData['meta_title'] = $prefix.' Shipping Rule';

        $pageData['breadcrumbs'] = [

             [

                 'title' => 'Shipping Rule List',

                 'url' =>route('pnkpanel.shipping-rule.list')

             ],

             [

                 'title' => $prefix.' Shipping Rule',

                 'url' =>route('pnkpanel.shipping-rule.edit', $id)

             ]

        ];

        return view('pnkpanel.shipping_rule.edit', compact('id', 'shipping_rule','shipping_method', 'country', 'state','shipping_rate','cnt_shipping_rate','shipping_state','shipping_country'))->with($pageData);;

    }



    public function shipping_rule_update(Request $request){

     

        $actType = $request->actType;

        $shipping_rule_id = $request->shipping_rule_id;



        $shipping_rule = ShippingRule::findOrNew($shipping_rule_id);

        

        $this->validate($request, [

            'country' => 'required',

           // 'state' => 'required',

            

        ],

        [

            'country.required' => 'Country is required',

           // 'state.required' => 'State is required',

          

        ]);

        $shipping_rule->shipping_mode_id  =  $request->shipping_method;

        //$shipping_rule->country = $request->country;

        if(in_array("US",$request->country))

        {
            $shipping_rule->country = $request->country[0];
        }
        else
        {
           
           $country = $request->country; 
            $countrylist = "";
        
            if(count($country)>0)
            {
               
              for($ci=0;$ci<count($country);$ci++)
              {
                $countrylist .= $country[$ci].",";
              }
              $countrylist = substr($countrylist,0,strlen($countrylist)-1);
              $shipping_rule->country = $countrylist;
      
            }
            else
            {
               $shipping_rule->country = "";  
            }
        }

        if(!in_array("US",$request->country))

        {
            $state = $request->otherstate;
           if(!empty($state)){
            $shipping_rule->state = $state; 
           }else{
            $shipping_rule->state = ''; 
           }
        //    $state = $request->otherstate;

        //    $shipping_rule->state = $state; 

        }

        else

        {

           $state = $request->state; 

            $statelist = "";

        

            if(count($state)>0)

            {

              for($ci=0;$ci<count($state);$ci++)

              {

                $statelist .= $state[$ci].",";

              }

              $statelist = substr($statelist,0,strlen($statelist)-1);

              $shipping_rule->state = $statelist;

      

            }

            else

            {

               $shipping_rule->state = "";  

            }

        }

        

      



        

        $shipping_rule->zipcode_to    =  $request->zipcode_to;

        $shipping_rule->zipcode_from  =  $request->zipcode_from;

        $shipping_rule->rule_type  =  $request->rule_type;

        if($request->free_ship_amt>0)

        {   

            $shipping_rule->free_ship_amt  =  $request->free_ship_amt;

            $is_free_ship="Yes";

        }

        else

        {

            $shipping_rule->free_ship_amt=0;

            $is_free_ship="No";

        }

        $shipping_rule->is_free_ship=$is_free_ship;

        $shipping_rule->prop_item  =  $request->prop_item;

       $shipping_rule->prop_charge  =  $request->prop_charge;

     

        $order_amount=$request->order_amount;

        $charge=$request->charge;

        

        if($shipping_rule->save()) {

           

            if($actType=="add")

            {

                $shipping_rule_id = $shipping_rule->shipping_rule_id;

            }

            else

            {

                $shipping_rule_id = $request->shipping_rule_id;

                 $result=ShippingRate::where('shipping_rule_id','=',$shipping_rule_id)->delete();

               

            }

            for($sr=0;$sr<count($order_amount);$sr++) 

            {

                if(isset($order_amount[$sr]) && !empty($order_amount[$sr])) 

                {

                    $shipping_rate = new ShippingRate;

                    $shipping_rate->shipping_rule_id=$shipping_rule_id;

                    $shipping_rate->order_amount=$order_amount[$sr];

                    $shipping_rate->charge=$charge[$sr];

                    $shipping_rate->save();

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

        return redirect()->route('pnkpanel.shipping-rule.list');

    }

    public function shipping_rule_delete($id)

    {

        if (ShippingRule::findOrFail($id)->delete()) {

            session()->flash('site_common_msg', config('messages.msg_delete'));

        } else {

            session()->flash('site_common_msg_err', config('messages.msg_delete_err'));

        }

        return redirect()->route('pnkpanel.shipping-rule.list');

    }

   /* public function shipping_method_charge_list($id)

    {

        $shipping_mode_res = ShippingMode::find($id);

    

        if(is_null($shipping_mode_res)) {

            session()->flash('site_common_msg_err', 'Please choose a shipping method to view additional shipping charges.');

            return redirect()->route('pnkpanel.shipping-method.list');

        }        



        if (request()->ajax()) 

        {

            $model = ShippingCharge::select('*')->where('shipping_mode_id', $id);

            $table = DataTables::eloquent($model);

            $table->addIndexColumn();

            $table->addColumn('checkbox', function ($row) {

                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->shipping_rule_id . '" />';

            });

            $table->editColumn('action', function ($row) {

                $action = (string)view('pnkpanel.component.datatable_action', ['id' => $row->shipping_rule_id]);

                return $action;

            });

            $table->rawColumns(['checkbox', 'display_position', 'action']);

            return $table->make(true);

        }



        $pageData['page_title'] = "Additional Shipping Charge For ".$shipping_mode_res['shipping_title'];

        $pageData['meta_title'] = "Additional Shipping Charge";

        $pageData['breadcrumbs'] = [

            [

                'title' => 'Shipping Method List',

                'url' => route('pnkpanel.shipping-method.list')

            ],

            [

                'title' => 'Additional Shipping Charge',

                'url' => route('pnkpanel.shipping-method.list')

            ]

        ];

        return view('pnkpanel.shipping_method.additional_shipping_list', compact('id'))->with($pageData);

    }



    public function shipping_method_charge_edit($id, $shipping_id = 0)

    {

        $country = Country::where('');



        if ($shipping_id > 0) {

            $shipping_charge = ShippingCharge::findOrFail($shipping_id);

            $country = Country::where('status', '1')->get();

            $state = State::all();

        } else {

            $shipping_charge = new ShippingCharge;

            $country = Country::where('status', '1')->get();

            $state = State::all();

        }

        $pageData['page_title'] = ($shipping_charge->shipping_rule_id > 0 ? 'Update' : 'Add')." Additional Shipping Charge";

        $pageData['meta_title'] = ($shipping_charge->shipping_rule_id > 0 ? 'Update' : 'Add')." Additional Shipping Charge";

        $pageData['breadcrumbs'] = [

            [

                'title' => 'Shipping Method List',

                'url' => route('pnkpanel.shipping-method.list')

            ],

            [

                'title' => 'Additional Shipping Charge',

                'url' => route('pnkpanel.shipping-method-charge.list',$id)

            ],

            [

                'title' =>  ($shipping_charge->shipping_rule_id > 0 ? 'Update' : 'Add'),

                'url'   =>  ''

            ]

        ];



        return view('pnkpanel.shipping_method.additional_shipping_edit', compact('id', 'shipping_charge', 'country', 'state'))->with($pageData);

    }



    public function shipping_method_charge_update(Request $request)

    {

        $actType = $request->actType;

        $id = $request->id;

        $shipping_charge = ShippingCharge::findOrNew($id);

        $this->validate($request, [

            'shipping_mode_id' => 'required',

            'state' => 'required',

            'country' => 'required',

            'additonal_charge' => 'required',

        ],

        [

            'country.required' => 'Country is required',

            'state.required' => 'State is required',

            'addition_charge' => 'Additional Charge is required'

        ]);



        $shipping_charge->country = $request->country;

        $shipping_charge->state = $request->state;

        $shipping_charge->shipping_mode_id = $request->shipping_mode_id;

        $shipping_charge->additonal_charge = $request->additonal_charge;

        if ($shipping_charge->save()) {

            if ($actType == 'add') {

                session()->flash('site_common_msg', config('messages.msg_add'));

                return redirect()->route('pnkpanel.shipping-method-charge.list', $shipping_charge->shipping_mode_id);

            } else {

                session()->flash('site_common_msg', config('messages.msg_update'));

            }

        } else {

            session()->flash('site_common_msg_err', config('messages.msg_add_err'));

        }

        return redirect()->route('pnkpanel.shipping-method-charge.list', $shipping_charge->shipping_mode_id);

    }



    public function shipping_method_charge_delete($id)

    {

        $shipping_method = ShippingCharge::findOrFail($id);

        if ($shipping_method->delete()) {

            session()->flash('site_common_msg', config('messages.msg_delete'));

        } else {

            session()->flash('site_common_msg_err', config('messages.msg_delete_err'));

        }

        return redirect()->route('pnkpanel.shipping-method-charge.list', $shipping_method->shipping_mode_id);

    }*/





    public function payment_method_list()

    {

        $payment_method_standard = PaymentMethod::where('pm_type', 'STANDARD')->get();

        $payment_method_custom = PaymentMethod::where('pm_type', 'CUSTOM')->get();

        $pageData['page_title'] = 'Payment Methods';

        $pageData['meta_title'] = 'Payment Methods';

        $pageData['breadcrumbs'] = [

            [

                'title' => 'Payment Methods',

                'url' => route('pnkpanel.payment-method.list')

            ],

        ];

        return view('pnkpanel.payment_methods.payment_method_list', compact('payment_method_standard', 'payment_method_custom'))->with($pageData);

    }



    public function payment_method_edit($id = 0)

    {

        if ($id > 0) {

            $payment_method = PaymentMethod::findOrFail($id);

        } else {

            $payment_method =  new PaymentMethod;

        }

        $prefix = ($id > 0 ? 'Edit' : 'Add New');

        $pageData['page_title'] = $prefix . ' Payment Method';

        $pageData['meta_title'] = $prefix . ' Payment Method';

        $pageData['breadcrumbs'] = [

            [

                'title' => 'Payment Methods',

                'url' => route('pnkpanel.payment-method.list')

            ],

            [

                'title' => $prefix . ' Payment Method',

                'url' => route('pnkpanel.payment-method.edit', $id)

            ]

        ];

        return view('pnkpanel.payment_methods.payment_method_edit', compact('payment_method'))->with($pageData);;

    }



    public function payment_method_update(Request $request)
    {
        $actType = $request->actType;
        $id = $request->id;
        $payment_method = PaymentMethod::findOrNew($id);
        $this->validate($request, [
            'pm_name' => 'required|string',
        ],
        [
            'pm_name.required' => 'Payment Method Name is required',
        ]);

        $payment_method->pm_name            =    $request->pm_name;
        $payment_method->pm_status            =    $request->pm_status;
        $payment_method->pm_position        =    $request->pm_position;
        $payment_method->pm_short_desc        =  $request->pm_short_desc;
        $payment_method->save();

        $payment_method = PaymentMethod::findOrNew($id);
       
        if ($payment_method->pm_type == "STANDARD") {

            $pm_details             = unserialize($payment_method->pm_details);
           
            $new_pm_details            = $request->pm_details;
           
           if(isset($new_pm_details) && !empty($new_pm_details)){
            $pm_settings_encrypted     = $request->pm_settings_encrypted;
           
            foreach ($new_pm_details as $pm_var_name => $pm_var_value) {
                if ($pm_settings_encrypted[$pm_var_name] == "Yes" and trim($pm_var_value) != '') {
                    $pm_var_value_new  = "";

                    $pm_var_value_new  = GlobalHelper::encrypt(trim($pm_var_value));

                    $pm_details[$pm_var_name] = $pm_var_value_new;

                    $is_send_payemt_email = "Yes";
                } elseif ($pm_settings_encrypted[$pm_var_name] == "No") {
                    $pm_var_value_new  = "";

                    $pm_var_value_new  = trim($pm_var_value);

                    $pm_details[$pm_var_name] = $pm_var_value_new;
                }
            }
            $payment_method->pm_details = serialize($pm_details);
        }
        }

        if ($payment_method->save()) {
            if ($actType == 'add') {
                session()->flash('site_common_msg', config('messages.msg_add'));
                return redirect()->route('pnkpanel.payment-method.list', $payment_method->pm_id);
            } else {
                session()->flash('site_common_msg', config('messages.msg_update'));
            }
        } else {
            session()->flash('site_common_msg_err', config('messages.msg_add_err'));
        }
        return redirect()->route('pnkpanel.payment-method.list', $payment_method->pm_id);
    }






    public function deleteImage(Request $request)

    {

        $success = false;

        $errors = [];

        $messages = [];

        $response_http_code = 400;



        $actType = $request->actType;

        if (in_array($actType, ['delete_image'])) {

            $destination_path = '';

            if ($request->type == 'artical_image') {

                $destination_path = config('const.ARTICAL_IMAGE_PATH');

            }



            $image_name = $request->image_name;

            if (File::delete($destination_path . $image_name)) {



                $article = Articles::find($request->id);



                $article->artical_image = NULL;



                $article->save();



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



    public function manage_static_page_list()

    {

        if (request()->ajax()) {

            $model = StaticPages::select('*');

            $table = DataTables::eloquent($model);

            $table->addIndexColumn();

            $table->addColumn('checkbox', function ($row) {

                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->static_pages_id . '" />';

            });

            $table->editColumn('url_link', function ($row) {

                if($row->name == 'contact-us')

				{

					return "<a href=" . URL('/') . '/' . $row->name.".html". " target='_blank'>" . URL('/') . '/' . $row->name .".html". "</a>";

				}

				else

				{

					return "<a href=" . URL('/') . '/pages/' . $row->name."". " target='_blank'>" . URL('/') . '/pages/' . $row->name ."". "</a>";

				}

            });

            $table->editColumn('status', function ($row) {

                return ($row->status ? 'Active' : 'Inactive');

            });

            $table->editColumn('action', function ($row) {

                $action = (string)view('pnkpanel.component.datatable_action', ['id' => $row->static_pages_id]);

                return $action;

            });

            $table->rawColumns(['checkbox', 'action', 'url_link']);

            return $table->make(true);

        }



        $pageData['page_title'] = "Manage Static Pages";

        $pageData['meta_title'] = "Manage Static Pages";

        $pageData['breadcrumbs'] = [

            [

                'title' => 'Manage Static Pages',

                'url' => route('pnkpanel.manage-static-page.list')

            ]

        ];

        return view('pnkpanel.static_page.static_page_list')->with($pageData);

    }



    public function manage_static_page_edit($id = 0)

    {

        if ($id > 0) {

            $static_page = StaticPages::findOrFail($id);

        } else {

            $static_page =  new StaticPages;

        }

        $prefix = ($id > 0 ? 'Edit' : 'Add New');

        $pageData['page_title'] = $prefix . ' Static Page';

        $pageData['meta_title'] = $prefix . ' Static Page';

        $pageData['breadcrumbs'] = [

            [

                'title' => 'Manage Static Pages',

                'url' => route('pnkpanel.manage-static-page.list')

            ],

            [

                'title' => $prefix . ' Static Page',

                'url' => route('pnkpanel.manage-static-page.edit', $id)

            ]

        ];

        return view('pnkpanel.static_page.static_page_edit', compact('static_page'))->with($pageData);;

    }



    public function manage_static_page_update(Request $request)

    {   

        $actType = $request->actType;

        $id = $request->id;

        $is_delete = $request->is_delete;

        $del_chk = $request->del_chk;

        $del_chk_arr = explode(",",$del_chk);

        $static_pages = StaticPages::findOrNew($id);

        $this->validate($request, [

            'title' => 'required|string',

            'name' => 'required|string',

        ],

        [

            'title.required' => 'Page Title is required',

            'name.required' => 'Page Name is required',

        ]);



        if ($request->hasFile('static_image')) {

            if ($request->file('static_image')->isValid()) {

                $data = $this->validate($request, [

                    'static_image' => 'required|mimes:jpeg,png,jpg',

                ]);

            }

        }

        // Added code for Category Tile Images as on 06-10-2023 Start

        $shop_count = $request->shop_count;

        //echo $shop_count; exit;

        if($shop_count > 0)

        {

            $no_data = array();

            $category_beauty_json = array();

            $flag = "false";

            $del_key = '';



            for($i=0;$i<$shop_count;$i++){



                if(isset($_POST['shop_question'.$i]) && $_POST['shop_question'.$i] == ""){

                    $no_data[] = "yes";

                }



                if(isset($_POST['shop_answer'.$i]) && $_POST['shop_answer'.$i] == ""){

                    $no_data[] = "yes";

                }



                if(isset($_POST['shop_chkbox'.$i]) && $_POST['shop_chkbox'.$i] != "" && $is_delete == "yes"){

                    $flag = "true";

                    $del_key = $_POST['shop_chkbox'.$i];

                }

                if ($_POST['shop_question'.$i]) {

                    $this->validate($request, [

                        'shop_question'.$i => 'required|string'

                    ]);

                }



                if ($_POST['shop_answer'.$i]) {

                    $this->validate($request, [

                        'shop_answer'.$i  => 'required|string'

                    ]);

                }

                $Insert_Date = date('d M y');

                //echo $Insert_Date; exit;

                $category_beauty_json[] = '{"shop_question": "'.str_replace("\"", "", $_POST['shop_question'.$i]).'","shop_answer": "'.str_replace("\"", "", $_POST['shop_answer'.$i]).'","Insert_Date": "'.$Insert_Date.'"}';

            }

            

            //echo $category_beauty_json; exit;

            $category_beauty_json = "[".implode(",", $category_beauty_json)."]";



            if($flag == "true" && count($del_chk_arr) > 0){

                $data = json_decode($category_beauty_json, true);



                foreach ($del_chk_arr as $del_value) {

                    unset($data[$del_value]);

                }



                $data = array_values($data);



                $category_beauty_json = json_encode($data);

            }



        }else{

            $category_beauty_json = "";

        }



        // Added code for Category Tile Images as on 06-10-2023 End

        $static_pages->title     =  $request->title;

        $static_pages->name =  $request->name;

        if($static_pages->name=="faqs")

        {

             $static_pages->content     =  $category_beauty_json;

        }

        else

        {

             $static_pages->content     =  $request->content;

        }

       

        //$static_pages->is_topmenu     =  $request->is_topmenu;

        $static_pages->status     =  $request->status;

        //$static_pages->products_sku     =  $request->products_sku == '' ? ' ' : $request->products_sku;

        //$static_pages->bottom_text     =  $request->bottom_text == '' ? ' ' : $request->bottom_text;

        $static_pages->meta_title     =  $request->meta_title ? $request->meta_title :'';

        $static_pages->meta_keywords     =  $request->meta_keywords ? $request->meta_keywords :'';

        $static_pages->meta_description     =  $request->meta_description ? $request->meta_description :'';

        if ($static_pages->save()) {



            /*if ($request->hasFile('static_image')) {

                if ($request->file('static_image')->isValid()) {



                    if (!file_exists(config('const.STATIC_IMAGE_PATH'))) {

                        File::makeDirectory(config('const.STATIC_IMAGE_PATH'), $mode = 0777, true, true);

                    }



                    $image = $request->file('static_image');



                    $rand_num = random_int(1000, 9999);

					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());

					$original_filename = clearSpecialCharacters($original_filename)."_".$rand_num."_".$static_pages->static_pages_id.".".$image->getClientOriginalExtension();



                    $image_name = $original_filename; //$image->getClientOriginalName();

                    $destination_path = config('const.STATIC_IMAGE_PATH');

                    $res = $image->move($destination_path, $image_name);



                    $orig_saved_file_path = $destination_path . '/' . $image_name;

                    $image_resize = Image::make($orig_saved_file_path);

                    $image_resize->resize(config('const.STATIC_IMAGE_THUMB_WIDTH'), config('const.STATIC_IMAGE_THUMB_HEIGHT'));

                    $image_resize->save($orig_saved_file_path);





                    $manage_article = StaticPages::find($static_pages->static_pages_id);

                    $manage_article->artical_image = $image_name;

                    $manage_article->save();

                }

            }*/

            if ($actType == 'add') {

                session()->flash('site_common_msg', config('messages.msg_add'));

                return redirect()->route('pnkpanel.manage-static-page.list', $static_pages->static_pages_id);

            } else {

                session()->flash('site_common_msg', config('messages.msg_update'));

            }

        } else {

            session()->flash('site_common_msg_err', config('messages.msg_add_err'));

        }

        return redirect()->route('pnkpanel.manage-static-page.list', $static_pages->static_pages_id);

    }



    public function manage_static_page_delete($id)

    {

        if (StaticPages::findOrFail($id)->delete()) {

            session()->flash('site_common_msg', config('messages.msg_delete'));

        } else {

            session()->flash('site_common_msg_err', config('messages.msg_delete_err'));

        }

        return redirect()->route('pnkpanel.manage-static-page.list');

    }





    public function manage_static_page_deleteImage(Request $request)

    {

        $success = false;

        $errors = [];

        $messages = [];

        $response_http_code = 400;



        $actType = $request->actType;

        if (in_array($actType, ['delete_image'])) {

            $destination_path = '';

            if ($request->type == 'static_image') {

                $destination_path = config('const.STATIC_IMAGE_PATH');

            }



            $image_name = $request->image_name;

            if (File::delete($destination_path . $image_name)) {



                $article = StaticPages::find($request->id);



                $article->static_image = NULL;



                $article->save();



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

    public function instagram_settings_edit(Request $request) {
		$instagram_settings = InstagramSettings::select('instagram_settings_id', 'title', 'var_name', 'setting', 'description', 'display_order', 'section', 'status')->orderBy('display_order')->get();
		
		$pageData['page_title'] = 'Instagram Settings';
		$pageData['meta_title'] = 'Instagram Settings';
		/*$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Instagram Settings',
				 'url' =>route('pnkpanel.instagram.edit')
			 ],
			 [
				 'title' => 'Global Settings',
				 'url' =>route('pnkpanel.global-setting.index')
			 ]
		];*/
		
        return view('pnkpanel.instagram.edit', compact('instagram_settings'))->with($pageData);;
    }
    public function instagram_settings_update(Request $request){
        $instagram_settings = InstagramSettings::select('instagram_settings_id', 'title', 'var_name', 'setting', 'description', 'display_order', 'section', 'status')->orderBy('display_order')->get();
        foreach($instagram_settings as $instagram_settings_key => $instagram_settings_value){
            $updateArray['setting'] = $request[$instagram_settings_value['instagram_settings_id']];
            $update = InstagramSettings::where('instagram_settings_id', '=', $instagram_settings_value['instagram_settings_id'])->update($updateArray);

        }
        session()->flash('site_common_msg', config('messages.msg_update')); 
        return redirect()->route('pnkpanel.instagram-settings.edit');
    }

}

