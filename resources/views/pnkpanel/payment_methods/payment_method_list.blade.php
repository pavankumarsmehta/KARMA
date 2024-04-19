@extends('pnkpanel.layouts.app')
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                </div>
                <h2 class="card-title">Standard Payment Methods</h2>
            </header>
			<div class="card-body">
                <table class="table table-ecommerce-simple table-striped mb-0" style="min-width: 750px;">
                    <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Payment Gateway	
                            </th>
                            <th>
                                Position	
                            </th>
                            <th>
                                Status	
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payment_method_standard as $pdata)
                        <tr>
                            <td>
                                {{$pdata->pm_name}}
                            </td>
                            <td>
                                {{$pdata->pm_gateway_name}}
                            </td>
                            <td>
                                {{$pdata->pm_position}}
                            </td>
                            <td>
                                <span class="badge {{$pdata->pm_status == "Active" ? 'badge-success' : 'badge-danger'}} p-2">{{$pdata->pm_status}}</span>
                            </td>
                            <td>
                                <a href="javascript:void(0);" data-toggle="tooltip" data-id="{{$pdata->pm_id}}" data-original-title="Edit" title="Edit" class="edit btn btn-sm btn-primary btnEditRecord"><i class="bx bx-edit"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.payment-method.list') }}";
let url_edit = "{{ route('pnkpanel.payment-method.edit', ':id') }}";
let url_bulk_action = "{{ route('pnkpanel.payment-method.bulk_action') }}";
</script>
@endpush
