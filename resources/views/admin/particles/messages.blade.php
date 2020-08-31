@if($errors->any())
<div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

    @foreach($errors->all() as $error)
        {!! $error !!}<br/>
    @endforeach
</div>
@elseif(session()->get('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

    @if(is_array(json_decode(session()->get('success'), true)))
        {!! implode('', session()->get('success')->all(':message<br/>')) !!}
    @else
        {!! session()->get('success') !!}
    @endif
</div>
@elseif(session()->get('warning'))
<div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

    @if(is_array(json_decode(session()->get('warning'), true)))
        {!! implode('', session()->get('warning')->all(':message<br/>')) !!}
    @else
        {!! session()->get('warning') !!}
    @endif
</div>
@elseif(session()->get('info'))
<div class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

    @if(is_array(json_decode(session()->get('info'), true)))
        {!! implode('', session()->get('info')->all(':message<br/>')) !!}
    @else
        {!! session()->get('info') !!}
    @endif
</div>
@elseif(session()->get('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

    @if(is_array(json_decode(session()->get('error'), true)))
        {!! implode('', session()->get('error')->all(':message<br/>')) !!}
    @else
        {!! session()->get('error') !!}
    @endif
</div>
@elseif(session()->get('message'))
<div class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

    @if(is_array(json_decode(session()->get('message'), true)))
        {!! implode('', session()->get('message')->all(':message<br/>')) !!}
    @else
        {!! session()->get('message') !!}
    @endif
</div>
@endif