@extends('static.template')
@section('content')
  {{ Form::open(array('action' => 'socialImpactPost')) }}
  {{ Form::label('terms', 'terms')}}
  {{ Form::text('terms', null , ['placeholder' => 'term1 | term2 | term3']) }}
  {{ Form::close() }}
@stop
