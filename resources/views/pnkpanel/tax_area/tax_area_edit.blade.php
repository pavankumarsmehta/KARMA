@extends('pnkpanel.layouts.app')
@section('content')
    <form action="{{ route('pnkpanel.tax-area.update') }}" method="post" name="frmRepresentative" id="frmRepresentative"
        class="ecommerce-form action-buttons-fixed">
        <input type="hidden" name="id" value="{{ $tax_area->tax_areas_id }}">
        <input type="hidden" name="actType" id="actType" value="{{ $tax_area->tax_areas_id > 0 ? 'update' : 'add' }}">
        @csrf
        <div class="row">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Tax Area Settings</h2>
                    </header>
                    <div class="card-body">
                        <form class="form-horizontal form-bordered" method="get">
							<div class="form-group row">
								<label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
							</div>
                            <div class="form-group row align-items-center">
                                <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0"
                                    for="country">Country</label>
                                <div class="col-lg-7 col-xl-6">
                                    <select name="country" id="country" class="form-control form-control-modern">
                                        @foreach($country as $country)
                                        <option value="{{$country->countries_iso_code_2}}" {{ old('country', $tax_area->country) == $country->countries_iso_code_2 ? 'selected' : '' }}>
                                        {{$country->countries_iso_code_2.' '.$country->countries_name}}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                    <label class="error" for="country" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row align-items-center">
                                <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0"
                                    for="state">State <span class="required">*</span></label>
                                <div class="col-lg-7 col-xl-6">
                                    <select name="state" id="state" class="form-control form-control-modern">
                                        <option value="">Select State</option>
                                        @foreach($state as $state)
                                        <option value="{{$state->code}}" {{ old('state', $tax_area->states) == $state->code ? 'selected' : '' }}>
                                            {{$state->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                    <label class="error" for="state" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="tax_region_name">Tax Region Name</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="tax_region_name" name="tax_region_name" value="{{ old('tax_region_name', $tax_area->tax_region_name) }}">
                                    @error('tax_region_name')
                                    <label class="error" for="tax_region_name" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            {{--
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="zip_from">Zip From</label>
                                <div class="col-lg-6">
                                    <input type="number" class="form-control @error('zip_from') error @enderror" id="zip_from" name="zip_from" value="{{ old('zip_from', $tax_area->zip_from) }}">
                                    @error('zip_from')
                                    <label class="error" for="zip_from" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="zip_to">Zip To</label>
                                <div class="col-lg-6">
                                    <input type="number" class="form-control @error('zip_to') error @enderror" id="zip_to" name="zip_to" value="{{ old('zip_to', $tax_area->zip_to) }}"/>
                                    @error('zip_to')
                                    <label class="error" for="zip_to" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            --}}
                            <div class="form-group row align-items-center">
                                <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0"
                                    for="status">Status</label>
                                <div class="col-lg-7 col-xl-6">
                                    <select name="status" id="status" class="form-control form-control-modern">
                                        <option value="1" {{ old('status', $tax_area->status) == '1' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0" {{ old('status', $tax_area->status) == '0' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>
                    </div>
            </div>
        </div>

        <div class="row action-buttons">
            <div class="col-12 col-md-auto">
                <button type="submit"
                    class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"
                    data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save Tax Area </button>
            </div>
            <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
                    class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
            </div>
            @if($tax_area->tax_areas_id > 0)
                <div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0"> <a href="javascript:void(0);"
                        data-id="{{ $tax_area->tax_areas_id }}"
                        class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord">
                        <i class="bx bx-trash text-4 mr-2"></i> Delete Tax Area </a> </div>
                </div>
            @endif
    </form>
@endsection

@push('scripts')
    <script>
        let url_list = "{{ route('pnkpanel.tax-area.list') }}";
        let url_edit = "{{ route('pnkpanel.tax-area.edit', ':id') }}";
        let url_update = "{{ route('pnkpanel.tax-area.update') }}";
        let url_delete = "{{ route('pnkpanel.tax-area.delete', ':id') }}";
    </script>
    <script src="{{ asset('pnkpanel/js/tax_area_edit.js') }}"></script>
@endpush
