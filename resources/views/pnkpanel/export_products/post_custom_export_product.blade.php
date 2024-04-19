@extends('admin.layouts.app')
@section('content')
    <form action="{{ route('admin.product.headerexport') }}" method="post" name="frmRepresentative" id="frompostexoort"
        class="ecommerce-form action-buttons-fixed">
        @csrf
        <div class="row">
            <div class="col">
                <section class="card card-expanded">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                        </div>
                        <h2 class="card-title">Please wait while process the products data</h2>
                        <h2>Process Records From: {{$start_limit}} to {{($start_limit+$end_limit)}}</h2>
                    </header>
                    <div class="card-body">
                            <input type="hidden" name="actType" value="Export" />
                            <input type="hidden" name="start_limit" value="{{$start_limit}}" />
                            <input type="hidden" name="end_limit" value="{{($start_limit+$end_limit)}}" />
                            <input type="hidden" name="process_batch" value="{{$process_batch}}" />
                            <input type="hidden" name="total_batch" value="{{$total_batch}}" />
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
              $("#frompostexoort").submit();
            });
        </script>
    @endpush
