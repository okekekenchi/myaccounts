@extends('errors::minimal')

@section('title', __($exception->getMessage() ?: 'Unprocessable entity'))
@section('code', '410')
@section('message', __($exception->getMessage() ?: 'Unprocessable entity'))
