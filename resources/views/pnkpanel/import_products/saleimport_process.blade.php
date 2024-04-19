@extends('admin.layouts.app')
@section('content')
<form action="{{ route('admin.saleproduct.sale_post_import') }}" method="post" name="frmRepresentative" id="fromsaleimport"
    class="ecommerce-form action-buttons-fixed">
    @csrf
    <div class="row">
        <div class="col">
            <section class="card card-expanded">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                    </div>
                    <h2 class="card-title">Please wait while process the Sale products CSV data</h2>
                    <h2>Process Records From: {{$start_limit}} to {{($start_limit+$end_limit)}}</h2>
                </header>
                <div class="card-body">
                    <input type="hidden" name="start_limit" value="{{$start_limit + $end_limit}}" />
                    <input type="hidden" name="total_record" value="{{$total_record}}" />
                    <input type="hidden" name="show_error_report" value="{{$show_error_report}}" />
                </div>
                <div class="row action-buttons mt-2">
            </section>
        </div>
    </div>
</form>
@endsection

@push('scripts')
    <script>
        $( document ).ready(function() {
            $("#fromsaleimport").submit();
        });
    </script>
@endpush
