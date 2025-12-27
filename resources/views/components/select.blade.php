@props([
    'name',
    'label',
    'data',
    'key',
    'textShow',
    'selected' => '',
    'upperCase' => false,
    'select2' => '',
    'showKey' => false,
    'disabled' => false,
])



<div class="form-group mb-3">
    <select name="{{ $name }}" id="{{ $name }}" class="form-select {{ $select2 }}" {{ $disabled ? 'disabled' : '' }}>
        <option value="">{{ $label }}</option>
        @foreach ($data as $d)
            @php
                $dKey = is_array($d) ? $d[$key] : $d->$key;
                $dTextShow = is_array($d) ? $d[$textShow] : $d->$textShow;
            @endphp
            <option {{ $dKey == $selected ? 'selected' : '' }} value="{{ $dKey }}">
                {{ $showKey ? $dKey . ' | ' : '' }}
                {{ $upperCase ? strtoupper(strtolower($dTextShow)) : ucwords(strtolower($dTextShow)) }}
            </option>
        @endforeach
    </select>
</div>
