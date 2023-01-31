<!doctype html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>{{ config('app.name', 'Inventoty Admin') }}</title>
	<meta name="description" content="Ela Admin - HTML5 Admin Template">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
	<link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
	<link rel="stylesheet" href="{{ asset('assets/css/cs-skin-elastic.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/lib/datatable/dataTables.bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
	
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
	<link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">

    <!-- <link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" /> -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	
	<style>
	#weatherWidget .currentDesc {
		color: #ffffff!important;
	}
	.traffic-chart {
		min-height: 335px;
	}
	#flotPie1  {
		height: 150px;
	}
	#flotPie1 td {
		padding:3px;
	}
	#flotPie1 table {
		top: 20px!important;
		right: -10px!important;
	}
	.chart-container {
		display: table;
		min-width: 270px ;
		text-align: left;
		padding-top: 10px;
		padding-bottom: 10px;
	}
	#flotLine5  {
		height: 105px;
	}

	#flotBarChart {
		height: 150px;
	}
	#cellPaiChart{
		height: 160px;
	}
	div#loader {
		position: fixed;
		width: 100%;
		height: 100vh;
		top: 0;
		background: #131313b8;
		left: 0;
		z-index: 999;
		display: none;
		justify-content: center;
		align-items: center;
		color: #fff;
	}
	aside.left-panel {
		width: 180px;
	}
	.right-panel {
		margin-left: 160px;
	}
	.form-control{
		font-size: 0.9rem;
	}
	.navbar .navbar-nav li > a .menu-icon {
		width: 30px;
	}
	#example thead{
		text-align: center;
	}
	.select2-selection {
		min-height: 38px;
	}
	.select2-selection{
		padding: 4px;
		height: 38px !important;
		font-size: 16px;
		border: 1px solid #ced4da !important;
	}
	.dataTables_wrapper .dataTables_paginate .paginate_button {
		padding: 0em 0em;
	}
	.scroll {
		max-height: 300px;
		overflow-y: auto;
	}
</style>
</head>
<body>
	<!-- Left Panel -->
	<aside id="left-panel" class="left-panel">
		<nav class="navbar navbar-expand-sm navbar-default">
			<div id="main-menu" class="main-menu collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li>
						<a href="{{ route('home.index') }}"><i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
					</li>
					<li>
						<a href="{{ route('posts.index') }}"><i class="menu-icon fa fa-dashboard"></i>Posts </a>
					</li>
				</ul>
			</div>
		</nav>
	</aside>
	<!-- /#left-panel -->
	<!-- Right Panel -->
	<div id="right-panel" class="right-panel">
		<!-- Header-->
		<header id="header" class="header">
			<div class="top-left">
				<div class="navbar-header">
					<a class="navbar-brand" href="{{ url('home') }}"><img src="{{ asset('images/logo3.png') }}" alt="Logo"></a>
					<a class="navbar-brand hidden" href="./"><img src="{{ asset('images/logo3.png') }}" alt="Logo"></a>
					<!--<a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>-->
				</div>
			</div>
			<div class="top-right">
				<div class="header-menu">
					<div class="header-left">
                    <div class="user-area dropdown float-right">
                    	<a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    		<img class="user-avatar rounded-circle" src="{{ asset('images/admin.jpg') }}" alt="User Avatar">
                    	</a>
                    	<div class="user-menu dropdown-menu">
                    		<a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i>{{ __('Logout') }}</a>
                    		<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    			@csrf
                    		</form>
                    	</div>
                    </div>

                </div>
            </div>
        </header>
        <!-- /#header -->
        <!-- .content -->
        
        @yield('content')
        
        <!-- /.content -->
        <div class="clearfix"></div>
        <!-- Footer -->
        <footer class="site-footer">
        	<div class="footer-inner bg-white">
        		<div class="row">
        			<div class="col-sm-6">
        				Copyright &copy; <?php echo date('Y') ?>Real Time chat
        			</div>
        			<div class="col-sm-6 text-right">
        				Designed by <a href="https://hashtaginfosystem.com/">Pratik</a>
        			</div>
        		</div>
        	</div>
        </footer>
        <!-- /.site-footer -->
    </div>
	<div id='loader'>
	  <i class="fa fa-spinner fa-spin" style="font-size:54px"></i>
	</div>
    <!-- /#right-panel -->
    <!-- Scripts -->
	
	
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
	
	<script src="{{ asset('assets/js/lib/data-table/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/data-table/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/data-table/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/data-table/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/data-table/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/data-table/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/js/lib/data-table/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/data-table/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/data-table/buttons.colVis.min.js') }}"></script>
    <!--<script src="{{ asset('assets/js/init/datatables-init.js') }}"></script>-->
	
	<!--  Chart js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>

    <!--Chartist Chart-->
    <script src="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartist-plugin-legend@0.6.2/chartist-plugin-legend.min.js"></script>

   <!--  <script src="https://cdn.jsdelivr.net/npm/jquery.flot@0.8.3/jquery.flot.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-pie@1.0.0/src/jquery.flot.pie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-spline@0.0.1/js/jquery.flot.spline.min.js"></script>-->
	<script src="https://cdn.jsdelivr.net/npm/simpleweather@3.1.0/jquery.simpleWeather.min.js"></script>
	<!--  <script src="assets/js/init/weather-init.js"></script> -->

	<script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.js"></script>
	<script src="{{ asset('/assets/js/init/fullcalendar-init.js') }}"></script>
	<script src="{{ asset('js/jquery.tabletoCSV.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
	<script>
        window.laravel_echo_port = '{{ env("LARAVEL_ECHO_PORT") }}';
    </script>
    <script src="//{{ Request::getHost()}}:{{ env('LARAVEL_ECHO_PORT') }}/socket.io/socket.io.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" language="javascript">
   jQuery(document).ready(function () {
		jQuery("#bootstrap-data-table").dataTable();
		jQuery('.remove-record').click(function() {
			var id = jQuery(this).attr('data-id');
			var url = jQuery(this).attr('data-url');
			var token = jQuery('meta[name="csrf-token"]').attr('content');
			jQuery(".remove-record-model").attr("action",url);
			jQuery('body').find('.remove-record-model').append('<input name="_token" type="hidden" value="'+ token +'">');
			jQuery('body').find('.remove-record-model').append('<input name="_method" type="hidden" value="DELETE">');
			jQuery('body').find('.remove-record-model').append('<input name="id" type="hidden" value="'+ id +'">');
		});

		jQuery('.remove-data-from-delete-form').click(function() {
			jQuery('body').find('.remove-record-model').find( "input" ).remove();
		});
		jQuery('.modal').click(function() {
			// $('body').find('.remove-record-model').find( "input" ).remove();
		});
   });
	jQuery(document).ready(function($){
		jQuery('.alert-danger li').each(function() {
			var text = jQuery(this).text();
			jQuery(this).text(text.replace('cat', 'category')); 
		});
		jQuery('.bselect2').select2();

		jQuery("#export").click(function(){
			jQuery("#allocateddata table").tableToCSV();
		});
	});
	jQuery.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
		}
	});
</script>
@yield('script')
</body>
</html>