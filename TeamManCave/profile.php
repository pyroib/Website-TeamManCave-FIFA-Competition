<?PHP
	$db = new DATABASE();
	$db->connect();
	$teamHashToURL = teamHashToName($db);
	$note = '';
	

	if( isset( $_POST['postPass'] ) && $_POST['postPass'] != '' )
	{
		$SQL = "SELECT * FROM users WHERE `user`= '" . $_POST['postUser'] . "'";
		$userDetails = $db->queryFetch( $SQL );
		
		if( $userDetails == 0 )
		{
			$note = '<p>invalid login</p>';
		} else if ( $_SESSION['u'] == $_POST['postUser'] && $_POST['postUser'] == 	$userDetails['user'] ) {
			$db->query( "update users set pass = '" . strtoupper( sha1( $_POST['postPass'] ) ) . "' WHERE id = '" . $userDetails['id']. "' AND user = '" . $userDetails['user'] . "'" );
			$note = '<p>Success!. You will need to login again.</p>';
		}
	}
	$HTML = '<div style="text-align:center;">';
	$HTML .= $note . '<form method="post" action="/?profile">';
	$HTML .= '<input type="hidden" value="' . $_SESSION['guid'] . '" name="guid" id="guid">';
	$HTML .= '<input type="hidden" value="' . $_SESSION['u'] . '" name="postUser" id="postUser">';
	$HTML .= '<div class="form-group">';
	$HTML .= '<label class="col-form-label" for="inputDefault">New Password:</label>';
	$HTML .= '<input class="form-control" type="password" value="" name="postPass" id="postPass" placeholder="Password">';
	$HTML .= '</div>';
	$HTML .= '<button type="submit" class="btn btn-lg btn-success">Submit</button>';
	$HTML .= '</form>';
	$HTML .= '</div>';
	echo $HTML;
?>


