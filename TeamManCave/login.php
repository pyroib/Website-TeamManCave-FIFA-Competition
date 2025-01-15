<?PHP
	$db = new DATABASE();
	$db->connect();
	$teamHashToURL = teamHashToName($db);
	$note = '';
	
	if( isset( $_POST['postUser'] ) && isset( $_POST['postPass'] ) )
	{
		$SQL = "SELECT * FROM users WHERE `user`= '" . $_POST['postUser'] . "'";
		$userDetails = $db->queryFetch( $SQL );


		if( $userDetails == 0 )
		{
			$note = '<p>invalid login</p>';
		} else {
			if ( strtoupper( sha1( $_POST['postPass'] ) ) == $userDetails['pass'] )
			{
				$_SESSION['u'] = $_POST['postUser'];
				$_SESSION['q'] = sha1( $_POST['postUser'] . $userDetails['id'] );
				$_SESSION['w'] = sha1( $userDetails['pass'] . $userDetails['id'] );
				header( 'location: /');
			}
		}
	}

	$HTML = '<div style="text-align:center;">';
	$HTML .= $note . '<form method="post" action="/?login">';
	$HTML .= '<div class="form-group">';
	$HTML .= '<input type="hidden" value="' . $_SESSION['guid'] . '" name="guid" id="guid">';
	$HTML .= '<label class="col-form-label" for="inputDefault">User Name:</label>';
	$HTML .= '<input class="form-control" type="text" value="" name="postUser" id="postUser" placeholder="User Name">';
	$HTML .= '</div>';
	$HTML .= '<div class="form-group">';
	$HTML .= '<label class="col-form-label" for="inputDefault">Password:</label>';
	$HTML .= '<input class="form-control" type="password" value="" name="postPass" id="postPass" placeholder="Password">';
	$HTML .= '</div>';
	$HTML .= '<button type="submit" class="btn btn-lg btn-success">Submit</button>';
	$HTML .= '</form>';
	$HTML .= '</div>';
	echo $HTML;
?>


