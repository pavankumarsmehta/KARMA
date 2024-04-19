@extends('admin.layouts.app')
@section('content')
<form action="{{ route('admin.saleproduct.import') }}" enctype="multipart/form-data" method="post" name="frmRepresentative" id="frmRepresentative" class="ecommerce-form action-buttons-fixed">
    @csrf
    <div class="row">
        <div class="col">
            <section class="card card-expanded">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                    </div>
                    <h2 class="card-title float-left">Upload Products Sale CSV File</h2>
					<h4 class="card-title float-right" style="padding-right: 50px;">
                        <a title="download whole product data csv file" href="{{config('const.SITE_URL')}}/feed_cron/product_export_saledata.php">Export Sale data</a>
                    </h4>
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
                            <a href="{{asset('public/csv_import/hbasales_SaleProduct_Sample.csv')}}">Download Sample CSV File</a>
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
                                        <?php
                                        $count = 0;
                                        if (count((array)$columns) > 0) {
                                            for ($ii = 0; $ii < count((array)$columns); $ii++) {
                                                if ($columns[$ii]->Field != "sale_product_id") {
                                                    if ($count % 20 == 0) {
                                        ?></td>
                                    <td valign="top" width="18%"><?php } ?>

                                    <?php
                                                    
                                                        $fieldName = $columns[$ii]->Field;
                                                    
                                    ?>
                                    <strong>&nbsp;<?= $ii ?></strong>.&nbsp;&nbsp;<?= trim($fieldName) ?> <br>
                        <?php
                                                }
                                                $count++;
                                            }
                                        }
                        ?>
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