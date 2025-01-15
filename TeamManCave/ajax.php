<?PHP
	error_reporting( E_ALL );
	chdir( __DIR__ );
	( file_exists( 'functions.php' ) ? include_once('functions.php') : die( 'functions not found' ) );
	( file_exists( 'db-connect/class_database.php' ) ? include_once('db-connect/class_database.php') : throw500( 'class_database' ) );
	date_default_timezone_set ( 'Australia/Sydney' );
	ini_set( 'display_errors', 1 );
	if( !session_id() ) session_start();
	$db = new DATABASE();
	$db->connect();
	header('Content-type: application/json'); 
	cleanPostData();
	$r = '0';
	$actionDada = array();
	
	if(!checkuser()) echo json_encode( array('r'=>'0' ) );
	
	if( isset($_REQUEST['simGame']) && $_REQUEST['simGame'] == '' ) 
	{	
		$r = '0';
		$u=(isset($_POST['u'])?$_POST['u']:'');
		$q=(isset($_POST['q'])?$_POST['q']:'');
		$w=(isset($_POST['w'])?$_POST['w']:'');
		$game=(isset($_POST['game'])?$_POST['game']:'');
		$team=(isset($_POST['team'])?$_POST['team']:'');
		$usr=(isset($_POST['usr'])?$_POST['usr']:'');
		
		if( $_SESSION['u'] == $u && $_SESSION['q'] == $q && $_SESSION['w'] == $w && $game != '') 
		{
			$getSQL = "SELECT * FROM fixtures WHERE gamehash = '" . $game . "'";
			$gamedata = $db->queryFetch( $getSQL );
			
			$change = 'false';
			
			if( $gamedata['hometeam'] == $team ){
			
				if( $gamedata['hometeamsim'] == 'false' ) $change = 'true';
				$SQL = "UPDATE fixtures SET hometeamsim = '" . $change . "' WHERE hometeam = '" . $team . "' AND gamehash = '" . $game . "'";
				$db->query( $SQL );
				$r = $change;
				
			} else if( $gamedata['awayteam'] == $team ) {
				
				if( $gamedata['awayteamsim'] == 'false' ) $change = 'true';
				$SQL = "UPDATE fixtures SET awayteamsim = '" . $change . "' WHERE awayteam = '" . $team . "' AND gamehash = '" . $game . "'";
				$db->query( $SQL );
				$r = $change;
			}
			
			$actionDada['action'] = 'changeSim';
			$actionDada['set_to'] = $change;
			$actionDada['game'] = $game;
			$actionDada['team'] = $team;
			$db->query( "INSERT INTO logs SET user = '" . $u . "', action ='" . json_encode($actionDada) . "', time='" . time() . "'" );
		}
		
	} else if ( isset($_REQUEST['saveResult']) && $_REQUEST['saveResult'] == '' ) {
		$u=(isset($_POST['u'])?$_POST['u']:'');
		$q=(isset($_POST['q'])?$_POST['q']:'');
		$w=(isset($_POST['w'])?$_POST['w']:'');
		$g=(isset($_POST['g'])?$_POST['g']:'');
		$h=(isset($_POST['h'])?$_POST['h']:'');
		$a=(isset($_POST['a'])?$_POST['a']:'');
		if( $_SESSION['u'] == $u && $_SESSION['q'] == $q && $_SESSION['w'] == $w && $g != '') 
		{

			$SQL = "UPDATE fixtures SET hometeamscore = '" . $h . "', awayteamscore='" . $a . "', submitted = '".$_SESSION['u']."' WHERE gamehash = '" . $g . "'";
			$db->query( $SQL );
			$actionDada['action'] = 'submitScore';
			$actionDada['game'] = $g;
			$actionDada['score']['h'] = $h;
			$actionDada['score']['a'] = $a;
			$db->query( "INSERT INTO logs SET user = '" . $u . "', action ='" . json_encode($actionDada) . "', time='" . time() . "'" );
			$r = '1';
		}
	}
	
	$ajaxReply = json_encode(array('r'=>$r));
	echo $ajaxReply;
?>