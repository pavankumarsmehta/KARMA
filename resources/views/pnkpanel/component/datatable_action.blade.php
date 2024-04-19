@if(in_array($CurrentRoute, ['pnkpanel.shipping-method.list']))

{{--<a href="javascript:void(0);" data-toggle="tooltip" data-id="{{ $id }}" data-original-title="View State Additional Charge" title="View State Additional Charge" class="edit btn btn-sm btn-primary btnEditRecord"><i class="fa fa-eye"></i></a> --}}

<a href="javascript:void(0);" data-toggle="tooltip" data-id="{{ $id }}" data-original-title="Edit" title="Edit" class="edit btn btn-sm btn-primary btnEditRecord"><i class="bx bx-edit"></i></a>
<a href="javascript:void(0);" data-toggle="tooltip" data-id="{{ $id }}" data-original-title="Delete" title="Delete" class="delete btn btn-sm btn-danger btnDeleteRecord"><i class="bx bx-trash"></i></a>
@elseif(in_array($CurrentRoute, ['pnkpanel.category.list']))
<a href="{{$cat_url}}" data-toggle="tooltip" data-id="{{ $id }}" data-original-title=">View Front Page" target="_blank" title="View Front Page" class="edit btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
<a href="javascript:void(0);" data-toggle="tooltip" data-id="{{ $id }}" data-original-title="Edit" title="Edit" class="edit btn btn-sm btn-primary btnEditRecord"><i class="bx bx-edit"></i></a>
<a href="javascript:void(0);" data-toggle="tooltip" data-id="{{ $id }}" data-original-title="Delete" title="Delete" class="delete btn btn-sm btn-danger btnDeleteRecord"><i class="bx bx-trash"></i></a>

@else

<a href="javascript:void(0);" data-toggle="tooltip" data-id="{{ $id }}" data-original-title="Edit" title="Edit" class="edit btn btn-sm btn-primary btnEditRecord"><i class="bx bx-edit"></i></a>

<a href="javascript:void(0);" data-toggle="tooltip" data-id="{{ $id }}" data-original-title="Delete" title="Delete" class="delete btn btn-sm btn-danger btnDeleteRecord"><i class="bx bx-trash"></i></a>

@endif





@if(in_array($CurrentRoute, ['admin.shape.list']))

<a href="javascript:void(0);" data-toggle="tooltip" data-id="{{ $id }}" data-original-title="View State Additional Charge" title="Clear Cache" class="edit btn btn-sm btn-primary btnClearRecord"><i class="fa fa-eye"></i></a>

@endif