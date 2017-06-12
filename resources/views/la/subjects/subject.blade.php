<html>
<head><link href="{{ asset('la-assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css" /></head>

<title>Subject {{$id}}</title>
<body>

<h1>Subject {{$id}} </h1>
@foreach($questions as $question)
    <div class="container">
        <h4> {{ $question->content }} </h4>
        @foreach($res[$question->id] as $r)
            <div>
                <label class="checkbox-circle"><input type="checkbox" value="{{$r->content}}">{{$r->content}}</label>
            </div>
@endforeach
    </div>
@endforeach
<br>
<br>
<footer>
    @include('la.layouts.partials.footer')
</footer>

</body>
</html>