<?PHP
function checkuser()
{
	if ( isset( $_SESSION['u'] ) && isset( $_SESSION['q'] ) && isset( $_SESSION['w'] ) )
	{
		$db = new DATABASE();
		$db->connect();
		$SQL = "SELECT * FROM users WHERE `user` = '" . $_SESSION['u'] . "'";
		$userDetails = $db->queryFetch( $SQL );
		if( $userDetails != 0 )
		{
			if ( $_SESSION['u'] == $userDetails['user'] &&
					$_SESSION['q'] == sha1( $_SESSION['u'] . $userDetails['id'] ) && 
					$_SESSION['w'] == sha1( $userDetails['pass'] . $userDetails['id'] ) )
					{
				return true;
			}
		}
	}
	return false;
}

function checkAdmin()
{
	if( checkuser() )
	{
		$db = new DATABASE();
		$db->connect();
		$SQL = "SELECT * FROM users WHERE `user` = '" . $_SESSION['u'] . "'";
		$userDetails = $db->queryFetch( $SQL );
		return ( $userDetails['admin'] == 'true' ? true : false );
	}
}


function teamHashToName($db)
{
	$teamSQL = "SELECT * FROM teams ORDER BY name ASC";
	$teamHashs = array();
	$listOfTeams = $db->queryFetchMulti( $teamSQL );
	if( $listOfTeams == 0 ) return $teamHashs;
	foreach( $listOfTeams as $k=>$team )
	{
		$teamHashs[$team['teamhash']] = $team['name'];
	}
	return $teamHashs;
}


