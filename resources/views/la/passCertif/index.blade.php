@extends("la.layouts.app")

@section("contentheader_title", "Pass Certification")-
@section("contentheader_description", "Pass test")
@section("section", "Certifs")
@section("sub_section", "Listing")
@section("htmlheader_title", "Certifs Listing")



@section("main-content")

<div id="countdowntimer"><span id="hm_timer"></span></div>

{{ Form::open(['route' => [config('laraadmin.adminRoute') . '.passCertif.update', $id], 'method' => 'put', 'style'=>'display:inline']) }}
@foreach($questions as $question)
    {{ $question->content }}
    <div class="form-check">
    @foreach($res[$question->id] as $r)
            {!! Form::checkbox($r->id, $r->content, null,array('class' => 'form-check-input') ) !!}
            {!! Form::label($r->id, $r->content, array('class' => 'form-check-label'))!!}
<br>
    @endforeach
    </div>
@endforeach

<div class="modal-footer">

    <input class="btn btn-success" type="submit" value="Submit" id="submit">
</div>
{{ Form::close() }}
@endsection


@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/countdowntimer/jquery.countdownTimer.css') }}"/>
@endpush
@push('scripts')
<script src="{{ asset('la-assets/plugins/countdowntimer/jquery.countdownTimer.js') }}"></script>
<script>

    $(function () {

            $(function(){
                $('#hm_timer').countdowntimer({
                    hours : {{ floor($duree) }},
                    minutes :{{ ($duree - floor($duree))*100 }},
                    size : "sm",
                    timeUp : timeIsUp ,
                    borderColor : "#313c3f"
                });

                function timeIsUp() {
                    document.getElementById('submit').click();

                }
            });
    });

</script>
@endpush

