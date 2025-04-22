@props(['PDFbuttonName' => 'PDF', 'CSVbuttonName' => 'CSV'])
<a class="btn btn-outline--dark" href="{{ request()->fullUrlWithQuery(['download' => 'pdf']) }}"><i
        class="la la-download"></i> {{ __($PDFbuttonName) }}</a>
<a class="btn btn-outline--info" href="{{ request()->fullUrlWithQuery(['download' => 'csv']) }}"><i
        class="la la-download"></i> {{ __($CSVbuttonName) }}</a>
