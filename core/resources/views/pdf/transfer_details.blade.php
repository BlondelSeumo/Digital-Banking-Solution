@extends('pdf.layouts.master')
@section('main-content')
    <div class="pdf-card">
        <div class="container">
            <div class="custom--card">
                <div class="card-body">
                    @include('partials.user.transfer_details')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .caption-list-two {
            border: 1px solid #ebebeb;
            padding: 0;
            background: #f7f7f7;
        }

        .caption-list-two li {
            border-bottom: 1px solid #ebebeb;
            padding: 1rem;
        }

        .caption-list-two li::after {
            content: "";
            clear: both;
            display: table;
        }

        .caption-list-two .value {
            float: right;
        }
    </style>
@endpush
