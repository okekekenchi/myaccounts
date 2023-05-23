
<!doctype html>
<html class="custom-scroll">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" 
			content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
		<meta name="theme-color" content="red">
		
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		<!-- Comment -->
		<meta name="description" content="{{ config('app.name', 'Laravel') }}">

		<title>{{$data->name}}</title>

		<style>
			.m-0 { margin: 0  !important; } .m-1 { margin: 0.25rem !important; } .m-2 { margin: 0.5rem !important; } .m-3 { margin: 1rem !important; } .m-4 { margin: 1.5rem !important; } .m-5 { margin: 3rem !important; }
			.ml-0 { margin-left: 0  !important; } .ml-1 { margin-left: 0.25rem !important; } .ml-2 { margin-left: 0.5rem !important; } .ml-3 { margin-left: 1rem !important; } .ml-4 { margin-left: 1.5rem !important; } .ml-5 { margin-left: 3rem !important; }
			.mr-0 { margin-right: 0  !important; } .mr-1 { margin-right: 0.25rem !important; } .mr-2 { margin-right: 0.5rem !important; } .mr-3 { margin-right: 1rem !important; } .mr-4 { margin-right: 1.5rem !important; } .mr-5 { margin-right: 3 !important; }
			.mt-0 { margin-top: 0  !important; } .mt-1 { margin-top: 0.27rem !important; } .mt-2 { margin-top: 0.5rem !important; } .mt-3 { margin-top: 1rem !important; } .mt-4 { margin-top: 1.5rem !important; } .mt-5 { margin-top: 3rem !important; }
			.mb-0 { margin-bottom: 0  !important; } .mb-1 { margin-bottom: 0.25rem !important; } .mb-2 { margin-bottom: 0.5rem !important; } .mb-3 { margin-bottom: 1rem !important; } .mb-4 { margin-bottom: 1.5rem !important; } .mb-5 { margin-bottom: 3rem !important; }


			.p-0 { padding: 0  !important; } .p-1 { padding: 0.25rem !important; } .p-2 { padding: 0.5rem !important; } .p-3 { padding: 1rem !important; } .p-4 { padding: 1.5rem !important; } .p-5 { padding: 3rem !important; }
			.pl-0 { padding-left: 0  !important; } .pl-1 { padding-left: 0.25rem !important; } .pl-2 { padding-left: 0.5rem !important; } .pl-3 { padding-left: 1rem !important; } .pl-4 { padding: 1.5rem !important; } .pl-5 { padding-left: 3rem !important; }
			.pr-0 { padding-right: 0  !important; } .pr-1 { padding-right: 0.25rem !important; } .pr-2 { padding-right: 0.5rem !important; } .pr-3 { padding-right: 1rem !important; } .pr-4 { padding: 1.5rem !important; } .pr-5 { padding-right: 3rem !important; }
			.pt-0 { padding-top: 0  !important; } .pt-1 { padding-top: 0.27rem !important; } .pt-2 { padding-top: 0.5rem !important; } .pt-3 { padding-top: 1rem !important; } .pt-4 { padding: 1.5rem !important; } .pt-5 { padding-top: 3rem !important; }
			.pb-0 { padding-bottom: 0  !important; } .pb-1 { padding-bottom: 0.25rem !important; } .pb-2 { padding-bottom: 0.5rem !important; } .pb-3 { padding-bottom: 1rem !important; } .pb-4 { padding-bottom: 1.5rem !important; } .pb-5 { padding-bottom: 3rem !important; }

			.grey-lighten-5 { background: #f8f8f8; }

			.vh-100 { height: 100vh !important;}
			.h-100 { height: 100% !important;}

			.vw-100 { width: 100vw !important;}
			.w-100 { width: 100% !important;}
			
			.row { display: flex; flex-wrap: wrap; margin-right: -15px; margin-left: -15px; }

			.no-gutters { margin-right: 0; margin-left: 0; }

			.d-flex { display: flex !important; }
    	.justify-content-center {	justify-content: center;}	

			b,strong { font-weight: bolder;}
			p { font-size: small !important;}

			

			@media (max-width: 450px) {
				
			}

			.font-weight-bold { font-weight: bold; }
			.font-size-small { font-size: small !important;}
			.font-size-smaller { font-size: smaller !important;}
			.footer--text { font-size: 10.5px !important;}
			.text-uppercase { text-transform: uppercase; }
			.text-capitalize { text-transform: capitalize; }
			.text-decoration-none { text-decoration: none; }
			.text-black-50 { color: rgba(0, 0, 0, 0.55) !important;}
			.text-center { text-align: center !important; }
			a { color: #007bff; text-decoration: none; background-color: transparent; }
			.letter-spacing-3 { letter-spacing: 3px;}
			.letter-spacing-4 { letter-spacing: 4px;}

			.position-fixed { position: fixed !important; }
			.bottom-0 { bottom: 0 !important; }

			.bg-white { background-color: white; }
			.bg-transparent { background-color: transparent !important; }

			.user-select-none { user-select: none;}

			h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 { margin-bottom: 0rem; font-weight: 500;	line-height: 1.2;	}
			h1, .h1 { font-size: 2.25rem; } h2, .h2 { font-size: 1.8rem; }
			h3, .h3 { font-size: 1.575rem; } h4, .h4 { font-size: 1.35rem; }
			h5, .h5 { font-size: 1.125rem;} h6, .h6 { font-size: 0.9rem; }
			
			textarea::-webkit-scrollbar, .custom-scroll::-webkit-scrollbar {	width: 7px;	height: 7px;}
			textarea::-webkit-scrollbar-thumb:hover,.custom-scroll::-webkit-scrollbar-thumb:hover {	background: #b9b8b8;}
			textarea::-webkit-scrollbar-track, .custom-scroll::-webkit-scrollbar-track { background: transparent; }
			textarea::-webkit-scrollbar-thumb,.custom-scroll::-webkit-scrollbar-thumb {	background: #d8d5d5;	border: none;	border-radius: 10px;}


			body, button, input, div { font-family: calibri !important; letter-spacing: normal !important;
    	text-rendering: optimizeLegibility; -webkit-font-smoothing: auto; }
			

			.card {	position: relative;	display: flex; flex-direction: column;	min-width: 0;	word-wrap: break-word; background-color: #fff;	background-clip: border-box; border: 1px solid rgba(0, 0, 0, 0.125);	border-radius: 0.25rem;	}
			.shadow { box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; }
			.btn { display: inline-block; font-weight: 400; color: #212529; text-align: center; vertical-align: middle; -webkit-user-select: none; -moz-user-select: none; user-select: none; background-color: transparent; border: 1px solid transparent; padding: 0.375rem 0.75rem; font-size: 0.9rem; line-height: 1.6; border-radius: 0.25rem; transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;}
			.btn-success { color: #fff; background-color: #28a745; border-color: #28a745;}
			.btn-success:hover { color: #fff; background-color: #218838; border-color: #1e7e34;}
			.btn-success:focus, .btn-success.focus {color: #fff; background-color: #218838; border-color: #1e7e34; box-shadow: 0 0 0 0.2rem rgba(72, 180, 97, 0.5);}
			.cursor-pointer {cursor: pointer !important;}
		</style>
	</head>

	<body>
		<div class="row col-12 no-gutters vh-100 grey-lighten-5">
			<div class="row no-gutters pl-2 pt-1 w-100 user-select-none">
				<img src="{{asset('/images/app-logo.png')}}" style="max-width:50px; max-height:50px" />

				<h6 class="m-0 pt-1 font-weight-bold text-uppercase">{{'kenmaxi'}}</h6>
			</div>
		
			@section('content')
					
			@show

			@include('emails.Footer')
		</div>
	</body>
</html>
