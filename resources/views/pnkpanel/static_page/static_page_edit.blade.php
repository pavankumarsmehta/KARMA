@extends('pnkpanel.layouts.app')
@section('content')
<?php

if ($static_page->name == "faqs" && $static_page->content != "" ) {
    $category_beauty_json = json_decode($static_page->content, true);

    $category_beauty_json_count = count($category_beauty_json);
    
} else {
    $category_beauty_json_count = 0;
}

?>
    <form action="{{ route('pnkpanel.manage-static-page.update') }}" method="post" enctype="multipart/form-data"
        name="frmRepresentative" id="frmRepresentative" class="ecommerce-form action-buttons-fixed">
        <input type="hidden" name="id" value="{{$static_page->static_pages_id}}">
        <input type="hidden" name="actType" id="actType"
            value="{{ $static_page->static_pages_id > 0 ? 'update' : 'add' }}">
        <input type="hidden" name="type" value="shop">
        <input type="hidden" id="is_delete" name="is_delete" value="no">
        <input type="hidden" id="shop_count" name="shop_count" value="<?php echo $category_beauty_json_count; ?>">
        <input type="hidden" id="shop_count_org" name="shop_count_org" value="<?php echo $category_beauty_json_count; ?>">
        <input type="hidden" id="del_chk" name="del_chk" value="">
        @csrf
        <div class="row">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Static Page Information<!--Edit : {{ $static_page->title }}--></h2>
                    </header>
                    <div class="card-body">
                        <form class="form-horizontal form-bordered" method="get">
                            <div class="form-group row">
                                <label class="col-lg-12 control-label text-right mb-0" for="email"><span
                                        class="required">*</span> <strong>Required Fields</strong></label>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="title">Page Title (for display)<span
                                        class="required">*</span></label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ old('title', $static_page->title) }}">
                                    @error('title')
                                        <label class="error" for="title" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="name">Short Page Name (for
                                    URL)<span class="required">*</span></label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control @error('name') error @enderror" id="name"
                                        name="name" value="{{ old('email', $static_page->name) }}">
                                    @error('name')
                                        <label class="error" for="name" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            @if($static_page->name=="faqs")
                            <div class="form-group row">
                                 <label class="col-lg-3 control-label text-lg-right pt-2" for="content">Faqs<span class="required">*</span></label>
                               <div class="col-lg-9">
                                    <div class="table-responsive">
                                        <table class="table table-ecommerce-simple table-striped mb-0 dataTable no-footer">
                                            <tbody>
                                                <?php for ($i = 0; $i < $category_beauty_json_count; $i++) {
                                                    if ($i % 2 == 0) $rclass = "even";
                                                    else $rclass = "odd"; ?>
                                                    <tr role="row" class="<?php echo $rclass; ?>" id="shop_row<?= ($i + 1); ?>">
                                                        <td>
                                                            <input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2" id="shop_chkbox<?= $i; ?>" name="shop_chkbox<?= $i; ?>" value="<?php echo $i; ?>">
                                                        </td>
                                                        <td>
                                                            Question <?= ($i + 1); ?>: <input type="text" class="form-control " id="shop_question<?= $i; ?>" name="shop_question<?= $i; ?>" value="<?= $category_beauty_json[$i]['shop_question']; ?>">
                                                        </td>
                                                      
                                                        <td>Answer <?= ($i + 1); ?>: <input type="text" class="form-control " name="shop_answer<?= $i; ?>" value="<?= $category_beauty_json[$i]['shop_answer']; ?>"></td>
                                                        
                                                    </tr>
                                                <?php } ?>
                                                <tr role="row" class="even" id="shop_row"></tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-ecommerce-simple table-striped mb-0 dataTable no-footer">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="javascript:void(0);" onclick="add_shop_row('shop');" class="submit-button btn btn-primary" style="text-decoration:none;">Add New Row</a>
                                                        <a href="javascript:void(0);" onclick="delete_shop_row('shop');" class="delete-button btn btn-danger" style="text-decoration:none;">Delete Selected</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="content">Page HTML<span class="required">*</span></label>
                                <div class="col-lg-9">
                                    <textarea name="content" cols="70" rows="4" id="content"
                                        class="mceEditor">{{ old('content', stripcslashes($static_page->content)) }}</textarea>
                                    @error('content')
                                        <label class="error" for="content" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            @endif
                            
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="meta_title">Meta Title</label>
                                <div class="col-lg-6">
                                    <textarea  class="form-control @error('meta_title') error @enderror"
                                        id="meta_title"
                                        name="meta_title">{{ old('meta_title', $static_page->meta_title) }}</textarea>
                                    @error('meta_title')
                                        <label class="error" for="meta_title" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="meta_keywords">Meta
                                    Keywords</label>
                                <div class="col-lg-6">
                                    <textarea class="form-control @error('meta_keywords') error @enderror"
                                        id="meta_keywords"
                                        name="meta_keywords">{{ old('meta_keywords', $static_page->meta_keywords) }}</textarea>
                                    @error('meta_keywords')
                                        <label class="error" for="meta_keywords"
                                            role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="meta_description">Meta
                                    Description</label>
                                <div class="col-lg-6">
                                    <textarea class="form-control @error('meta_description') error @enderror"
                                        id="meta_description"
                                        name="meta_description">{{ old('meta_description', $static_page->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <label class="error" for="meta_description"
                                            role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0"
                                    for="status">Status</label>
                                <div class="col-lg-7 col-xl-6">
                                    <select name="status" id="status" class="form-control form-control-modern">
                                        <option value="1" {{ old('status', $static_page->status) == '1' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0" {{ old('status', $static_page->status) == '0' ? 'selected' : '' }}>
                                            Inactive</option>
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
                    data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save Static Page </button>
            </div>
            <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
                    class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
            </div>
            @if ($static_page->static_pages_id > 0)
                <div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0"> <a href="javascript:void(0);"
                        data-id="{{ $static_page->static_pages_id }}"
                        class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord">
                        <i class="bx bx-trash text-4 mr-2"></i> Delete Static Page </a> </div>
        </div>
        @endif
    </form>
@endsection

@push('scripts')
    <script>
        let url_list = "{{ route('pnkpanel.manage-static-page.list') }}";
        let url_edit = "{{ route('pnkpanel.manage-static-page.edit', ':id') }}";
        let url_update = "{{ route('pnkpanel.manage-static-page.update') }}";
        let url_delete = "{{ route('pnkpanel.manage-static-page.delete', ':id') }}";
        let url_delete_image  = "{{ route('pnkpanel.manage-static-page.delete_image') }}";
    </script>
    <script src="{{ asset('pnkpanel/js/static_page_edit.js') }}"></script>
	<script src="{{ asset('pnkpanel/js/tiny_custom_static.js') }}"></script>
    <script type="text/javascript">
        function add_shop_row(type) {
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
    }
    </script>
@endpush