function arrangeTableData( $fixtures, $teamHashToName )
{
	$tableData = array();
	$gameListPerTeam = array();

	
	
	foreach( $fixtures as $k => $fixture )
	{
		$gameListPerTeam[$fixture['hometeam']][] = $fixture;
		$gameListPerTeam[$fixture['awayteam']][] = $fixture;
	}

	if( $gameListPerTeam == 0 ) return array();
	
	foreach( $gameListPerTeam as $team => $games )
	{
		if( !isset( $tableData[$team]['pointadjustment'] ) )	{ $tableData[$team]['pointadjustment'] = 0; }
		if( !isset( $tableData[$team]['positionadjustment'] ) )	{ $tableData[$team]['positionadjustment'] = 0; }
		if( !isset( $tableData[$team]['positionadjustmentnote'] ) )	{ $tableData[$team]['positionadjustmentnote'] =''; }
		if( !isset( $tableData[$team]['teamhash'] ) )	{ $tableData[$team]['teamhash'] = $team; }
		if( !isset( $tableData[$team]['name'] ) )			{ $tableData[$team]['name'] = ( isset( $teamHashToName[$team] ) ? $teamHashToName[$team] : $team ); }
		if( !isset( $tableData[$team]['played'] ) )		{ $tableData[$team]['played'] = 0; }
		if( !isset( $tableData[$team]['hplayed'] ) )		{ $tableData[$team]['hplayed'] = 0; }
		if( !isset( $tableData[$team]['aplayed'] ) )		{ $tableData[$team]['aplayed'] = 0; }
		if( !isset( $tableData[$team]['win'] ) )				{ $tableData[$team]['win'] = 0; }
		if( !isset( $tableData[$team]['bye'] ) )				{ $tableData[$team]['bye'] = 0; }
		if( !isset( $tableData[$team]['hwin'] ) )			{ $tableData[$team]['hwin'] = 0; }
		if( !isset( $tableData[$team]['awin'] ) )			{ $tableData[$team]['awin'] = 0; }
		if( !isset( $tableData[$team]['draw'] ) )			{ $tableData[$team]['draw'] = 0; }
		if( !isset( $tableData[$team]['hdraw'] ) )			{ $tableData[$team]['hdraw'] = 0; }
		if( !isset( $tableData[$team]['adraw'] ) )			{ $tableData[$team]['adraw'] = 0; }
		if( !isset( $tableData[$team]['lose'] ) )			{ $tableData[$team]['lose'] = 0; }
		if( !isset( $tableData[$team]['hlose'] ) )			{ $tableData[$team]['hlose'] = 0; }
		if( !isset( $tableData[$team]['alose'] ) )			{ $tableData[$team]['alose'] = 0; }
		if( !isset( $tableData[$team]['points'] ) )			{ $tableData[$team]['points'] = 0; }
		if( !isset( $tableData[$team]['hpoints'] ) )		{ $tableData[$team]['hpoints'] = 0; }
		if( !isset( $tableData[$team]['apoints'] ) )		{ $tableData[$team]['apoints'] = 0; }
		if( !isset( $tableData[$team]['gf'] ) )				{ $tableData[$team]['gf'] = 0; }
		if( !isset( $tableData[$team]['ga'] ) )				{ $tableData[$team]['ga'] = 0; }
		if( !isset( $tableData[$team]['gd'] ) )				{ $tableData[$team]['gd'] = 0; }
		
		foreach( $games as $k => $game )
		{
			if( $game['hometeam'] == $team )
			{
				$tableData[$team]['played'] = $tableData[$team]['played'] + 1;
				$tableData[$team]['hplayed'] = $tableData[$team]['hplayed'] + 1;
				$tableData[$team]['gf'] = $tableData[$team]['gf'] + $game['hometeamscore'];
				$tableData[$team]['ga'] = $tableData[$team]['ga'] + $game['awayteamscore'];
				$tableData[$team]['gd'] = $tableData[$team]['gd'] + $game['hometeamscore'];
				$tableData[$team]['gd'] = $tableData[$team]['gd'] - $game['awayteamscore'];
				
				if( $game['hometeamscore'] > $game['awayteamscore'] ) {
					$tableData[$team]['win'] = $tableData[$team]['win'] + 1;
					$tableData[$team]['hwin'] = $tableData[$team]['hwin'] + 1;
					$tableData[$team]['points'] = $tableData[$team]['points'] + 3;
					$tableData[$team]['hpoints'] = $tableData[$team]['hpoints'] + 3;
				} else if( $game['hometeamscore'] == $game['awayteamscore'] ) {
					$tableData[$team]['draw'] = $tableData[$team]['draw'] + 1;
					$tableData[$team]['hdraw'] = $tableData[$team]['hdraw'] + 1;
					$tableData[$team]['points'] = $tableData[$team]['points'] + 1;
					$tableData[$team]['hpoints'] = $tableData[$team]['hpoints'] + 1;
				} else if( $game['hometeamscore'] < $game['awayteamscore'] ) {
					$tableData[$team]['lose'] = $tableData[$team]['lose'] + 1;
					$tableData[$team]['hlose'] = $tableData[$team]['hlose'] + 1;
				}
			} else {
				$tableData[$team]['played'] = $tableData[$team]['played'] + 1;
				$tableData[$team]['aplayed'] = $tableData[$team]['aplayed'] + 1;
				$tableData[$team]['gf'] = $tableData[$team]['gf'] + $game['awayteamscore'];
				$tableData[$team]['ga'] = $tableData[$team]['ga'] + $game['hometeamscore'];
				$tableData[$team]['gd'] = $tableData[$team]['gd'] + $game['awayteamscore'];
				$tableData[$team]['gd'] = $tableData[$team]['gd'] - $game['hometeamscore'];
				 if( $game['hometeamscore'] < $game['awayteamscore'] ) {
					$tableData[$team]['win'] = $tableData[$team]['win'] + 1;
					$tableData[$team]['awin'] = $tableData[$team]['awin'] + 1;
					$tableData[$team]['points'] = $tableData[$team]['points'] + 3;
					$tableData[$team]['apoints'] = $tableData[$team]['apoints'] + 3;
				} else if( $game['hometeamscore'] == $game['awayteamscore'] ) {
					$tableData[$team]['draw'] = $tableData[$team]['draw'] + 1;
					$tableData[$team]['adraw'] = $tableData[$team]['adraw'] + 1;
					$tableData[$team]['points'] = $tableData[$team]['points'] + 1;
					$tableData[$team]['apoints'] = $tableData[$team]['apoints'] +1;
				} else if( $game['hometeamscore'] > $game['awayteamscore'] ) {
					$tableData[$team]['lose'] = $tableData[$team]['lose'] + 1;
					$tableData[$team]['alose'] = $tableData[$team]['alose'] + 1;
				}
			}
		}
	}
	

	
	if( count( $tableData ) > 0 ){
		/*
			https://stackoverflow.com/questions/4582649/php-sort-array-by-two-field-values
		*/
		// Obtain a list of columns
		foreach ( $tableData as $key => $row ) {
			$points[$key] = $row['points'];
			$gd[$key] = $row['gd'];
		}
		// Sort the data with volume descending, edition ascending
		array_multisort( $points, SORT_ASC, $gd, SORT_ASC, $tableData );
		$tableData = array_reverse( $tableData );	
	}
	
	return array( 'tableData'=>$tableData );
	
}


	
	
	
	
	
	
	
	
	

	
// this is a quick kill script to generate a list of GUIDS
if( isset( $_REQUEST['GUID_LIST'] ) ) { for($x=0;$x<1500;$x++) {echo create_guid() . '<br>';} exit; }

