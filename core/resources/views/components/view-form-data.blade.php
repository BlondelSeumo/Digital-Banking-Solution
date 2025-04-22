@props(['data' => []])

<div class="row gy-4">
    @foreach ($data as $val)
        <div class="col-md-12">
            <span class="mb-0 fw-bold">{{ __(@$val->name) }}</span>
            @if ($val->type == 'checkbox')
                {{ implode(',', $val->value) }}
            @elseif(@$val->type == 'file')
                @if ($val->value)
                    <a href="{{ route('admin.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}" class="me-3"><i class="fa-regular fa-file"></i> @lang('Attachment') </a>
                @else
                    @lang('No File')
                @endif
            @else
                <p>{{ __(@$val->value) }}</p>
            @endif
        </div>
    @endforeach
</div>
