@extends('pnkpanel.layouts.app')
@section('content')

<form action="{{ route('pnkpanel.product.export') }}" method="post" name="frmRepresentative" id="frmRepresentative" class="ecommerce-form action-buttons-fixed">
    @csrf
    <div class="row">
        <div class="col">
            <section class="card card-expanded">
                <header class="card-header" >
                    <h2 class="card-title float-right mr-5"><a href="{{ route('pnkpanel.product.import') }}" style="text-decoration:underline;">Import Product </a></h2>
                </header>
                    
            </section>
            <section class="card @if(Session::has('filename')) card-expanded @else card-collapsed @endif">
                <header class="card-header" style="display: inline;">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                    </div>
                    <h2 class="card-title float-left mr-5 mb-3"><a title="download whole product data csv file" href="{{config('const.SITE_URL')}}/feed_cron/product_export_alldata.php">Export All Data</a></h2>
                    <h2 class="card-title float-left mr-5 mb-3"><a title="download whole product data csv file" href="{{config('const.SITE_URL')}}/feed_cron/product_export_alldata.php?status=1">Export Active Data</a></h2>
                    <h2 class="card-title float-left mr-5 mb-3"><a title="download whole product data csv file" href="{{config('const.SITE_URL')}}/feed_cron/product_export_alldata.php?status=0">Export InActive Data</a></h2>
                    
                </header>
                    @if(isset($_GET['msg']) && !empty($_GET['msg']))
                <div class="card-body" style="display:block;">
                    <p class="center"><strong>{{ $_GET['msg']; }}</strong></P>
                </div>
                @endif
                @if(request()->get('export') == 'yes')
                <div class="card-body" style="display:block;">
                    <h5><strong>Step 1 : Export Products</strong></h5>
                    <div class="form-group row">
                        <label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="category_id">Product Category</label>
                        <div class="col-lg-7 col-xl-6">
                            <select name="arr_category_id[]" id="category_id" class="form-control form-control-modern @error('arr_category_id') error @enderror" multiple="multiple" size="15">
                                <option value="" disabled="disabled" style="font-weight:bold; color:#000000;">Select
                                    Product
                                    categories</option>
                                @php
                                $records = App\Models\Category::where('parent_id', '=', '0')
                                ->orderBy('category_name', 'asc')
                                ->with([
                                'childrenRecursive' => function ($query) {
                                $query->orderBy('category_name', 'asc');
                                },
                                ])
                                ->get();
                                $selectedCategoryIdArr = [];
                                //echo var_dump($product->productsCategory); exit;

                                foreach ($product->productsCategory as $productsCategory) {
                                $selectedCategoryIdArr[] = $productsCategory->category_id;
                                }
                                echo implode(App\Http\Controllers\pnkpanel\ProductController::drawCategoryTreeDropdown($records, 0, old('parent_id', $selectedCategoryIdArr)));
                                @endphp
                            </select>
                            @error('arr_category_id')
                            <label class="error" for="arr_category_id[]" role="alert">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-lg-5 col-xl-3 control-label text-lg-right pt-2 mt-1 mb-0" for="manufacture_id">Status</label>
                        <div class="col-lg-7 col-xl-6">
                            <select name="status" id="status" class="form-control form-control-modern @error('status') error @enderror">
                                <option value="All">All</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('status')
                            <label class="error" for="status" role="alert">{{ $message }}</label>
                            @enderror
                            <h5 class="text-danger">(Please be patient - it may take 20-25 seconds)</h5>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12 col-md-auto">
                            <button type="submit" class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord" data-loading-text="Loading...">
                                <i class="bx bx-save text-4 mr-2"></i> Export Product
                            </button>
                        </div>
                        <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0">
                            <a href="javascript:void(0);" class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
                        </div>
                    </div>

                    <input type="hidden" name="export_col_names[]" value="All" />
                    @if(Session::has('filename'))
                    <div id="file_download">
                        <h5><strong>Step 2 : Download Exported CSV File (Click below links to download exported file.)</strong></h5>
                        #<a class="text-danger" href="{{URL('/public/csv_export/')}}/{{Session::get('filename')}}">{{Session::get('filename')}}</a>
                        <label>Right click on link and select "Save Link As" to download.</label>
                    </div>
                    @endif
                </div> 
                @endif
            </section>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

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

    $(".btnSaveExportProductItemsWithLimitedFieldsRecord").click(function() {
        var selected_header_name_Values = $('#header_name').val();
        var selected_cat_id_Values = $('.cat_id').val();

        if (selected_cat_id_Values.length == 0 && selected_header_name_Values.length == 0) {
            alert("Please Select at least one option from both select for Export Product Items With Limited Fields");
            return false;
        }

        if (selected_cat_id_Values.length == 0) {
            alert("Please Select Product Categories for Export Product Items With Limited Fields");
            return false;
        }

        if (selected_header_name_Values.length == 0) {
            alert("Please Select Product Header for Export Product Items With Limited Fields");
            return false;
        }
    });

    @if(Session::has('filenamecustom'))
    $('html, body').animate({
        'scrollTop': $("#file_downloadcustom").position().top
    });
    toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.success("Products Export Successfull.");
    @endif

    setInterval(function(){
  if ($.cookie("fileLoading")=='true') {
    $.removeCookie('fileLoading', { path: '/' });
    location.href = site_url+"/pnkpanel/product/export";
  }
},1000);

</script>
@endpush