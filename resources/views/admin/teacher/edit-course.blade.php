@extends('layouts.app.frame')
@section('title', 'Edit Teacher Course')
@section('description', 'Please make sure to check all input')
@section('breadcrumbs')
	@php echo \Breadcrumbs::render([['title' => 'Teacher', 'url' => url('/admin/teacher')], 
	['title' => 'View#'.$model->user_id, 'url' => url('/admin/teacher/'.$model->user_id)], 'Update Course']) @endphp
@endsection
@section('button')
	<a href="{{ url('/admin/teacher/' . $model->user_id) }}" class="btn btn-info btn-xs no-border">Back</a>
@endsection

@section('content')


    {!! Form::model($model, [
            'method' => 'PATCH',
            'url' => ['/admin/teacher/edit-course', $model->id],
            'files' => true,
            'id' => 'formValidate',
        ]) !!}

        @include ('admin.teacher.form-course', ['submitButtonText' => 'Update', 'userId'=>$model->user_id])

	{!! Form::close() !!}
@endsection