<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Country;
use App\Models\State;
use App\Models\EmailTemplate;
use DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExports;
use DB;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;
use App\Http\Controllers\Traits\generalTrait;

class CustomerController extends Controller
{
    use generalTrait, CrudControllerTrait {
        generalTrait::sendSMTPMail insteadof CrudControllerTrait;
    }
    //use CrudControllerTrait;
    // use Traits\generalTrait;
    //use generalTrait;

    public function model()
    {
        return Customer::class;
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            // dd($request);
            $model = Customer::select([
                'customer_id',
                'first_name',
                'last_name',
                DB::raw("IF(first_name <> '' && last_name <> '', CONCAT(first_name,' ',last_name), '-' ) AS name"),
                'email',
                'city',
                'state',
                'country',
                DB::raw("DATE_FORMAT(reg_datetime,'%m/%d/%Y') AS converted_date"),
                'registration_type',
                'status',
                
            ]);
            $model = $model->withCount('orders');
            $table = DataTables::eloquent($model);
            // dd($table);
            $table->addIndexColumn();
            $table->addColumn('checkbox', function($row) {
                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="'.$row->customer_id.'" />';
            });
            $table->editColumn('name', function($row) {
                // return $row->name;
                $full_name = ($row->name == '') ? '-' : $row->name;
                return "<a href=".route('pnkpanel.customer.edit',["id"=>$row->customer_id]).">".$full_name."</a>";
            });
            /*$table->addColumn('reg_datetime', function($row) {
                    return Carbon::parse($row->reg_datetime)->format('m/d/Y');
            });*/
            $table->addColumn('customer_type', function($row) {
                    return ($row->registration_type == "M" ? "Member" : "Guest");
            });
            $table->editColumn('status', function($row) {
                return ($row->status ? 'Active':'Inactive');
            });
           
            $table->editColumn('orders_count', function($row) {
                if($row->orders_count > 0) {
                    $order_details = Order::selectRaw("MIN( DATE_FORMAT( order_datetime, '%m/%d/%Y' ) ) AS order_date")->where('customer_id', '=', $row->customer_id)->first();
                    $filterStartDate = Carbon::parse($order_details->order_date)->format('m/d/Y');
                    return "<a href='".route('pnkpanel.order.list')."?filterCustomer=".$row->customer_id."&filterStartDate=".$filterStartDate."'>".$row->orders_count."</a>";
                } else {
                    return 0;
                }
            });
            
            $table->editColumn('city', function($row) {
                    return $city=$row->city;
            });

            $table->editColumn('state', function($row) {
                    return $state=$row->state;
            });

            $table->editColumn('country', function($row) {
                    return $country=$row->country;
            });
            $table->addColumn('action', function($row) {
                return (string)view('pnkpanel.component.datatable_action', ['id' => $row->customer_id]);
            });
            $table->rawColumns(['name','checkbox','email','action','orders', 'orders_count']);
            return $table->make(true);

        }

        $pageData['page_title'] = "Customer List";
        $pageData['meta_title'] = "Customer List";
        $pageData['breadcrumbs'] = [
             [
                 'title' => 'Customer List',
                 'url' =>route('pnkpanel.customer.list')
             ]
        ];
        
