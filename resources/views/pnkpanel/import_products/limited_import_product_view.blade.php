@extends('pnkpanel.layouts.app')
@section('content')
@csrf
    <div class="row">
        <div class="col">
        {{-- <section class="card card-expanded">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                    </div>
                    <h2 class="card-title center">Qualdev team working on it. We will update you soon.</h2>
                </header>
        </secton> --}}    
       
<form action="{{ route('pnkpanel.product.updateimportproduct') }}" enctype="multipart/form-data" method="post" name="frmRepresentative" id="frmRepresentative" class="ecommerce-form action-buttons-fixed">
    @csrf
    <div class="row">
        <div class="col">
            <section class="card card-expanded">
                <header class="card-header" style="display: inline;">
                    <h2 class="card-title float-right mr-5"><a href="{{ route('pnkpanel.product.updateexportproduct_view') }}" style="text-decoration:underline;">Export Stock/Price</a></h2>
                </header>
            </section>
            <section class="card card-expanded">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                    </div>
                    <h2 class="card-title">Upload Products CSV File</h2>
                </header>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="import_product_file">Browse Products CSV File :</label>
                        <div class="col-lg-6">
                            <input type="file" class="form-control" name="import_product_file" id="import_product_file" />
                            <div class="col-lg-7 col-xl-6">
                                @error('import_product_file')
                                <label class="error" for="import_product_file" role="alert">{{ $message }}</label>
                                @enderror
                            </div>
                            <a href="{{asset('public/csv_limited_import/HBA_Sample_Products_Limited.csv')}}">Download Sample CSV File</a>
                        </div>
                    </div>
                </div>
                <div class="row action-buttons mt-2">
                    <div class="col-12 col-md-auto">
                        <button type="submit" class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord" data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Import Product
                        </button>
                    </div>
                    <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);" class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
                    </div>
            </section>
            <section class="card card-expanded">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                    </div>
                    <h2 class="card-title">Import CSV column names must be same</h2>
                </header>
                <div class="card-body">
                    <div class="form-group row">
                        <table width="95%" border="0" cellspacing="0" cellpadding="3" align="center">
                            <tbody>
                                <tr class="lightbg">
                                    <td valign="top" width="18%">
                                    <strong>&nbsp;1</strong>.&nbsp;&nbsp;SKU <br>
                                    <strong>&nbsp;2</strong>.&nbsp;&nbsp;Current Stock <br>
                                    <strong>&nbsp;3</strong>.&nbsp;&nbsp;Retail Price <br>
                                    <strong>&nbsp;4</strong>.&nbsp;&nbsp;Our Price <br>
                                    <strong>&nbsp;5</strong>.&nbsp;&nbsp;Sale Price <br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <label>Notes:</label>
                <li>This section will allow you to upload bulk products data using <strong> .CSV </strong> only.</li>
                <li>CSV file format must be same as <strong>sample CSV</strong> format</li>
        </div>
        </section>
          
    </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('pnkpanel/js/import.js') }}"></script>
<script>
    @if(Session::has('filename'))
    $('html, body').animate({
        'scrollTop': $("#file_download").position().top
    });
    toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.success("Products Export Successfull.");
    @endif
</script>
<script src="{{ asset('admin/js/manage_polls_list.js') }}"></script>

@endpush