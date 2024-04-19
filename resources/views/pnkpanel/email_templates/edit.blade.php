@extends('pnkpanel.layouts.app')
@section('content')
    <form action="{{ route('pnkpanel.email-templates.update') }}" method="post" name="frmEmailTemplate" id="frmEmailTemplate"
        class="ecommerce-form action-buttons-fixed">
        <input type="hidden" name="id" value="{{ $emailtemplate->email_templates_id }}">
        <input type="hidden" name="actType" id="actType" value="{{ $emailtemplate->email_templates_id > 0 ? 'update' : 'add' }}">
        @csrf
        <div class="row">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">{{ $emailtemplate->title }}</h2>
                    </header>
                    <div class="card-body">
                        <form class="form-horizontal form-bordered" method="get">
							<div class="form-group row">
								<label class="col-lg-12 control-label text-right mb-0" for="email"><span class="required">*</span> <strong>Required Fields</strong></label>
							</div>
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="title">Email Template Name </label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="title" name="title" value="{{ $emailtemplate->title }}" disabled>
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="subject">Email Subject <span class="required">*</span></label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject', $emailtemplate->subject) }}">
                                    @error('subject')
                                    <label class="error" for="title" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                             <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="mail_body">Email Content <span class="required">*</span></label>
                                <div class="col-lg-9">
                                    <textarea name="mail_body" id="mail_body" class="mceEditor" cols="80" rows="15">{{ stripslashes(old('mail_body', $emailtemplate->mail_body)) }}</textarea>
                            @error('mail_body')
                            <label class="error" for="mail_body" role="alert">{{ $message }}</label>
                            @enderror
                                </div>
                            </div>
                            
                    </div>
            </div>
        </div>

        <div class="row action-buttons">
            <div class="col-12 col-md-auto">
                <button type="submit"
                    class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"
                    data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save Changes </button>
            </div>
            <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
                    class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
            </div>
            
            </div>
    </form>
@endsection

@push('scripts')
    <script>
        let url_list = "{{ route('pnkpanel.email-templates.list') }}";
        let url_edit = "{{ route('pnkpanel.email-templates.edit', ':id') }}";
        let url_update = "{{ route('pnkpanel.email-templates.update') }}";
        let url_delete = "{{ route('pnkpanel.email-templates.delete', ':id') }}";
    </script>
    <script src="{{ asset('pnkpanel/js/email_templates_edit.js') }}"></script>
	<script src="{{ asset('pnkpanel/js/tiny_custom.js') }}"></script>
@endpush
