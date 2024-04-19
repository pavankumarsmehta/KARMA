@extends('pnkpanel.layouts.app')
@section('content')
<style>
.custom-checkbox .form-check-input {
    display: none;
}

.custom-checkbox .form-check-label {
    padding-left: 25px;
    cursor: pointer;
    margin-bottom:0px;
}

.custom-checkbox .form-check-label:before {
    content: "";
    position: absolute;
    width: 25px;
    height: 25px;
    left: 0;
    top: 0px;
    border: 2px solid #007bff;
    border-radius: 3px;
    background-color: white;
}

.custom-checkbox .form-check-input:checked + .form-check-label:after {
    content: "";
    position: absolute;
    left: 7px;
    top: 1px;
    width: 10px;
    height: 17px;
    border: solid #007bff;
    border-width: 0 3px 3px 0;
    transform: rotate(45deg);
}
</style>
    <form action="{{ route('pnkpanel.instagram-feeds.accept') }}" method="post" name="frmRepresentative" id="frmRepresentative"
        class="ecommerce-form action-buttons-fixed">
        @csrf
        <div class="row">
            <div class="col">
                <div class="row">
                    @foreach($instagram_fetch_feeds as $feed)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card">
                                @if($feed['media_type'] == 'VIDEO')
                                    <video width="100%" controls>
                                        <source src="{{ $feed['media_url'] }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <img src="{{ $feed['media_url'] }}" class="card-img-top" alt="Instagram Feed">
                                @endif
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="form-check custom-checkbox">
                                                <input class="form-check-input" name="feed[{{ $feed['instagram_fetch_feed_id'] }}]" type="checkbox" id="feed_{{ $feed['instagram_fetch_feed_id'] }}">
                                                <label class="form-check-label" for="feed_{{ $feed['instagram_fetch_feed_id'] }}"></label>
                                            </div>
                                        </div>
                                        <div class="col-10 text-right">
                                            <a href="{{ $feed['permalink'] }}" class="btn btn-primary" target="_blank">View on Instagram</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row action-buttons">
            <div class="col-12 col-md-auto">
                <button type="submit"
                    class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"
                    data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Accpet </button>
            </div>
            <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
                    class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
let url_list = "{{ route('pnkpanel.instagram-feeds.list') }}";
let url_bulk_action = "{{ route('pnkpanel.instagram-feeds.bulk_action') }}";
let url_fetch = "{{ route('pnkpanel.instagram-feeds.fetch') }}";
let url_accept = "{{ route('pnkpanel.instagram-feeds.accept', ':id') }}";
</script>
<script src="{{ asset('pnkpanel/js/instagram_feed_list.js') }}"></script>
@endpush
