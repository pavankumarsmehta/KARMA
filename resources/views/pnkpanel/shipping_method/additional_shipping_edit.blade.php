@extends('pnkpanel.layouts.app')
@section('content')
    <form action="{{ route('pnkpanel.shipping-method-charge.update') }}" method="post" name="frmRepresentative" id="frmRepresentative"
        class="ecommerce-form action-buttons-fixed">
        <input type="hidden" name="id" value="{{ $shipping_charge->shipping_rule_id }}">
        <input type="hidden" name="shipping_mode_id" value="{{$id}}">
        <input type="hidden" name="actType" id="actType" value="{{ $shipping_charge->shipping_rule_id > 0 ? 'update' : 'add' }}">
        @csrf
        <div class="row">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">{{ $shipping_charge->shipping_rule_id > 0 ? 'Update' : 'Add' }} Additional Shipping Charge</h2>
                    </header>
                    <div class="card-body">
                        <form class="form-horizontal form-bordered" method="get">
							<div class="form-group row">
								<label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
							</div>
                            <div class="form-group row align-items-center">
                                <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="country">Country<span class="required">*</span></label>
                                <div class="col-lg-7 col-xl-6">
                                    <select name="country" id="country" class="form-control form-control-modern"> 
                                        @foreach ($country as $country)
                                            <option value="{{ $country->countries_iso_code_2 }}"
                                                {{ old('country', $shipping_charge->country) == $country->countries_iso_code_2 ? 'selected' : '' }}>
                                                {{ $country->countries_iso_code_2 . ' ' . $country->countries_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <label class="error" for="country" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="state">States<span class="required">*</span></label>
                                <div class="col-lg-7 col-xl-6">
                                    <select name="state" id="state" class="form-control form-control-modern">
                                        <option value="">State Not Applicable</option>
                                        @foreach ($state as $state)
                                            <option value="{{ $state->code }}"
                                                {{ old('state', $shipping_charge->state) == $state->code ? 'selected' : '' }}>
                                                {{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                        <label class="error" for="state" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                           
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="additonal_charge">Additional Charges <span class="required">*</span></label>
                                <div class="col-lg-6">
                                    <input type="number" class="form-control @error('additonal_charge') error @enderror" id="additonal_charge" name="additonal_charge"  value="{{ old('additonal_charge', $shipping_charge->additonal_charge) }}">
                                    @error('additonal_charge')
                                    <label class="error" for="additonal_charge" role="additonal_charge">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                    </div>
            </div>
        </div>

        <div class="row action-buttons">
            <div class="col-12 col-md-auto">
                <button type="submit"
                    class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"
                    data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> {{ $shipping_charge->shipping_rule_id > 0 ? 'Update' : 'Add' }} Shipping Charge </button>
            </div>
            <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
                    class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
            </div>
            @if($shipping_charge->shipping_rule_id > 0)
                <div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0"> <a href="javascript:void(0);"
                        data-id="{{ $shipping_charge->shipping_rule_id }}"
                        class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord">
                        <i class="bx bx-trash text-4 mr-2"></i> Delete Shipping Charge </a> </div>
                </div>
            @endif
    </form>
@endsection

@push('scripts')
    <script>
        let url_list = "{{ route('pnkpanel.shipping-method-charge.list',$id) }}";
        let url_edit = "{{ route('pnkpanel.shipping-method-charge.edit', ':id') }}";
        let url_update = "{{ route('pnkpanel.shipping-method-charge.update') }}";
        let url_delete = "{{ route('pnkpanel.shipping-method-charge.delete', ':id') }}";
    </script>
    <script src="{{ asset('pnkpanel/js/additional_shipping_edit.js') }}"></script>
@endpush
