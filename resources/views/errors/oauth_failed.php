@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '403')
@section('message', __($message))

@section('reason', 'The page might have been expired, removed, had its name changed, or is temporarily unavailable.')

