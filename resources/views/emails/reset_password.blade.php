@extends('emails.template')

@section('content')
  
  <div class="col no-gutters w-100 bg-white"
    style="border-radius: 39em 0px 0 0 !important; height: calc(100vh - 44px);">

    <div class="pl-3 mb-0 text-capitalize">
      <h5>Hi <b>{{ $data->user->first_name ?? $data->user->business_name ?? $data->user->last_name ?? '' }}</b>,</h5>
    </div>

    <div class="col no-gutters pl-3 mt-3">
      <p class="m-0 user-select-none">
        There was a request to reset your account's password.<br>
        If that was you, knidly
      </p><br>
      
      <a href="{{$data->url}}">
        <button class="cursor-pointer shadow font-weight-bold btn btn-success">
          Continue to reset password.
        </button><br><br>
      </a>

      <p class=" user-select-none">
        This password reset link will expire in <i>{{$data->expires_at}} minutes</i> </p>

      <p class=" user-select-none">
        If you did not request to reset your password, then, no further action is required.</p>

      @include('emails.salutation', ['salutation' => $data->salutation])
    </div>
  </div>
  
@endsection