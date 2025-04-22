@props(['btn' => 'btn--primary'])
<div class="input-group w-auto flex-fill">
    <x-date-picker/>

    <button class="btn {{ $btn }} input-group-text" type="submit"><i class="la la-search"></i></button>
</div>