function cleanGetData()
{
	foreach( $_GET as $k=>$v ) $_GET[$k] = stripslashes( $v );
}

function cleanPostData()
{
	foreach( $_POST as $k=>$v ) $_POST[$k] = stripslashes( $v );
}

function debug( $c, $return = 0 )
{
	$HTML = "<div class=\"debug\">";
	if( is_string( $c ) )
	{
		$HTML .= $c;
	} else if( is_array( $c ) || is_object( $c ) ) {
		$HTML .= "<pre>" . print_r( $c,1 ) . "</pre>";
	} else {
		$HTML .= "cant, sorry";
	}
	$HTML .= "</div>";
	if( $return ) return $HTML;
	echo $HTML;
}


function silentDebug( $c )
{
	echo "<!--" . debug( $c, 1 ) . "!-->";
}



function throw404( $additional = "" )
{
	header_status( "404" ); 
	die( "<h1>404 Page not found</h1><small>".$additional."</small>" );
	}

function throw500( $additional = "" )
{
	header_status( "500" ); 
	die( "<h1>500 Page not loaded</h1><small>".$additional."</small>" );
}

function throw403( $additional = "" )
{
	header_status( "203" ); 
	die( "<h1>403 Denied</h1><small>".$additional."</small>" );
}

function header_status($statusCode) {
	static $status_codes = null;
	if ($status_codes === null) {
		$status_codes = array (
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		426 => 'Upgrade Required',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended'
		);
	}
	if ($status_codes[$statusCode] !== null) {
		$status_string = $statusCode . ' ' . $status_codes[$statusCode];
		header($_SERVER['SERVER_PROTOCOL'] . ' ' . $status_string, true, $statusCode);
	}
} 

function setSessionVariable($variable, $value)
{
	$_SESSION[$variable] = $value;
}

function create_guid()
{ 
	if( function_exists( 'com_create_guid' ) )
	{
		return trim( com_create_guid(), '{}' );
		}else{
		mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);
		$uuid = substr($charid, 0, 8).$hyphen
		.substr($charid, 8, 4).$hyphen
		.substr($charid,12, 4).$hyphen
		.substr($charid,16, 4).$hyphen
		.substr($charid,20,12);
		return $uuid;
	}
}

function in_array_r($needle, $haystack, $strict = false)
{
	if( $haystack == 0 ) return false;
	foreach ($haystack as $item) {
		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
			return true;
		}
	}
	return false;
}


?>