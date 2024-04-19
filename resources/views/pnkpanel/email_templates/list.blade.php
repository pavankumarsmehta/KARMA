@extends('pnkpanel.layouts.app')
@section('content')
<div class="row">
	<div class="col">
		<div class="card card-modern">
			<!--<a class="simple-ajax-modal1 btn btn-default" href="http://portotheme.com/html/porto-admin/3.0.0/ajax/ui-elements-modals-ajax.html">Load Ajax Content</a>-->
			<div class="card-body">
				<div class="datatables-header-footer-wrapper">
					<table class="table table-ecommerce-simple table-striped mb-0" id="adminGridDataTable" style="min-width: 750px;">
						<thead>
							<tr>
								<th width="30%">Email Template Name</th>
								<th width="60%">Subject</th>													
								<th width="10%">Action</th>
							</tr>
						</thead>
						<tbody>
							<tr class="odd"><td valign="top" colspan="3" class="dataTables_empty"></td></tr>
						</tbody>
					</table>
					<hr class="solid mt-5 opacity-4">
					
					@include('pnkpanel.component.datatable_footer')
				</div>
			</div>
		</div>
	</div>
</div>

<!-- <div id="custom-content" class="modal-block modal-block-md" style="display: none;">
	<section class="card">
		<header class="card-header">
			<h2 class="card-title"></h2>
		</header>
		<div class="card-body">
			<div class="row">
				<div class="col-md-12 email-body"></div>
			</div>
		</div>
		<footer class="card-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-dismiss">Close</button>
				</div>
			</div>
		</footer>
	</section>
</div> -->

<div class="modal bd-example-modal-xl" id="modalBootstrap" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body"></div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.email-templates.list') }}";
let url_edit = "{{ route('pnkpanel.email-templates.edit', ':id') }}";
let url_update = "{{ route('pnkpanel.email-templates.update') }}";
</script>
<script src="{{ asset('pnkpanel/js/email_templates_list.js') }}"></script>
@endpush

