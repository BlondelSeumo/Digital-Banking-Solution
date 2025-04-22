@extends('pdf.layouts.master')
@section('main-content')
    <div class="pdf-card">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-12">
                    <div class="custom--card">
                        <div class="card-body">
                            @include('partials.user.loan_details')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
