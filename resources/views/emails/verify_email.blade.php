@extends('emails.template')

@section('content')
  
  <div class="col no-gutters w-100 bg-white"
    style="border-radius: 39em 0px 0 0 !important; height: calc(100vh - 44px);">

    <div class="pl-3 mb-0 text-capitalize">
      <h5>Hi <b>{{ $data->user->first_name ?? $data->user->business_name ?? $data->user->last_name ?? '' }}</b>,</h5>
    </div>

    <div class="col no-gutters pl-3 mt-3">
      <p class="m-0 user-select-none">
        Thank you for registering with <a href="{{ env('APP_URL')}}">kenmaxi.</a><br>
        We do not take for granted, that you choose us amongst other like providers
        to meet your business needs.</h6> <br>
        You are a step away from accessing our solution!
      </p><br>

      <span>
        <b class="user-select-none">Verification Code:</b>
        <h4 class="letter-spacing-3 mt-2">{{ $data->otp }}</h4>

        <br>
      </span>

      <p> 
        <a href="{{ $data->url }}">
          Verification link  </a>
        expires in <i>{{$data->expires_at}}  minutes</i>
      </p>
    </div>
  </div>
  
@endsection