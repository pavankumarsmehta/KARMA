@extends('pnkpanel.layouts.app')
@section('content')
    <style>
        .list_column{ 
            list-style-type: none
        }
        </style>
    <form action="{{ route('pnkpanel.tax-area.csv_import') }}" enctype="multipart/form-data" method="post" name="frmImportTaxRules" id="frmImportTaxRules"
    class="ecommerce-form action-buttons-fixed">
    @csrf

    <div class="row">
		<div class="col">
			<section class="card card-modern card-big-info">
				<div class="card-body">
					<div class="row">
						<div class="col-lg-2-5 col-xl-1-5">
							<i class="card-big-info-icon fas fa-file-csv"></i>
							<h2 class="card-big-info-title">Upload Tax Rules And Rates CSV File</h2>
							<p class="card-big-info-desc">This section will allow you to upload bulk Tax Rules And Rates data using .CSV file only.</p>
						</div>
						<div class="col-lg-3-5 col-xl-4-5">
                            <div class="form-group row align-items-center">
                                <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="country">Country</label>
                                <div class="col-lg-7 col-xl-6">
                                    <select name="country" id="country" class="form-control form-control-modern"> 
                                        @foreach ($country as $country)
                                            <option value="{{ $country->countries_iso_code_2 }}"
                                                {{ old('country', 'US') == $country->countries_iso_code_2 ? 'selected' : '' }}>
                                                {{ $country->countries_iso_code_2 . ' ' . $country->countries_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <label class="error" for="country" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
							<div class="form-group row align-items-center">
								<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="browse_csv">Browse CSV File</label>
								<div class="col-lg-7 col-xl-6">
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="input-append">
											<div class="uneditable-input">
												<i class="fas fa-file fileupload-exists"></i>
												<span class="fileupload-preview"></span>
											</div>
											<span class="btn btn-default btn-file">
												<span class="fileupload-exists">Change</span>
												<span class="fileupload-new">Select file</span>
												<input type="file" name="csv_file" id="csv_file" accept=".csv">
											</span>
											<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
										</div>
										@error('csv_file')
											<label class="error" for="csv_file" role="alert">{{ $message }}</label>
										@enderror
                                        <span class="help-block"><a href="{{asset('pnkpanel/sample_taxt_rules.csv')}}"> Download Sample CSV File</a></span>
										 
										
									</div>
								</div>
							</div>
                            <div class="form-group row align-items-center">

                            	<div class="col-lg-5 col-xl-3 text-lg-right mb-0">
                                    <input type="checkbox" id="flush_current_tax_rules" name="flush_current_tax_rules" value="Yes">
                                    
                                </div>

                            	<label class="col-lg-7 col-xl-6 control-label text-lg-left mb-0" for="purge_all">Purge All Tax Rules & Rates</label> 

                            	@error('flush_current_tax_rules')
                                        <label class="error" for="flush_current_tax_rules" role="alert">{{ $message }}</label>
                                    @enderror                               
                                
                            </div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

    
    <div class="row action-buttons">
        <div class="col-12 col-md-auto">
            <button type="submit" class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord" data-loading-text="Loading..."> <i class="bx bx-upload text-4 mr-2"></i> Upload File </button>
        </div>
        <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"  class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a> </div>
      </div>
    </form>
    <div class="row mt-5">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Tax Rules And Rates CSV file column name must be same as below</h2>
				</header>
				<div class="card-body">
					<table class="table-borderless table-responsive-md mb-0">
					   <tbody>
					      <tr>
					      	<td>
						   	@php
								$a = 0;
								$gen_csv_fields_arr = array("State","ZipCode","TaxRegionName","StateRate","EstimatedCombinedRate","EstimatedCountyRate","EstimatedCityRate","EstimatedSpecialRate","RiskLevel");
									foreach($gen_csv_fields_arr as $key => $val)
									{
										$a++;
										if($a<12)
										{
									 		echo '<strong>&nbsp;' .$a . '</strong>.&nbsp;&nbsp;' . $val;
										}
										else
										{
											echo '<strong>' .$a . '</strong>.&nbsp;&nbsp;' . $val;
										}
										
									 	$closetag = "<br>";
										if( $a % 12 == 0 ) {
										 $closetag = "</td><td>";
										}
										 echo $closetag;
									 } 
							 @endphp
							</td>
					      </tr>
					   </tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
@endsection

@push('scripts')
    <script>
        let url_list = "{{route('pnkpanel.tax-area.import_tax_rules_and_rates')}}";
        let url_update = "{{ route('pnkpanel.tax-area.tax_area_rate_update') }}";
        let url_delete = "{{ route('pnkpanel.tax-area.tax_area_rate_delete', ':id') }}";
        let url_bulk_action = "{{ route('pnkpanel.tax-area.bulk_action') }}";
    </script>
    <script src="{{ asset('pnkpanel/js/import_tax_rates.js') }}"></script>
@endpush
