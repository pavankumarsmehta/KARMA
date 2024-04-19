@extends('pnkpanel.layouts.app')

@section('content')



<form action="{{ route('pnkpanel.product.updateexportproduct_view') }}" method="post" name="frmRepresentative" id="frmRepresentative" class="ecommerce-form action-buttons-fixed">

    @csrf

    <div class="row">

        <div class="col">

             <section class="card card-expanded">

                <header class="card-header" style="display: inline;">

                    <h2 class="card-title float-right mr-5"><a href="{{ route('pnkpanel.product.updateimportproduct_view') }}" style="text-decoration:underline;">Import Stock/Price</a></h2>

                </header>

                    

            </section>

            <section class="card @if(Session::has('filename')) card-expanded @else card-collapsed @endif">

                <header class="card-header" style="display: inline;">

                    <div class="card-actions">

                        <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>

                    </div>

                    <h2 class="card-title float-left mr-5 mb-3"><a title="download whole product data csv file" href="{{config('const.SITE_URL')}}/feed_cron/limited_product_export_alldata.php">Export All Data</a></h2>

                    <h2 class="card-title float-left mr-5 mb-3"><a title="download whole product data csv file" href="{{config('const.SITE_URL')}}/feed_cron/limited_product_export_alldata.php?status=1">Export Active Data</a></h2>

                    <h2 class="card-title float-left mr-5 mb-3"><a title="download whole product data csv file" href="{{config('const.SITE_URL')}}/feed_cron/limited_product_export_alldata.php?status=0">Export InActive Data</a></h2>

                    

                </header>

                    @if(isset($_GET['msg']) && !empty($_GET['msg']))

                <div class="card-body" style="display:block;">

                    <p class="center"><strong>{{ $_GET['msg']; }}</strong></P>

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

    location.href = site_url+"/pnkpanel/product/updateexportproduct";

  }

},1000);



</script>

@endpush