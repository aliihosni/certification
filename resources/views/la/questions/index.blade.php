@extends("la.layouts.app")

@section("contentheader_title", "Questions")
@section("contentheader_description", "Questions listing")
@section("section", "Questions")
@section("sub_section", "Listing")
@section("htmlheader_title", "Questions Listing")

@section("headerElems")
@la_access("Questions", "create")
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Question</button>
@endla_access
@la_access("Questions", "create")
<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal2">Questions From Exel</button>
@endla_access
@endsection

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

<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="example1" class="table table-bordered">
		<thead>
		<tr class="success">
			@foreach( $listing_cols as $col )
			<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
			@endforeach
			@if($show_actions)
			<th>Actions</th>
			@endif
		</tr>
		</thead>
		<tbody>
			
		</tbody>
		</table>
	</div>
</div>

@la_access("Questions", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Question</h4>
			</div>
			{!! Form::open(['action' => 'LA\QuestionsController@store', 'id' => 'question-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
                    @la_form($module)
					
					{{--
					@la_input($module, 'content')
					@la_input($module, 'subject')
					--}}
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endla_access




@la_access("Questions", "create")
<div class="modal fade" id="AddModal2" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Questions From Excel</h4>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<a href="{{ route('excel-file',['type'=>'xls']) }}">Download Excel xls</a> |
						<a href="{{ route('excel-file',['type'=>'xlsx']) }}">Download Excel xlsx</a> |
						<a href="{{ route('excel-file',['type'=>'csv']) }}">Download CSV</a>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<a href="{{ route('excel-file-model',['type'=>'xls']) }}">Download Excel xls Model</a> |
						<a href="{{ route('excel-file-model',['type'=>'xlsx']) }}">Download Excel xlsx Model</a> |
						<a href="{{ route('excel-file-model',['type'=>'csv']) }}">Download CSV Model</a>
					</div>
				</div>
				{!! Form::open(array('route' => 'import-csv-excel','method'=>'POST','files'=>'true')) !!}
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="form-group">
							{!! Form::label('sample_file','Select File to Import:',['class'=>'col-md-3']) !!}
							<div class="col-md-9">
								{!! Form::file('sample_file', array('class' => 'form-control')) !!}
								{!! $errors->first('sample_file', '<p class="alert alert-danger">:message</p>') !!}
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 text-center">
						{!! Form::submit('Upload',['class'=>'btn btn-primary']) !!}
					</div>
				</div>
				{!! Form::close() !!}
			</div>

		</div>
	</div>
</div>
@endla_access

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/question_dt_ajax') }}",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if($show_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	$("#question-add-form").validate({
		
	});
});
</script>
@endpush
