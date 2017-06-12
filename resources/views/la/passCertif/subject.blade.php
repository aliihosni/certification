<html>
<head><link href="{{ asset('la-assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css" /></head>

<title>Correction {{$subject}}</title>
<body>

<h1>Certification</h1>
<small>Correction {{$subject}}</small>
@foreach($questions as $question)
    <div class="container">
        <h4> {{ $question->content }} </h4>
        @foreach($res[$question->id] as $r)
         <div>{{$r->content}}
             <span class="badge"> {{$r->type}} </span>
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