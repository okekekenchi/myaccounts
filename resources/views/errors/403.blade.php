@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))

@section('reason', 'The page might have been expired, removed, had its name changed, or is temporarily unavailable.')

