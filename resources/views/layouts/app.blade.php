
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="custom-scroll">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" 
			content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
		<meta name="theme-color" content="red">
		
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		<!-- Comment -->
		<meta name="description" content="{{ config('app.name', 'Laravel') }}">

		<title>{{ config('app.name', 'Laravel') }}</title>

		<!-- Favicon -->
		<link href="{{ asset('/images/app-logo.png') }}" class="rounded" rel="icon">

		<!-- Fonts -->
		<link rel="dns-prefetch" href="//fonts.gstatic.com">

		<!-- Styles -->
		<link rel="stylesheet" href="{{ mix('css/app.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
	</head>

	<body>
		<main id="app" v-cloak>
			<app/>
		</main>
	</body>

	<script>
		const pageOptions = [10, 15, 25, 50],
					code_verifier = "{{ $code_verifier ?? null }}",
					user = @json(Auth::user() ?? [])
	</script>

	<!-- required js -->
	<script src="{{ mix('js/app.js') }}"></script>
</html>
