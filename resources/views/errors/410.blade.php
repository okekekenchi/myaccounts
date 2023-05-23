@extends('errors::minimal')

@section('title', __($exception->getMessage() ?: 'Expired'))
@section('code', '410')
@section('message', __($exception->getMessage()))
