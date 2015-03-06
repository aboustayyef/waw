@extends('admin.layout')
@section('content')

@if($errors->any())
<div class="row">
  <div class="col-log-12">
    <ul>
      {{implode('',$errors->all('<li>:message</li>'))}}
    </ul>
  </div>
</div>
@endif

<div class="row">
  <div class="col-lg-12">
    <h1>Enter Blog Details</h1>
  </div>
</div>

{{ Form::open(array('url' => '/admin/addBlog/step2', 'method' => 'get')) }}
<div class="row">
  <div class="col-lg-6">
      <div class="form-group">
        {{ Form::label('url', 'Blog Address')}}
        {{ Form::text('url', null ,['placeholder' => 'enter URL here', 'class'=>'form-control']) }}
      </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
      <div class="form-group">
        {{ Form::label('twitter', 'Twitter Handle')}}
        {{ Form::text('twitter', null ,['placeholder' => 'Twitter Handle (without @)', 'class'=>'form-control']) }}
      </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
    <button type="submit" class="btn btn-default">Submit</button>
  </div>
</div>

{{Form::close()}}
@stop
