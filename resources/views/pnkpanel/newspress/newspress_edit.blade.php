@extends('pnkpanel.layouts.app')
@section('content')

    <form action="{{ route('pnkpanel.manage-news-press.update') }}" method="post" enctype="multipart/form-data"
        name="frmRepresentative" id="frmRepresentative" class="ecommerce-form action-buttons-fixed">
        <input type="hidden" name="id" value="{{$newspress->newspress_id}}">
        <input type="hidden" name="actType" id="actType"
            value="{{ $newspress->newspress_id > 0 ? 'update' : 'add' }}">
        <input type="hidden" name="type" value="shop">
        <input type="hidden" id="is_delete" name="is_delete" value="no">
        <input type="hidden" id="del_chk" name="del_chk" value="">
        @csrf
        <div class="row">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">News press Information<!--Edit : {{ $newspress->title }}--></h2>
                    </header>
                    <div class="card-body">
                        <form class="form-horizontal form-bordered" method="get">
                            <div class="form-group row">
                                <label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="title">Title<span class="required">*</span></label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $newspress->title) }}">
                                    @error('title')
                                        <label class="error" for="title" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="description">Description<span class="required">*</span></label>
                                <div class="col-lg-6">
                                    <textarea name="description" id="description" class="form-control @error('description') error @enderror">{{ old('description', $newspress->description) }}</textarea>
                                    @error('description')
                                        <label class="error" for="description" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="date">Date</label>
                                <div class="col-lg-6">
                                    <input type="date" class="form-control @error('date') error @enderror" id="date" name="date" value="{{ old('date', $newspress->date) }}"  min="{{ date("Y-m-d"); }}">
                                    @error('date')
                                        <label class="error" for="date" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
									<label class="col-lg-3 col-xl-3 control-label text-lg-right mb-0" for="status">Image</label>
										<div class="col-lg-9 col-xl-9">
											<div class="fileupload @if (!empty($newspress->image) && File::exists(config('const.NEWSPRESS_IMG_PATH').$newspress->image)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
												<div class="input-append">
													<div class="uneditable-input">
														<i class="fas fa-file fileupload-exists"></i>
														<span class="fileupload-preview">@if (!empty($newspress->image) && File::exists(config('const.NEWSPRESS_IMG_PATH').$newspress->image)) {{ $newspress->image }} @endif</span>
													</div>
													<span class="btn btn-default btn-file">
														<span class="fileupload-exists">Change</span>
														<span class="fileupload-new">Select file</span>
														<input type="file" name="image" id="image">
													</span>
													@if (!empty($newspress->image) && File::exists(config('const.NEWSPRESS_IMG_PATH').$newspress->image))
													<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="image" data-subtype="image" data-id="{{ $newspress->newspress_id }}" data-src="{{ config('const.NEWSPRESS_IMG_PATH').$newspress->image }}" data-caption="newspress Zoom Image">View</a>
													@endif
													<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="image" data-subtype="image" data-id="{{ $newspress->newspress_id }}" data-image-name="{{ $newspress->image }}" data-dismiss="fileupload">Remove</a>
												</div>
												@error('image')
												<label class="error" for="image" role="alert">{{ $message }}</label>
												@enderror
											</div>
										</div>
									</div>

                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="type">Type</label>
                                <div class="col-lg-6">
									<div class="radio-custom radio-inline radio-primary">
										<input name="type" id="news_type" type="radio" value="1" {{ (old('type', $newspress->type) == "1" || $newspress->type == "") ? "checked" :  "" }} >
										<label for="news_type"> News</label>
									</div>
									<div class="radio-custom radio-inline radio-primary mb-2 ml-3">
										<input name="type" id="press_type" type="radio" value="2" {{ (old('type', $newspress->type) == "2" || $newspress->type == "") ? "checked" :  "" }} >
										<label for="press_type"> Press</label>
									</div>
                                    @error('type')
                                        <label class="error" for="type" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="status">Status</label>
                                <div class="col-lg-7 col-xl-6">
                                    <select name="status" id="status" class="form-control form-control-modern">
                                        <option value="1" {{ old('status', $newspress->status) == '1' ? 'selected' : '' }}> Active</option>
                                        <option value="0" {{ old('status', $newspress->status) == '0' ? 'selected' : '' }}> Inactive</option>
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
                    data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save News press </button>
            </div>
            <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
                    class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
            </div>
            @if ($newspress->newspress_id > 0)
                <div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0"> <a href="javascript:void(0);"
                        data-id="{{ $newspress->newspress_id }}"
                        class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord">
                        <i class="bx bx-trash text-4 mr-2"></i> Delete News press </a> </div>
        </div>
        @endif
    </form>
@endsection

@push('scripts')
    <script>
        let url_list = "{{ route('pnkpanel.manage-news-press.list') }}";
        let url_edit = "{{ route('pnkpanel.manage-news-press.edit', ':id') }}";
        let url_update = "{{ route('pnkpanel.manage-news-press.update') }}";
        let url_delete = "{{ route('pnkpanel.manage-news-press.delete', ':id') }}";
        let url_bulk_action = "{{ route('pnkpanel.manage-static-page.bulk_action') }}";
        let url_delete_image = "{{ route('pnkpanel.manage-news-press.delete-image') }}";
    </script>
    <script src="{{ asset('pnkpanel/js/newspress_edit.js') }}"></script>
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
