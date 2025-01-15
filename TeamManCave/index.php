<?PHP
	error_reporting( E_ALL );
	chdir( __DIR__ );
	( file_exists( 'functions.php' ) ? include_once( 'functions.php' ) : die( 'functions not found' ) );
	( file_exists( 'db-connect/class_database.php' ) ? include_once( 'db-connect/class_database.php' ) : throw500( 'class_database' ) );
	date_default_timezone_set ( 'Australia/Sydney' );
	ini_set( 'display_errors', 1 );
	cleanPostData();

	if( !session_id() ) session_start();
	if( !isset( $_SESSION['guid'] ) || $_SESSION['guid'] == '' ) $_SESSION['guid'] = create_guid();

	$checkedUser = checkuser();
	$checkedAdmin = checkAdmin();
	
	if( ( isset($_REQUEST['saveResult']) && $_REQUEST['saveResult'] == '' ) || 
		 ( isset($_REQUEST['simGame']) && $_REQUEST['simGame'] == '' ) )
	{
		( file_exists( 'ajax.php' ) ? include_once( 'ajax.php' ) : throw500( 'ajax' ) );
		exit;
	}
		
?>
<html>
	<head>
		<meta charset="UTF-8">
		<title>TeamManCave FIFA19 Super League</title>
		<meta name="robots" content="noindex">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="keywords" content="">
		<meta name="description" content="">
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans" >
		<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
		<script src="/js/script.php"></script>
	</head>
	<body>
		<div class="pageController">
			<div class="headerImage">
				<a href="/"><img src="/images/mcd_fif_19_header.png" alt="Page Header Image"></a>
			 <br />
				<a href="/">Home</a> | 
				<a href="/?fixtures">Fixtures</a> | 
				<a href="/?table">Table</a> | 
<?PHP if( $checkedAdmin ) echo '<a href="/?admin">Admin</a> | '; ?>
<?PHP if( !$checkedUser ) echo '<a href="/?login">Login</a>'; ?>
<?PHP if( $checkedUser ) echo '<a href="/?profile">Profile</a> | '; ?>
<?PHP if( $checkedUser ) echo '<a href="/?logout">Log out</a>'; ?>
			</div>
			<h1>TeamManCave's PS4 FIFA19 Super League</h1>
<?PHP
	if( isset($_REQUEST['table']) && $_REQUEST['table'] == '' )
	{
		( file_exists( 'table.php' ) ? include_once( 'table.php' ) : throw500( 'table' ) );
		
	} else if( isset($_REQUEST['fixtures']) && $_REQUEST['fixtures'] == '' ) {
		( file_exists( 'fixtures.php' ) ? include_once( 'fixtures.php' ) : throw500( 'fixtures' ) );
	
	} else if( isset($_REQUEST['profile']) && $_REQUEST['profile'] == '' ) {
		( file_exists( 'profile.php' ) ? include_once( 'profile.php' ) : throw500( 'profile' ) );
	
	} else if( isset($_REQUEST['login']) && $_REQUEST['login'] == '' ) {
		( file_exists( 'login.php' ) ? include_once( 'login.php' ) : throw500( 'login' ) );	
	
	} else if( isset($_REQUEST['admin']) && $_REQUEST['admin'] == '' ) {
		( file_exists( 'admin.php' ) ? include_once( 'admin.php' ) : throw500( 'admin' ) );
	
	} else if( isset($_REQUEST['logout']) && $_REQUEST['logout'] == '' ) {
		( file_exists( 'logout.php' ) ? include_once( 'logout.php' ) : throw500( 'logout' ) );
	} else {
		( file_exists( 'home.php' ) ? include_once( 'home.php' ) : throw500( 'home' ) );
	}
?>
			<div style="text-align:center;">
				<small style="font-size:8px;">
					This subdomain is purely a data host provided for the TeamManCave comp. There is no 
					affiliation to tipping-comp.com.au
				</small>
			</div>
		</div>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-122818102-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', 'UA-122818102-1');
		</script>
	</body>
</html>