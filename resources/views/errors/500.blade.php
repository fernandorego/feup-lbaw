@extends('layouts.errors')

@section('errors')
<div class="error-body">
    <a onClick=Previous()><i class="icon-arrow-left-circle redirect-back-icon"></i></a>
    <div class="error-container row m-0" style="padding-top: 20vh;">
        <div class="error-number col-md-6">
            <div class="error-neon">500</div>
        </div>
        <div class="error-start-flex col-md-6">
            <div class="error-meaning">
                <div class="error-message">Internal Server Error.</div>
                <div class="error-message2">Something went wrong.</div>
            </div>
        </div>
    </div>
</div>
@endsection



@section('scripts')
<script>
    function Previous() {
        window.history.back();
    }
</script>
@endsection