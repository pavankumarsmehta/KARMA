@extends('pnkpanel.layouts.app')
@section('content')
<form action="{{ route('pnkpanel.payment-method.update') }}" enctype="multipart/form-data" method="post" name="frmRepresentative" id="frmRepresentative"
class="ecommerce-form action-buttons-fixed">
<input type="hidden" name="id" value="{{ $payment_method->pm_id }}">
<input type="hidden" name="actType" id="actType" value="{{ $payment_method->pm_id > 0 ? 'update' : 'add' }}">
@csrf
<div class="row">
	<div class="col">			
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                        </div>
                        <h2 class="card-title">Payment Method Common Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
                        </div>
                        @php 
                        $payment_method_settings = unserialize($payment_method->pm_details);
                        @endphp 

                        
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="pm_name">Payment Method Name<span class="required">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control @error('pm_name') error @enderror" id="pm_name" name="pm_name" value="{{ old('pm_name', $payment_method->pm_name) }}">
                                <span class="help-block">Payment method name will appear under payment methods on payment page.</span>
                                @error('pm_name')
                                <label class="error" for="pm_name" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="pm_status">Is This Method Available?</label>
                            <div class="col-lg-6">
                                <select name="pm_status" id="pm_status" class="form-control">
                                    <option value="Active" {{ old('pm_status', $payment_method->pm_status) == "Active" ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('pm_status', $payment_method->pm_status) == "Inactive" ? 'selected' : '' }}>Inactive</option>
                                  </select>
                                @error('pm_status')
                                <label class="error" for="pm_status" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="pm_position">Position</label>
                            <div class="col-lg-6">
                                <input name="pm_position" type="text" class="form-control" id="pm_position" size="10" value="{{ old('pm_position', $payment_method->pm_position) }}">
                                @error('pm_position')
                                <label class="error" for="pm_position" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        @if($payment_method->pm_group_name == 'PAYMENT_PAYPALEC' || $payment_method->pm_group_name == 'PAYMENT_BRAINTREECC' || $payment_method->pm_group_name == 'PAYMENT_AUTHORIZENETCC' || $payment_method->pm_group_name == 'PAYMENT_GOOGLEC')

                        <h2 class="card-title mb-4 mt-4">Payment Server Settings</h2>
                        @if($payment_method->pm_group_name == "PAYMENT_PAYPALEC")
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="paypalec_Username">Paypal API User Name :</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[paypalec_Username]" value="Yes">
                                <input name="pm_details[paypalec_Username]" type="text" class="form-control" id="paypalec_Username" value="">
                                <span class="help-block">Leave blank if you do not want to change your detail.</span>
                                @error('paypalec_Username')
                                <label class="error" for="paypalec_Username" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="paypalec_Signature">Paypal API Password</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[paypalec_Password]" value="Yes">
                                <input name="pm_details[paypalec_Password]" type="text" class="form-control" id="paypalec_Password" value="">
                                <span class="help-block">Leave blank if you do not want to change your detail.</span>
                                @error('paypalec_Signature')
                                <label class="error" for="paypalec_Signature" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="paypalec_Signature">Paypal API Signature </label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[paypalec_Signature]" value="Yes">
                                <input name="pm_details[paypalec_Signature]" type="text" class="form-control" id="paypalec_Signature" value="">
                                <span class="help-block">Leave blank if you do not want to change your detail.</span>
                                @error('paypalec_Signature')
                                <label class="error" for="paypalec_Signature" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="paypalec_Transaction_Mode">Paypal Transaction Mode</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[paypalec_Transaction_Mode]" value="No">
                                <select name="pm_details[paypalec_Transaction_Mode]" class="form-control">
                                    <option value="Sandbox"  {{ $payment_method_settings['paypalec_Transaction_Mode'] == "Sandbox" ? "selected" : ''  }} >Sandbox</option>
                                    <option value="Live"  {{ $payment_method_settings['paypalec_Transaction_Mode'] == "Live" ? "selected" : ''  }} >Live</option>
                                  </select>
                                <span class="help-block">Processing mode (Sandbox for test mode and Live for real processing)</span>
                                @error('paypalec_Transaction_Mode')
                                <label class="error" for="paypalec_Transaction_Mode" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        @endif

                        @if($payment_method->pm_group_name == "PAYMENT_AUTHORIZENETCC")
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="authorizenetcc_Login">Merchant Login ID </label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[authorizenetcc_Login]" value="Yes">
                                <input name="pm_details[authorizenetcc_Login]" type="text" class="form-control" id="authorizenetcc_Login" value="">
                                <span class="help-block">Leave blank if you do not want to change your detail.</span>
                                @error('authorizenetcc_Login')
                                <label class="error" for="authorizenetcc_Login" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="authorizenetcc_Password">Password</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[authorizenetcc_Password]" value="Yes">
                                <input name="pm_details[authorizenetcc_Password]" type="text" class="form-control" id="authorizenetcc_Password" value="">
                                <span class="help-block">Leave blank if you do not want to change your detail.</span>
                                @error('authorizenetcc_Password')
                                <label class="error" for="authorizenetcc_Password" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="authorizenetcc_Test_Request"> Test Request </label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[authorizenetcc_Test_Request]" value="No">
                                <select name="pm_details[authorizenetcc_Test_Request]" class="form-control">
                                    <option value="TRUE"  {{ $payment_method_settings['authorizenetcc_Test_Request'] == "TRUE" ? "selected" : ''  }} >TRUE</option>
                                    <option value="FALSE"  {{ $payment_method_settings['authorizenetcc_Test_Request'] == "FALSE" ? "selected" : ''  }} >FALSE</option>
                                  </select>
                                <span class="help-block">Processing mode (TRUE for test mode and FALSE for real processing) </span>
                                @error('authorizenetcc_Test_Request')
                                <label class="error" for="authorizenetcc_Test_Request" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="authorizenetcc_Auth_Type">Auth Type</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[authorizenetcc_Auth_Type]" value="No">
                                <select name="pm_details[authorizenetcc_Auth_Type]" class="form-control">
                                    <option value="AUTH_ONLY"  {{ $payment_method_settings['authorizenetcc_Auth_Type'] == "AUTH_ONLY" ? "selected" : ''  }} >AUTH_ONLY</option>
                                    <option value="AUTH_CAPTURE"  {{ $payment_method_settings['authorizenetcc_Auth_Type'] == "AUTH_CAPTURE" ? "selected" : ''  }} >AUTH_CAPTURE</option>
                                    <option value="CAPTURE_ONLY"  {{ $payment_method_settings['authorizenetcc_Auth_Type'] == "CAPTURE_ONLY" ? "selected" : ''  }} >Live</option>
                                  </select>
                                <span class="help-block">Auth-Capture is the normal transaction method; a transaction is sent to the system for approval, the transaction is approved, the merchant is notified of the approval, and the transaction automatically settles at the end of the business day without any further action by the merchant. Auth-Only stands for Authorization-Only and means obtaining an authorization for a certain amount on a customer's credit card without actually charging the card. If the money is not captured within 30 days, the transaction will expire. </span>
                                @error('authorizenetcc_Auth_Type')
                                <label class="error" for="authorizenetcc_Auth_Type" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        @endif

                        @if($payment_method->pm_group_name == "PAYMENT_GOOGLEC")
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="google_Merchant_Id">Merchant Login ID </label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[google_Merchant_Id]" value="Yes">
                                <input name="pm_details[google_Merchant_Id]" type="text" class="form-control" id="google_Merchant_Id" value="">
                                <span class="help-block">Leave blank if you do not want to change your detail.</span>
                                @error('google_Merchant_Id')
                                <label class="error" for="google_Merchant_Id" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="google_Merchant_Key">Merchant Key</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[google_Merchant_Key]" value="Yes">
                                <input name="pm_details[google_Merchant_Key]" type="text" class="form-control" id="google_Merchant_Key" value="">
                                <span class="help-block">Leave blank if you do not want to change your detail.</span>
                                @error('google_Merchant_Key')
                                <label class="error" for="google_Merchant_Key" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="google_Transaction_Mode">Transaction Mode</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[google_Transaction_Mode]" value="No">
                                <select name="pm_details[google_Transaction_Mode]" class="form-control">
                                    <option value="Sandbox"  {{ $payment_method_settings['google_Transaction_Mode'] == "Sandbox" ? "selected" : ''  }} >Sandbox</option>
                                    <option value="Live"  {{ $payment_method_settings['google_Transaction_Mode'] == "Live" ? "selected" : ''  }} >Live</option>
                                  </select>
                                <span class="help-block">Processing mode (Sandbox for test mode and Live for real processing) .</span>
                                @error('google_Transaction_Mode')
                                <label class="error" for="google_Transaction_Mode" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="google_Currency_Code"> Currency Code</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[google_Currency_Code]" value="No">
                                <select name="pm_details[google_Currency_Code]" class="form-control">
                                    <option value="USD">USD</option>
                                  </select>
                                @error('google_Currency_Code')
                                <label class="error" for="google_Currency_Code" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @if($payment_method->pm_group_name == "PAYMENT_BRAINTREECC")
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="braintree_merchant_account_id">Merchant Account Id</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[BRAINTREE_MERCHANT_ACCOUNT_ID]" value="Yes">
                                <input name="pm_details[BRAINTREE_MERCHANT_ACCOUNT_ID]" type="text" class="form-control" id="braintree_merchant_account_id" value="">
                                <span class="help-block">Leave blank if you do not want to change your detail.</span>
                                @error('braintree_merchant_account_id')
                                <label class="error" for="braintree_merchant_account_id" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="braintree_merchant_id">Merchant Id :</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[BRAINTREE_MERCHANT_ID]" value="Yes">
                                <input name="pm_details[BRAINTREE_MERCHANT_ID]" type="text" class="form-control" id="braintree_merchant_id" value="">
                                <span class="help-block">Leave blank if you do not want to change your detail.</span>
                                @error('braintree_merchant_id')
                                <label class="error" for="braintree_merchant_id" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="braintree_public_api_key">Public API Key :</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[BRAINTREE_PUBLIC_API_KEY]" value="Yes">
                                <input name="pm_details[BRAINTREE_PUBLIC_API_KEY]" type="text" class="form-control" id="braintree_public_api_key" value="">
                                <span class="help-block">Leave blank if you do not want to change your detail.</span>
                                @error('braintree_public_api_key')
                                <label class="error" for="braintree_public_api_key" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="braintree_private_api_key">Private API key :</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[BRAINTREE_PRIVATE_API_KEY]" value="Yes">
                                <input name="pm_details[BRAINTREE_PRIVATE_API_KEY]" type="text" class="form-control" id="braintree_private_api_key" value="">
                                <span class="help-block">Leave blank if you do not want to change your detail.</span>
                                @error('braintree_private_api_key')
                                <label class="error" for="braintree_private_api_key" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="braintree_tokenization_key">Tokenization Key :</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[BRAINTREE_TOKENIZATION_KEY]" value="Yes">
                                <input name="pm_details[BRAINTREE_TOKENIZATION_KEY]" type="text" class="form-control" id="braintree_tokenization_key" value="">
                                <span class="help-block">Leave blank if you do not want to change your detail.</span>
                                @error('braintree_tokenization_key')
                                <label class="error" for="braintree_tokenization_key" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="braintree_google_merchant_id">Google Merchant ID :</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[BRAINTREE_GOOGLE_MERCHANT_ID]" value="Yes">
                                <input name="pm_details[BRAINTREE_GOOGLE_MERCHANT_ID]" type="text" class="form-control" id="braintree_google_merchant_id" value="">
                                <span class="help-block">Leave blank if you do not want to change your detail.</span>
                                @error('braintree_google_merchant_id')
                                <label class="error" for="braintree_google_merchant_id" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="braintree_transaction_mode">Transaction Mode :</label>
                            <div class="col-lg-6">
                                <input type="hidden" name="pm_settings_encrypted[BRAINTREE_TRANSACTION_MODE]" value="No">
                                <select name="pm_details[BRAINTREE_TRANSACTION_MODE]" class="form-control">
                                    <option value="sandbox" <?php if(isset($payment_method_settings["BRAINTREE_TRANSACTION_MODE"]) && $payment_method_settings["BRAINTREE_TRANSACTION_MODE"] == "sandbox") echo "selected";?>>sandbox</option>
                                <option value="production" <?php if(isset($payment_method_settings["BRAINTREE_TRANSACTION_MODE"]) && $payment_method_settings["BRAINTREE_TRANSACTION_MODE"] == "production") echo "selected";?>>production</option>
                                </select>
                                <span class="help-block"> Processing mode (Sandbox for test mode and production mode for real processing)</span>
                                @error('braintree_transaction_mode')
                                <label class="error" for="braintree_transaction_mode" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        @endif
                        @endif
                        <hr>
                        <h2 class="card-title mb-4 mt-4">Payment Message Setting</h2>
                        <div class="form-group row">
                            <label class="col-lg-3 control-label text-lg-right pt-2" for="pm_short_desc">Payment Message</label>
                            <div class="col-lg-9">
                                <textarea name="pm_short_desc" class="mceEditor" cols="70" rows="3" id="pm_short_desc">{{nl2br(stripcslashes($payment_method->pm_short_desc))}}</textarea>
                                @error('google_Currency_Code')
                                <label class="error" for="google_Currency_Code" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row action-buttons mt-4">
                        <div class="col-12 col-md-auto">
                            <button type="submit"
                                class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"
                                data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save Changes </button>
                        </div>
                        <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
                                class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
                        </div>
                 </section>                
			
		
	</div>
</div>
</form>
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.payment-method.list') }}";
let url_update = "{{ route('pnkpanel.payment-method.update') }}";
let url_edit = "{{ route('pnkpanel.payment-method.edit', ':id') }}";
let url_delete = "{{ route('pnkpanel.payment-method.delete', ':id') }}";
</script>
<script src="{{ asset('pnkpanel/js/payment_method_edit.js') }}"></script>
@endpush
