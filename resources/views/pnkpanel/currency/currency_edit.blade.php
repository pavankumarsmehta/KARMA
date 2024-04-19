@extends('pnkpanel.layouts.app')
@section('content')

    <form action="{{ route('pnkpanel.manage-currency.update') }}" method="post" enctype="multipart/form-data"
        name="frmRepresentative" id="frmRepresentative" class="ecommerce-form action-buttons-fixed">
        <input type="hidden" name="id" value="{{$currency->currencies_id}}">
        <input type="hidden" name="actType" id="actType"
            value="{{ $currency->currencies_id > 0 ? 'update' : 'add' }}">
        <input type="hidden" name="type" value="shop">
        <input type="hidden" id="is_delete" name="is_delete" value="no">
        <input type="hidden" id="del_chk" name="del_chk" value="">
        @csrf
        <div class="row">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Currency Information<!--Edit : {{ $currency->title }}--></h2>
                    </header>
                    <div class="card-body">
                        <form class="form-horizontal form-bordered" method="get">
                            <div class="form-group row">
                                <label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="title">Country Name<span class="required">*</span></label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $currency->title) }}">
                                    @error('title')
                                        <label class="error" for="title" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="name">Code<span class="required">*</span></label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control @error('code') error @enderror" id="code" name="code" value="{{ old('code', $currency->code) }}">
                                    @error('code')
                                        <label class="error" for="code" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="symbol_left">Symbol left</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control @error('symbol_left') error @enderror" id="symbol_left" name="symbol_left" value="{{ old('symbol_left', $currency->symbol_left) }}">
                                    @error('symbol_left')
                                        <label class="error" for="symbol_left" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="symbol_right">Symbol right</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control @error('symbol_right') error @enderror" id="symbol_right" name="symbol_right" value="{{ old('symbol_right', $currency->symbol_right) }}">
                                    @error('symbol_right')
                                        <label class="error" for="symbol_right" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="decimal_point">Decimal Point<span class="required">*</span></label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control @error('decimal_point') error @enderror" id="decimal_point" name="decimal_point" value="{{ old('decimal_point', $currency->decimal_point) }}">
                                    @error('decimal_point')
                                        <label class="error" for="decimal_point" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="thousands_point">Thousands Point</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control @error('thousands_point') error @enderror" id="thousands_point" name="thousands_point" value="{{ old('thousands_point', $currency->thousands_point) }}">
                                    @error('thousands_point')
                                        <label class="error" for="thousands_point" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="decimal_places">Decimal Places<span class="required">*</span></label>
                                <div class="col-lg-6">
                                    <input type="number" class="form-control @error('decimal_places') error @enderror" id="decimal_places" name="decimal_places" value="{{ old('decimal_places', $currency->decimal_places) }}">
                                    @error('decimal_places')
                                        <label class="error" for="decimal_places" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="value">Value<span class="required">*</span></label>
                                <div class="col-lg-6">
                                    <input type="number" class="form-control @error('value') error @enderror" id="value" name="value" value="{{ old('value', $currency->value) }}">
                                    @error('value')
                                        <label class="error" for="value" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="status">Status</label>
                                <div class="col-lg-7 col-xl-6">
                                    <select name="status" id="status" class="form-control form-control-modern">
                                        <option value="1" {{ old('status', $currency->status) == '1' ? 'selected' : '' }}> Active</option>
                                        <option value="0" {{ old('status', $currency->status) == '0' ? 'selected' : '' }}> Inactive</option>
                                    </select>
                                </div>
                            </div>
                    </div>
            </div>
        </div>
        <!--Added code for Category Beauty Images as on 06-10-2023 Start-->

      

    <!--Added code for Category Tile Beauty as on 06-10-2023 End-->
        <div class="row action-buttons">
            <div class="col-12 col-md-auto">
                <button type="submit"
                    class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"
                    data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save Currency </button>
            </div>
            <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
                    class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
            </div>
            @if ($currency->currencies_id > 0)
                <div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0"> <a href="javascript:void(0);"
                        data-id="{{ $currency->currencies_id }}"
                        class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord">
                        <i class="bx bx-trash text-4 mr-2"></i> Delete Currency </a> </div>
        </div>
        @endif
    </form>
@endsection

@push('scripts')
    <script>
        let url_list = "{{ route('pnkpanel.manage-currency.list') }}";
        let url_edit = "{{ route('pnkpanel.manage-currency.edit', ':id') }}";
        let url_update = "{{ route('pnkpanel.manage-currency.update') }}";
        let url_delete = "{{ route('pnkpanel.manage-currency.delete', ':id') }}";
        let url_bulk_action = "{{ route('pnkpanel.manage-static-page.bulk_action') }}";
    </script>
    <script src="{{ asset('pnkpanel/js/currency_edit.js') }}"></script>
    <script type="text/javascript">
        var err_msg1_for_cache = '<?= Session::get('site_common_msg')  ?>';
        if (err_msg1_for_cache != ""){
            $.ajax({
                type: 'POST',
                url: site_url + '/clearfrontcurrencycache',
                data: {},
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log('currency list chache cache clear sucessfully');

                }
            });
        }
      /*  function add_shop_row(type) {
        var beauty_rows = parseInt($("#" + type + "_count").val());

        if (beauty_rows > 0) {
            var index = beauty_rows + 1;
        } else {
            var index = 1;
        }

        var rclass = 'odd';
        if (beauty_rows % 2 == 0) {
            rclass = 'even';
        }

        var shop_html = '<tr role="row" class="' + rclass + '" id="' + type + '_row' + index + '"><td><input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2" id="' + type + '_chkbox' + beauty_rows + '" name="' + type + '_chkbox' + beauty_rows + '" value="" checked></td><td>Question ' + index + ': <input type="text" class="form-control " id="' + type + '_question' + beauty_rows + '" name="' + type + '_question' + beauty_rows + '" value=""></td><td>Answer ' + index + ': <input type="text" class="form-control " id="' + type + '_answer' + beauty_rows + '" name="' + type + '_answer' + beauty_rows + '" value=""></td><tr>';

        if (beauty_rows > 0) {
            $("#" + type + "_row" + beauty_rows).after(shop_html);
        } else {
            $("#" + type + "_row").after(shop_html);
        }

        $("#" + type + "_count").val(index);
    }


    function delete_shop_row(type) {
        var beauty_rows = parseInt($("#" + type + "_count").val());
        var org_beauty_rows = parseInt($("#" + type + "_count_org").val());
        var delete_row = 0;
        var chbox_sel = 'no';
        var del_flag = "false";

        var del_chk = $('.list_checkbox:checked').map(function() {
            return this.value;
        }).get();

        for (var d = 0; d < beauty_rows; d++) {
            if ($("#" + type + "_chkbox" + d).prop('checked') == true) {
                chbox_sel = 'true';
                if (d >= org_beauty_rows) {
                    $("#" + type + "_row" + (d + 1)).remove();
                    delete_row = delete_row + 1;
                } else {
                    del_flag = "true";
                }
            }
        }

        if (del_flag == "true") {
            var ans = window.confirm("Are you sure you want to delete?");
            if (ans) {
                $("#is_delete").val("yes");
                $("#del_chk").val(del_chk);
                $(".btnSaveRecord").trigger('click');
            }
        }

        if (chbox_sel == 'no') {
            alert('Please select checkbox to delete.');
            return false;
        }
        if (delete_row > 0) {
            $("#" + type + "_count").val((beauty_rows - delete_row));
        } 
    }*/
    </script>
@endpush
