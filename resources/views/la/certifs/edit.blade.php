@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/certifs') }}">Certif</a> :
@endsection
@section("contentheader_description", $certif->$view_col)
@section("section", "Certifs")
@section("section_url", url(config('laraadmin.adminRoute') . '/certifs'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Certifs Edit : ".$certif->$view_col)

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::model($certif, ['route' => [config('laraadmin.adminRoute') . '.certifs.update', $certif->id ], 'method'=>'PUT', 'id' => 'certif-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'certification')
					@la_input($module, 'user')
					@la_input($module, 'score')
					@la_input($module, 'subject')
					@la_input($module, 'status')
					@la_input($module, 'total')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/certifs') }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
	$("#certif-edit-form").validate({
		
	});
});
</script>
@endpush