        return view('pnkpanel.customer.list')->with($pageData);

    }

    public function edit($id = 0) {
        if($id > 0) {
            $customer = Customer::findOrFail($id);
        } else {
            $customer =  new Customer;
        }
        $countryArray = getCountryBoxArray();
        $stateArray = getStateBoxArray();
        
        $prefix = ($id > 0 ? 'Edit' : 'Add New');
        $pageData['page_title'] = $prefix.' Customer';
        $pageData['meta_title'] = $prefix.' Customer';
        $pageData['breadcrumbs'] = [
             [
                 'title' => 'Customer List',
                 'url' =>route('pnkpanel.customer.list')
             ],
             [
                 'title' => $prefix.' Customer',
                 'url' =>route('pnkpanel.customer.edit', $id)
             ]
        ];
        // dd($customer);
        return view('pnkpanel.customer.edit', compact('customer', 'countryArray', 'stateArray'))->with($pageData);;
    }

    public function update(Request $request)
    {
        $actType = $request->actType;
        $customer_id = $request->customer_id;
        
        if($actType == 'add') {
            $check_email = Customer::whereRaw('LCASE(`email`) = "'.strtolower(trim($request->email)).'"')->where('registration_type', '=', 'M')->count();
        } else {
            if(strtolower(trim($request->email)) != strtolower(trim($request->old_email))) {
                $check_email = Customer::whereRaw('LCASE(`email`) = "'.strtolower(trim($request->email)).'"')->where('customer_id', '!=', $customer_id)
                            ->where('registration_type', '=', 'M')->count();
            } else {
                $check_email = 0;
            }
        }
        if($check_email && $check_email > 0)
        {
            return redirect()->back()->withInput()
                    ->withErrors([
                        'email_exists' => "Please Change Email Address its all ready in Use.",
                    ]);
        }

        $this->validate($request, [
            'email'   => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'address1' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'phone' => 'required',
            'state' => 'required_if:country,US',
            'other_state' => 'required_unless:country,US'
        ],
        [
            'email.required'        => 'Please Enter Email',
            'email.email'  => 'Please input a valid email address.',
            'first_name.required'  => 'Please Enter First Name',
            'last_name.required'  => 'Please Enter Last Name',
            'address1.required'  => 'Please Enter Address',
            'city.required'  => 'Please Enter City',
            'zip.required'  => 'Please Enter Zip',
            'phone.required'  => 'Please Enter Phone No.',
            'state.required_if' => 'Please Enter State',
            'other_state.required_unless' => 'Please Enter State'
        ]);
        
        if ($request->actType == 'add' && $request->registration_type != 'guest') {

                $data = $this->validate($request, [
                    'password' => 'required_if:actType,add|same:confirm_password',
                ],
                [
                    'password.required_if' => 'Please Enter Password',
                    'password.same' => 'Please Confirm Your Password'
                ]);

        }
        $request->registration_type = 'guest';
        if (($request->actType == 'add' && $request->registration_type != 'guest') || ($request->registration_type != 'guest' && $request->actType == 'update' && $request->password != '')) {

                $data = $this->validate($request, [
                    'confirm_password' => 'required_with:password',
                ],
                [
                    'confirm_password.required_with' => 'Please Enter Confirm Password',
                ]);

        }
        
        $state = $request->state;
        if( $request->country != "US" ) {
            $state = $request->otherstate;
        }
        // $registration_type = ($request->registration_type == 'guest') ? 'G' : 'M';
        $customer = Customer::findOrNew($customer_id);
        $customer->first_name     =  $request->first_name;
        $customer->last_name     =  $request->last_name;
        $customer->address1     =  $request->address1;
        $customer->address2     =  ($request->has('address2') ) ? $request->address2 : '';
        $customer->city     =  $request->city;
        $customer->country     =  $request->country;
        $customer->state     =  $state;
        $customer->zip     =  $request->zip;
        $customer->site_type     =  $request->site_type;
        $customer->phone     =  $request->phone;
        $customer->email     =  $request->email;
        if($request->password != null) {
            $customer->password     =  md5($request->password);
        }
        // $customer->registration_type     =  $registration_type;
        $customer->customer_ip     =  $request->ip();
        $customer->customer_browser   =  $request->header('User-Agent');
        // $customer->representative_id   =  ($request->representative_id != null) ? $request->representative_id : 0;
        $customer->reg_datetime   =  Carbon::now()->format('Y-m-d H:i:s');
        $customer->status  =  $request->status;
        // dd($customer);
        if($customer->save()) {
            if($actType == 'add') {
                session()->flash('site_common_msg', config('messages.msg_add'));
            } else {
                session()->flash('site_common_msg', config('messages.msg_update')); 
            }
        } else {
            session()->flash('site_common_msg_err', config('messages.msg_add_err'));
        }
        return redirect()->route('pnkpanel.customer.edit', $customer->customer_id);
    }

    public function delete($id){
        if(Customer::findOrFail($id)->delete()) {
            session()->flash('site_common_msg', config('messages.msg_delete')); 
        } else {
            session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 
        }
        return redirect()->route('pnkpanel.customer.list');
    }

    public function customerExport()
    {
        $export_file_name = 'customer.csv';
        $header_row = ["First Name", "Last Name", "Email", "Address1", "Address2", "Phone", "City", "State", "Zip", "Country", "Type", "Status"];
        $customer_data = Customer::all();
        $csv_data = [];
        if(count($customer_data) > 0) {
            foreach($customer_data as $customer_key => $customer_value) {
                $csv_data[$customer_key]['first_name'] = $customer_value['first_name'];
                $csv_data[$customer_key]['last_name'] = $customer_value['last_name'];
                $csv_data[$customer_key]['email'] = $customer_value['email'];
                $csv_data[$customer_key]['address1'] = $customer_value['address1'];
                $csv_data[$customer_key]['address2'] = $customer_value['address2'];
                $csv_data[$customer_key]['phone'] = $customer_value['phone'];
                $csv_data[$customer_key]['city'] = $customer_value['city'];
                $csv_data[$customer_key]['state'] = $customer_value['state'];
                $csv_data[$customer_key]['zip'] = $customer_value['zip'];
                $csv_data[$customer_key]['country'] = $customer_value['country'];
                $csv_data[$customer_key]['registration_type'] = ($customer_value['registration_type'] == "M" ? "Member" : "Guest");
                $csv_data[$customer_key]['status'] = ($customer_value['status'] == '0') ? 'InActive' : 'Active';
            }
            return Excel::download(new CustomerExports($csv_data, $header_row), $export_file_name);
        } else {
            session()->flash('site_common_msg_err', 'Something went wrong. Please try again later.'); 
        }
    }

    public function emailForgotPassword(Request $request) {
        $success = false;
        $errors = [];
        $messages = [];
        $response_http_code = 400;

        $actType = $request->actType;
        
        
        if($actType == 'Forgotpassword')
        {
            $ids_obj = $request->ids;
            if(empty($ids_obj)) 
            {
                $success = false;
                $errors = ["message" => ["Please select record(s)."]];
                $messages = [];
                $response_http_code = 400;
            } else {
                $ids_obj = explode(',', $ids_obj);

                $e_msg = '';

                foreach($ids_obj as $record_key => $record_value)
                {
                    $send_mail = Customer::where('customer_id', '=', $record_value)->where('registration_type', '=', 'M')->first();
                    if($send_mail && $send_mail->count() > 0) {

                        $vEmail     = $send_mail->email;
                        $vPassword  = generatePassword();

                        $UpdateCustomer = [];
                        $UpdateCustomer["password"]= md5($vPassword);

                        $result1 = Customer::where('customer_id', '=', $record_value)
                                            ->update($UpdateCustomer);

                        if( $vPassword != "" && !empty($vPassword) )
                        { 
                            $mres = Self::Get_Mail_Template("FORGOT_PASSWORD");
                            $mail_subject = stripslashes($mres->subject);
                            $mail_content = stripslashes($mres->mail_body);
                    
                            $mail_subject    = str_replace('{$SITE_NAME}', config('Settings.SITE_TITLE'), $mail_subject);
                            
                            $mail_content = str_replace('{$vemail}', $vEmail, $mail_content);
                            $mail_content = str_replace('{$password}', $vPassword, $mail_content);
                            $mail_content = str_replace('{$TOLL_FREE_NO}', config('Settings.TOLL_FREE_NO'), $mail_content);
                            $mail_content = str_replace('{$Site_URL}', config('const.SITE_URL'), $mail_content);
                            $mail_content = str_replace('{$SITE_NAME}', config('Settings.SITE_TITLE'), $mail_content);
                                      
                            /*echo "  T0  :".$vEmail."from:". config('Settings.CONTACT_MAIL')." <br>";
                            echo $mail_subject."<br>";
                            echo $mail_content."<br>"; exit;*/
                            
                            // $onesendstat = SendMail($mail_subject,$mail_content,$vEmail, config('Settings.CONTACT_MAIL'));
                            
                            $e_msg .= $vEmail.",";
                        }
                    }

                }

                if($e_msg !='')
                {
                    $msg = "Mail has been Sent Successfully to ".$e_msg;
                }
                else
                {
                    $msg =  "Mail has not been Sent, Please try again.";
                }

                $success = true;
                $errors = [];
                $messages = ["message" => [$msg]];
                $response_http_code = 200;
            }
        }
        
        return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
    }
    
    public function Get_Mail_Template($mail_name) 
    {
        $aEmailTemplate = EmailTemplate::select('subject', 'mail_body')->where('template_var_name', '=', $mail_name)->where('status', '=', '1')->first();

        if( $aEmailTemplate && $aEmailTemplate->count() > 0 ) 
        {
            return $aEmailTemplate;
        }
        else 
        {
            return false;
        }
    }
    
    public function ajaxAutoSuggestCustomerName(Request $request) {
		$payLoad = json_decode(request()->getContent(), true);
		//$payLoad = json_decode(request()->get('payload'));
		$str_li_content ='';
		$search_keyword = $payLoad['search_keyword'];
		if(isset($search_keyword) && $search_keyword != '') {
			$search_keyword = preg_replace("/[^a-z0-9 ]/si", "", $search_keyword);
			return Customer::select('customer_id','first_name','last_name')->where('status', '1')
			->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like',  '%'.$search_keyword.'%')
			/*->where(function ($query) use ($search_keyword){
				$query->where('first_name', 'like', '%'.$search_keyword.'%')
					->orWhere('last_name','like', '%'.$search_keyword.'%');
			})*/
			->orderBy('first_name')->orderBy('last_name')->get()->toJson();
		} else {
			return response()->json();
		}
	}

}
