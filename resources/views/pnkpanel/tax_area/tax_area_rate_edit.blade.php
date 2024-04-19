@extends('pnkpanel.layouts.app')
@section('content')
    <form action="{{ route('pnkpanel.tax-area.tax_area_rate_update') }}" method="post" name="frmRepresentative" id="frmRepresentative"
    class="ecommerce-form action-buttons-fixed">
    <input type="hidden" name="id" value="{{ $tax_area->tax_areas_id }}">
    <input type="hidden" name="tax_rates_id" value="{{$tax_area_rate->tax_rates_id}}">
    <input type="hidden" name="actType" id="actType" value="{{ $tax_area_rate->tax_rates_id > 0 ? 'update' : 'add' }}">
    @csrf
    <div class="row">
        <div class="col">
            <section class="card card-expanded">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                    </div>
                    <h2 class="card-title">{{ $tax_area_rate->tax_rates_id > 0 ? 'Add' : 'Edit' }} Tax Rates</h2>
                </header>
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-lg-12 control-label text-right mb-0" for="email"><span
                                class="required">*</span> <strong>Required Fields</strong></label>
                    </div>


                    <div class="form-group row align-items-center">
                        <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="country">Country</label>
                        <div class="col-lg-7 col-xl-6">
                            <select name="country" id="country" class="form-control form-control-modern" disabled> 
                                @foreach ($country as $country)
                                    <option value="{{ $country->countries_iso_code_2 }}"
                                        {{ old('country', $tax_area->country) == $country->countries_iso_code_2 ? 'selected' : '' }}>
                                        {{ $country->countries_iso_code_2 . ' ' . $country->countries_name }}</option>
                                @endforeach
                            </select>
                            @error('country')
                                <label class="error" for="country" role="alert">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="state">States</label>
                        <div class="col-lg-7 col-xl-6">
                            <select name="state" id="state" class="form-control form-control-modern" disabled>
                                <option value="">State Not Applicable</option>
                                @foreach ($state as $state)
                                    <option value="{{ $state->code }}"
                                        {{ old('state', $tax_area->states) == $state->code ? 'selected' : '' }}>
                                        {{ $state->name }}</option>
                                @endforeach
                            </select>
                            @error('state')
                                <label class="error" for="state" role="alert">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label class="col-lg-3 control-label text-lg-right pt-2" for="zip_from">Zip from</label>
                        <div class="col-lg-6">
                            <input type="number" class="form-control @error('zip_from') error @enderror" id="zip_from"
                                name="zip_from" value="{{ old('zip_from', $tax_area->zip_from) }}" disabled>
                            @error('zip_from')
                                <label class="error" for="zip_from" role="alert">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 control-label text-lg-right pt-2" for="zip_to">Zip to</label>
                        <div class="col-lg-6">
                            <input type="number" class="form-control @error('zip_to') error @enderror" id="zip_to"
                                name="zip_to" value="{{ old('zip_to', $tax_area->zip_to) }}" disabled />
                            @error('zip_to')
                                <label class="error" for="zip_to" role="alert">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                    --}} 
                    <div class="form-group row">
                        <label class="col-lg-3 control-label text-lg-right pt-2" for="amount_from">Starting Order Amount <span
                            class="required">*</span></label>
                        <div class="col-lg-6">
                            <input type="number"  step="0.01" class="form-control @error('amount_from') error @enderror" id="amount_from"
                                name="amount_from" value="{{ old('amount_from', $tax_area_rate->amount_from) }}" />
                            @error('amount_from')
                                <label class="error" for="amount_from" role="alert">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 control-label text-lg-right pt-2" for="charge_amount">Charge Percentage(%) <span
                            class="required">*</span></label>
                        <div class="col-lg-6">
                            <input type="number"  step="0.01" class="form-control @error('charge_amount') error @enderror" id="charge_amount"
                                name="charge_amount" value="{{ old('charge_amount', $tax_area_rate->charge_amount) }}" />
                            @error('charge_amount')
                                <label class="error" for="charge_amount" role="alert">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="amount_in_percent">Charge Type</label>
                        <div class="col-lg-7 col-xl-6">
                            <select name="amount_in_percent" id="amount_in_percent" class="form-control form-control-modern">
                                <option value="Y" {{ old('amount_in_percent', $tax_area_rate->amount_in_percent) == 'Y' ? 'selected' : '' }}>
                                    Percent</option>
									{{--<option value="N" {{ old('amount_in_percent', $tax_area_rate->amount_in_percent) == '0' ? 'selected' : '' }}>
                                    Amount</option>--}}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row action-buttons mt-2">
                    <div class="col-12 col-md-auto">
                        <button type="submit"
                            class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"
                            data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> {{ $tax_area_rate->tax_rates_id > 0 ? 'Update' : 'Add' }} Tax Rate
                        </button>
                    </div>
                    <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
                            class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
                    </div>
                    @if($tax_area_rate->tax_rates_id > 0)
                    <div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0"> <a href="javascript:void(0);"
                            data-id="{{ $tax_area->tax_rates_id }}"
                            class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord">
                            <i class="bx bx-trash text-4 mr-2"></i> Delete Tax Rate </a> </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card card-modern">
                <div class="card-body">
                    <header class="card-header">
                        <h2 class="card-title">Tax Area Rates</h2>
                    </header>
                    <div class="datatables-header-footer-wrapper">
                        <div class="datatable-header">
                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-lg-auto ml-auto mb-3 mb-lg-0">
                                    <div class="d-flex align-items-lg-center flex-column flex-lg-row">
                                        <label class="ws-nowrap mr-3 mb-0">Search By:</label>
                                        <select class="form-control select-style-1 search-by" name="search-by">
										{{--<option value="1">Starting Order Amount</option>--}}
                                            <option value="2">Tax Rate</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-auto mb-3 mb-lg-0 pl-lg-1">
                                    <div class="search search-style-1 search-style-1-lg mx-lg-auto">
                                        <div class="input-group">
                                            <input type="text" class="search-term form-control" name="search-term"
                                                id="search-term" placeholder="Search Quote">
                                            <span class="input-group-append">
                                                <button class="btn btn-default" type="submit"><i
                                                        class="bx bx-search"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-auto pl-lg-1 text-center">
                                    <button type="button" class="mb-1 mt-1 mr-1 btn btn-default"
                                        onclick="javascript:location.reload();return;"><i class="fas fa-sync"></i>
                                        Refresh</button>
                                </div>

                            </div>
                        </div>

                        <table class="table table-ecommerce-simple table-striped mb-0" id="adminGridDataTable"
                            style="min-width: 750px;">
                            <thead>
                                <tr>
                                    <th width="2%"><input type="checkbox" id="mainChk" name="select-all"
                                            class="select-all checkbox-style-1 p-relative top-2" value="" /></th>
                                    <th width="40%">Starting Order Amount</th>
                                    <th width="40%">Tax Rate</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="odd">
                                    <td valign="top" colspan="5" class="dataTables_empty"></td>
                                </tr>
                            </tbody>
                        </table>

                        <hr class="solid mt-5 opacity-4">

                        @include('pnkpanel.component.datatable_footer')

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let url_list = "{{ route('pnkpanel.tax-area.tax_area_rate_list',$tax_area->tax_areas_id) }}";
        let url_edit = "{{ route('pnkpanel.tax-area.tax_area_rate_edit',[$tax_area->tax_areas_id, ':id']) }}";
        let url_update = "{{ route('pnkpanel.tax-area.tax_area_rate_update') }}";
        let url_delete = "{{ route('pnkpanel.tax-area.tax_area_rate_delete', ':id') }}";
        let url_bulk_action = "{{ route('pnkpanel.tax-area.bulk_action') }}";
    </script>
    <script src="{{ asset('pnkpanel/js/tax_area_rates_list.js') }}"></script>
@endpush
