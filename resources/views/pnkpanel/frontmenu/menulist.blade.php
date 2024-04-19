@extends('pnkpanel.layouts.app')
@section('content')
<style type="text/css">
	.main-accordion{border:2px solid #c8c8c8;}
	.main-accordion:not(:first-child){margin-top:20px;}
	.main-accordion .panel-collapse{padding:20px 20px;}
	.panel{border-color:#EEEEEE !important;}
	.panel > .panel-heading {background-color: #f6f6f6;color: #000;text-align: left;border-color:black !important;font-size: 20px;padding:20px 20px;}
	.panel > .panel-heading .panel-title{margin:0 0;}

	.panel> .panel-body{text-align: left;font-family: "Comic Sans MS";font-size: 35px;}
	.glyphicon{font-size: 20px !important;text-align: left !important;}
	#accordion{padding:20px;background:#fff;}
	.internal-title{border:2px solid #c8c8c8;}
	.internal-title:not(:first-child){margin-top:20px;}
</style>
<?php

?>

<div class="row">
	<div class="col">
		<section class="card">
			<header class="card-header">
				<div class="card-actions"></div>
				<h2 class="card-title">Menu List</h2>
				
			</header>
			<div class="panel-group" id="accordion">
				@foreach(App\Models\Frontmenu::where('parent_id','=','0')->get() as $firstlevel)
				<div class="panel panel-default main-accordion">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#outside_{{$firstlevel->menu_id}}">{{$firstlevel->menu_title}}</a>
						</h4>
					</div>
					@foreach(App\Models\Frontmenu::where('parent_id','=',$firstlevel->menu_id)->get() as $secondlevel)
					
					<div id="outside_{{$firstlevel->menu_id}}" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="panel-group" id="accordion1">
								<div class="panel internal-title">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion1" class="accordion1_title" href="#inside_{{$secondlevel->menu_id}}">{{$secondlevel->menu_title}}</a>
											<a style="float:right;" href="javascript:void(0);" data-toggle="tooltip" data-id="{{$secondlevel->menu_id}}/subcatadd" data-original-title="Add" title="Add" class="edit btn btn-sm btn-primary btnEditRecord">Add {{$secondlevel->menu_title}}</a>

											<a style="float:right;" href="javascript:void(0);" data-toggle="tooltip" data-id="{{$secondlevel->menu_id}}" data-original-title="Edit" title="Edit" class="edit btn btn-sm btn-primary btnEditRecord"><i class="bx bx-edit"></i></a>
										</h4>
									</div>
									<div id="inside_{{$secondlevel->menu_id}}" class="panel-collapse collapse">
										<div class="panel-body">
											@php
											$thirdlevel=App\Models\Frontmenu::where('parent_id','=',$secondlevel->menu_id)->get();
											@endphp
											@if(count($thirdlevel) > 0 )


											<table cellpadding="5" cellspacing="5" border="1" width="100%" class="table table-ecommerce-simple table-striped mb-0">
												<tr>
													<th width="30%">Title</th>
													<th width="20%">Link</th>
													<th width="10%">Rank</th>
													<th width="10%">Status</th>
													<th width="20%">Action</th>
												</tr>
												@foreach(App\Models\Frontmenu::where('parent_id','=',$secondlevel->menu_id)->get() as $thirdlevel)
					
												<tr>
													<td>{{$thirdlevel->menu_title}}</td>
													<td>{{$thirdlevel->menu_link}}</td>
													<td>{{$thirdlevel->rank}}</td>
													<td>@if($thirdlevel->status =='1') <span class="badge badge-success">Active</span> @else <span class="badge badge-danger">Inactive</span> @endif</td>
													<td>
														<a href="javascript:void(0);" data-toggle="tooltip" data-id="{{$thirdlevel->menu_id}}" data-original-title="Edit" title="Edit" class="edit btn btn-sm btn-primary btnEditRecord"><i class="bx bx-edit"></i></a>
														<a href="javascript:void(0);" data-toggle="tooltip" data-id="{{$thirdlevel->menu_id}}" data-original-title="Delete" title="Delete" class="delete btn btn-sm btn-danger btnDeleteRecord"><i class="bx bx-trash"></i></a>
													</td>
												</tr>
												@endforeach
											</table>
											@endif
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					@endforeach
				</div>
				@endforeach
				
			</div> 
		</section>
	</div>
	
</div>
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.frontmenu.menulist') }}";
let url_edit = "{{ route('pnkpanel.frontmenu.menuedit', ':id',':parent') }}";
let url_update = "{{ route('pnkpanel.frontmenu.update') }}";
let url_delete = "{{ route('pnkpanel.frontmenu.delete', ':id') }}";
let url_bulk_action = "{{ route('pnkpanel.frontmenu.bulk_action') }}";
let url_bulk_action_update_rank = "{{ route('pnkpanel.frontmenu.bulk_action_update_rank') }}";

</script>
<script src="{{ asset('pnkpanel/js/frontmenu_list.js') }}"></script>
@endpush
