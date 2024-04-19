@extends('pnkpanel.layouts.app')
@section('content')
<form action="{{ route('pnkpanel.bulkmail.update') }}" method="post" name="frmBulkMail" id="frmBulkMail" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="actType" id="actType" value="update">
	@csrf
	
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Bulk Mail Options</h2>
				</header>
				<div class="card-body">
						
						
						<div class="form-group row">
							<label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="usrgroup">Select User Group <span class="required">*</span></label>
							<div class="col-lg-2 col-xl-2">
								<div class="radio-custom radio-inline radio-primary">
									<input name="group" id="group1" type="radio" value="1" {{ (old('group') == "1") ? "checked" :  "" }}/>
									<label for="super_admin"></label>
								</div>
							</div>
							<div class="col-lg-4 col-xl-4" id="orders_field_0">
								<select name="usrgroup" id="usrgroup" class="form-control form-control-modern" onclick="setGroup(0)">
			                      <option value="">== Select == </option>
			                      <option value="1" {{ ( (old('usrgroup') == '1') && (old('group') == "1") ? 'selected' : '') }}>All Customer</option>
			                      <option value="2" {{ ( (old('usrgroup') == '2') && (old('group') == "1") ? 'selected' : '') }}>All Active Customer</option>
			                      <option value="3" {{ ( (old('usrgroup') == '3') && (old('group') == "1") ? 'selected' : '') }}>All Inactive Customer</option>
			                      <option value="4" {{ ( (old('usrgroup') == '4') && (old('group') == "1") ? 'selected' : '') }}>All Newsletter</option>
			                      <option value="5" {{ ( (old('usrgroup') == '5') && (old('group') == "1") ? 'selected' : '') }}>All Active Newsletter </option>
			                      <option value="6" {{ ( (old('usrgroup') == '6') && (old('group') == "1") ? 'selected' : '') }}>All Inactive Newsletter</option>
								</select>
								@error('usrgroup')
								<label class="error" for="usrgroup" role="alert">{{ $message }}</label>
								@enderror
								@error('group')
								<label class="error" for="group" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="usrgroup">Selected Users <span class="required">*</span></label>
							<div class="col-lg-2 col-xl-2">
								<div class="radio-custom radio-inline radio-primary">
									<input name="group" id="group2" type="radio" value="2" {{ (old('group') == "2") ? "checked" :  "" }}/>
									<label for="super_admin"></label>
								</div>
							</div>
							<div class="col-lg-4 col-xl-4">
								<select name="allusrs[]" id="allusrs" class="form-control form-control-modern" multiple="multiple" onclick="setGroup(1)" onchange="setGroup(1)">
			                      <option value="">======Select Customer======</option>
			                      @foreach($res_cust as $customer_key => $customer_value)	
								  <option value="<?=$customer_value['email']?>" {{ (collect(old('allusrs'))->contains($customer_value['email']) && (old('group') == "2")) ? 'selected':'' }}>
								  {{ $customer_value['first_name']." ".$customer_value['last_name']." ( ".$customer_value['email']." )"; }}
								  </option>
								  @endforeach
								</select>
								@error('allusrs')
								<label class="error" for="allusrs" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="usrgroup">Selected Newsletter Client <span class="required">*</span></label>
							<div class="col-lg-2 col-xl-2">
								<div class="radio-custom radio-inline radio-primary">
									<input name="group" id="group3" type="radio" value="4" {{ (old('group') == "4") ? "checked" :  "" }}/>
									<label for="super_admin"></label>
								</div>
							</div>
							<div class="col-lg-4 col-xl-4">
								<select name="allsubscriber[]" id="allsubscriber" class="form-control form-control-modern" multiple="multiple" onclick="setGroup(2)" onchange="setGroup(2)">
			                      <option value="">======Select Newsletter======</option>
			                      @foreach($res_news as $client_key => $client_value)	
								  <option value="<?=$client_value['email']?>" {{ (collect(old('allsubscriber'))->contains($client_value['email']) && (old('group') == "4")) ? 'selected':'' }}>
								  {{ $client_value['email'] }}
								  </option>
								  @endforeach
								</select>
								@error('allsubscriber')
								<label class="error" for="allsubscriber" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="email">Enter Mail Address <span class="required">*</span></label>
							<div class="col-lg-2 col-xl-2">
								<div class="radio-custom radio-inline radio-primary">
									<input name="group" id="group4" type="radio" value="3" {{ (old('group') == "3") ? "checked" :  "" }}/>
									<label for="super_admin"></label>
								</div>
							</div>
							<div class="col-lg-4 col-xl-4">
								<input type="email" class="form-control @error('email') error @enderror" id="email" name="email" value="{{ ( old('group') == "3" ? old('email') : '') }}" onclick="setGroup(3)">
								@error('email')
								<label class="error" for="email" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="message_subject">Subject <span class="required">*</span></label>
							<div class="col-lg-6">
								<input type="text" class="form-control @error('message_subject') error @enderror" id="message_subject" name="message_subject" value="{{ old('message_subject') }}">
								@error('message_subject')
								<label class="error" for="message_subject" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="message_text">Message Text </label>
							<div class="col-lg-9">
								<textarea name="message_text" id="message_text" class="mceEditor" cols="80" rows="5">{{ stripslashes(old('message_text')) }}</textarea>
								@error('message_text')
								<label class="error" for="message_text" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
						
					
				</div>
			</section>
		</div>
	</div>

	<div class="row action-buttons">
		<div class="col-12 col-md-auto">
			<button type="submit"
				class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"
				data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Send Message </button>
		</div>
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
				class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnResetForm" onclick="resetform('frmBulkMail');">Reset</a>
		</div>
	</div>

</form>
@endsection

@push('scripts')
<script>
let url_edit = "{{ route('pnkpanel.bulkmail.index') }}";
let url_update = "{{ route('pnkpanel.bulkmail.update') }}";
</script>
<script src="{{ asset('pnkpanel/js/bulkmail_edit.js') }}"></script>
<script src="{{ asset('pnkpanel/js/tiny_custom_static.js') }}"></script>
@endpush
