{{-- @if ($message = Session::get('errors')) --}}
{{-- @endif --}}
@if (session('notification'))
    <div class="alert alert-{{session('notification.type')}} alert-dismissible fade show" role="alert">
        @if (is_array(session('notification.message')))
            <ul>
                @foreach (session('notification.message') as $key => $value)
                    <li>{{$value[0]}}</li>
                @endforeach
            </ul>
        @else
            <p>{{session('notification.message')}}</p>
        @endif
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php Session::forget('notification'); Session::save(); ?>
@endif