<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>@yield('title')</title>
			
		<link href="{{ mix('css/app.css') }}" rel="stylesheet">
		<link href="{{ asset('css/style.css') }}" rel="stylesheet">

		<style>
		</style>
	</head>
	
	<body class="antialiased user-select-none">
		<div class="d-flex pt-1 pl-2" style="height: 48px; background: #f5f5f5;">
			<img src="{{ asset('/images/app-logo.png') }}" style="width:50.5px; height:40.5px" />

			<span class="pt-1 text-uppercase text-smoothen">
				<b>kenmaxi</b>
			</span>
		</div>

		<div>
			<div class="d-flex" style="margin-left: 60px; margin-top: 8px;">
				<div class="mr-2 text-smoothen" style="color: grey; font-size: 60px;">
					@yield('code')
				</div>
				
				<div class="text-capitalize text-smoothen"
					style="margin-top:37px; font-size: 15px; letter-spacing:1px; font-family: calibri">
					@yield('message')
				</div>
			</div>

			
			<div class="text-smoothen pr-3 pl-3">
				<p>  
					The page you requested cannot be found. @yield('reason')
				</p><br>
					
					<b>Quick Fix:</b><br>

				<p>
					If you manually entered the URL in the address bar, verify that it is spelt correctly.<br>
					Open the <a class="text-decoration-none" href="/login">home page </a>
					and select the link to the information you need.<br>
				</p>
			</div>
				
		</div>
	
	</body>
</html>