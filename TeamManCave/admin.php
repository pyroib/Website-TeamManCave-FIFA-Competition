<?PHP
	$db = new DATABASE();
	$db->connect();
	$teamHashToURL = teamHashToName($db);
	$note = '';
	
	$isAdmin = checkAdmin();
	
	if( !$isAdmin )
	{
		echo "no!";
		
	} else {
			
			$HTML = '<div class="colHolder">';
			
			$HTML .= '<div><a href="/?admin">admin home</a> | <a href="/?admin&amp;adduser">add a new user</a></div>';
			
			if( isset( $_REQUEST['adduser'] ) ) {
				if(	( isset( $_POST['adminGuid'] ) && $_POST['adminGuid'] != '' ) && 
						( isset( $_POST['adminUser'] ) && $_POST['adminUser'] != '' ) && 
						( isset( $_POST['newUser'] ) && $_POST['newUser'] != '' ) && 
						( isset( $_POST['newPass'] ) && $_POST['newPass'] != '' ) && 
						( isset( $_POST['userTeam1'] ) && $_POST['userTeam1'] != '' ) ){
						
						$createSQL = "INSERT INTO users SET 
															`user` = '" . $_POST['newUser'] . "', 
															`team1` = '" . $_POST['userTeam1'] . "'";
						
						if( isset( $_POST['userTeam2'] ) && $_POST['userTeam2'] != '' )  {
							$createSQL .= ", `team2` = '" . $_POST['userTeam2'] . "'";
						}
						
						if( isset( $_POST['userTeam3'] ) && $_POST['userTeam3'] != '' ) {
							$createSQL .= ", `team3` = '" . $_POST['userTeam3'] . "'";
						}
						
						$createSQL .= ", `pass` = '" . strtoupper( sha1( $_POST['newPass'] ) ) . "'";
						
						
						if( isset( $_POST['isAdmin'] ) && $_POST['isAdmin'] != '' ) {
							$createSQL .= ", `admin` = '" . $_POST['isAdmin'] . "'";
						}
						
						$db->query( $createSQL );
						$HTML .=	'<div class="msg">Created!</div>';
				}
				
				$HTML .= '<form method="post" action="/?admin&amp;adduser">';
				$HTML .= '<input type="hidden" value="' . $_SESSION['guid'] . '" name="adminGuid" id="adminGuid">';
				$HTML .= '<input type="hidden" value="' . $_SESSION['u'] . '" name="adminUser" id="adminUser">';
				$HTML .= '<div class="form-group">';
				
				$HTML .= '<label class="col-form-label" for="newUser">Username: (login)</label>';
				$HTML .= '<input class="form-control" type="text" placeholder="PSN Name" name="newUser" id="newUser">';
				
				$HTML .= '<hr>';
				
				$HTML .= '<label class="col-form-label" for="newPass">Password:</label>';
				$HTML .= '<input class="form-control" type="text" placeholder="Password For Site" name="newPass" id="newPass">';
				
				$HTML .= '<hr>';
				
				$teamListSQL = "SELECT * FROM teams";
				$teamListData = $db->queryFetchMulti( $teamListSQL );
				
				
				$HTML .= '<label class="col-form-label" for="userTeam1">Team 1:</label>';
				$HTML .= '<select name="userTeam1" id="userTeam1" >';
				foreach( $teamListData as $k => $teamData )
				{
					$HTML .= '<option value="' . $teamData['teamhash'] . '">' . $teamData['name'] . '</option>';
				}
				$HTML .= '</select>';
				$HTML .= '<hr>';
				
				$HTML .= '<label class="col-form-label" for="userTeam2">Team 2:</label>';
				$HTML .= '<select name="userTeam2" id="userTeam2" >';
				$HTML .= '<option value="">-</option>';
				foreach( $teamListData as $k => $teamData )
				{
					$HTML .= '<option value="' . $teamData['teamhash'] . '">' . $teamData['name'] . '</option>';
				}
				$HTML .= '</select>';
				$HTML .= '<hr>';
				
				$HTML .= '<label class="col-form-label" for="userTeam3">Team 3:</label>';
				$HTML .= '<select name="userTeam3" id="userTeam3" >';
				$HTML .= '<option value="">-</option>';
				foreach( $teamListData as $k => $teamData )
				{
					$HTML .= '<option value="' . $teamData['teamhash'] . '">' . $teamData['name'] . '</option>';
				}
				$HTML .= '</select>';
				$HTML .= '<hr>';
				
				
				
				
				$HTML .= '<label class="col-form-label" for="isAdmin">Admin:</label>';
				$HTML .= '<select name="isAdmin" id="isAdmin" >';
				$HTML .= '<option value="false">No</option>';
				$HTML .= '<option value="true">Yes</option>';
				$HTML .= '</select>';
				
				$HTML .= '</div>';
				$HTML .= '<button type="submit" class="btn btn-lg btn-success">Save</button>';
				$HTML .= '</form>';
			
			} else if( isset( $_REQUEST['team'] ) && $_REQUEST['team'] != '' ) {
			
				if(	( isset( $_POST['adminGuid'] ) && $_POST['adminGuid'] != '' ) && 
						( isset( $_POST['adminUser'] ) && $_POST['adminUser'] != '' ) && 
						( isset( $_POST['teamID'] ) && $_POST['teamID'] != '' ) && 
						( isset( $_POST['teamHash'] ) && $_POST['teamHash'] != '' ) && 
						( isset( $_POST['teamName'] ) && $_POST['teamName'] != '' ) ){
						
						$updateSQL = "UPDATE teams SET 
													`name` = '" . $_POST['teamName'] . "' 
													 WHERE teamhash = '" . $_POST['teamHash'] . "' AND 
													 id = '" . $_POST['teamID'] . "'";
						
						$db->query( $updateSQL );
						$HTML .=	'<div class="msg">Saved!</div>';
				}
				
				
				
				$teamDataSQL = "SELECT * FROM teams WHERE teamhash = '" . $_REQUEST['team']  . "'";
				$teamData = $db->queryFetch( $teamDataSQL );
				
				$HTML .= '<form method="post" action="/?admin&amp;team=' . $teamData['teamhash'] . '">';
				$HTML .= '<input type="hidden" value="' . $_SESSION['guid'] . '" name="adminGuid" id="adminGuid">';
				$HTML .= '<input type="hidden" value="' . $_SESSION['u'] . '" name="adminUser" id="adminUser">';
				$HTML .= '<input type="hidden" value="' . $teamData['id'] . '" name="teamID" id="teamID">';
				$HTML .= '<input type="hidden" value="' . $teamData['teamhash'] . '" name="teamHash" id="teamHash">';
				$HTML .= '<div class="form-group">';
				
				$HTML .= '<label class="col-form-label" for="teamName">Team Name:</label>';
				$HTML .= '<input class="form-control" type="text" value="'. $teamData['name'] . '" name="teamName" id="teamName">';
				
				
				$HTML .= '</div>';
				$HTML .= '<button type="submit" class="btn btn-lg btn-success">Save</button>';
				$HTML .= '</form>';

			} else if( isset( $_REQUEST['user'] ) && $_REQUEST['user'] != '' ) {
				
				if(	( isset( $_POST['adminGuid'] ) && $_POST['adminGuid'] != '' ) && 
						( isset( $_POST['adminUser'] ) && $_POST['adminUser'] != '' ) && 
						( isset( $_POST['userName'] ) && $_POST['userName'] != '' ) && 
						( isset( $_POST['userTeam1'] ) && $_POST['userTeam1'] != '' ) && 
						( isset( $_POST['isAdmin'] ) && $_POST['isAdmin'] != '' ) ){
						
						$updateSQL = "UPDATE users SET 
													`user` = '" . $_POST['userName'] . "', 
													`team1` = '" . $_POST['userTeam1'] . "', ";
													
						if( isset( $_POST['userTeam2'] ) && $_POST['userTeam2'] != '' )  {
							$updateSQL .= "`team2` = '" . $_POST['userTeam2'] . "', ";
						}
						if( isset( $_POST['userTeam3'] ) && $_POST['userTeam3'] != '' ) {
							$updateSQL .= "`team3` = '" . $_POST['userTeam3'] . "', ";
						}
						
						$updateSQL .= 	"`admin` = '" . $_POST['isAdmin'] . "' 
													 WHERE id = '" . preg_replace("/[^0-9,.]/", "", $_REQUEST['user'] ) . "'";
						
						$db->query( $updateSQL );
						
						$HTML .=	'<div class="msg">saved!</div>';
				}
	
	
				$user = $_REQUEST['user'];
				$userId = preg_replace("/[^0-9,.]/", "", $user);
				
				$userListSQL = "SELECT * FROM users WHERE id = '" . $userId . "'";
				$userListData = $db->queryFetch( $userListSQL );
				
				$HTML .= '<form method="post" action="/?admin&amp;user=' . $userId . '">';
				$HTML .= '<input type="hidden" value="' . $_SESSION['guid'] . '" name="adminGuid" id="adminGuid">';
				$HTML .= '<input type="hidden" value="' . $_SESSION['u'] . '" name="adminUser" id="adminUser">';
				$HTML .= '<div class="form-group">';
				
				$HTML .= '<label class="col-form-label" for="userName">Username: (login)</label>';
				$HTML .= '<input class="form-control" type="text" value="'. $userListData['user'] . '" name="userName" id="userName">';
				
				$HTML .= '<hr>';
				

				$teamListSQL = "SELECT * FROM teams";
				$teamListData = $db->queryFetchMulti( $teamListSQL );
				
				
				$HTML .= '<label class="col-form-label" for="userTeam1">Team 1:</label>';
				$HTML .= '<select name="userTeam1" id="userTeam1" >';
				foreach( $teamListData as $k => $teamData )
				{
					$selected = ( $userListData['team1'] == $teamData['teamhash'] ? 'selected' : '' );
					$HTML .= '<option ' . $selected . ' value="' . $teamData['teamhash'] . '">' . $teamData['name'] . '</option>';
				}
				$HTML .= '</select>';
				$HTML .= '<hr>';
				
				$HTML .= '<label class="col-form-label" for="userTeam2">Team 2:</label>';
				$HTML .= '<select name="userTeam2" id="userTeam2" >';
				$HTML .= '<option value="">-</option>';
				foreach( $teamListData as $k => $teamData )
				{
					$selected = ( $userListData['team2'] == $teamData['teamhash'] ? 'selected' : '' );
					$HTML .= '<option ' . $selected . ' value="' . $teamData['teamhash'] . '">' . $teamData['name'] . '</option>';
				}
				$HTML .= '</select>';
				$HTML .= '<hr>';
				
				$HTML .= '<label class="col-form-label" for="userTeam3">Team 3:</label>';
				$HTML .= '<select name="userTeam3" id="userTeam3" >';
				$HTML .= '<option value="">-</option>';
				foreach( $teamListData as $k => $teamData )
				{
					$selected = ( $userListData['team3'] == $teamData['teamhash'] ? 'selected' : '' );
					$HTML .= '<option ' . $selected . ' value="' . $teamData['teamhash'] . '">' . $teamData['name'] . '</option>';
				}
				$HTML .= '</select>';
				$HTML .= '<hr>';
				
				$HTML .= '<label class="col-form-label" for="isAdmin">Admin:</label>';
				$HTML .= '<select name="isAdmin" id="isAdmin" >';
				$HTML .= '<option value="false">No</option>';
				$HTML .= '<option value="true" ' . ( $userListData['admin'] == 'true' ? 'selected' : '' ) . ' >Yes</option>';
				$HTML .= '</select>';
				
				$HTML .= '</div>';
				$HTML .= '<button type="submit" class="btn btn-lg btn-success">Save</button>';
				$HTML .= '</form>';
				
				
			} else {
			
				$userListSQL = "SELECT * FROM users";
				$userListData = $db->queryFetchMulti( $userListSQL );
				
				$userList = '<div class="userList">';
				$userList .= '<ul>';
				foreach( $userListData as $k => $userDetails )
				{
					$userList .= '<li><a href="/?admin&amp;user=' . $userDetails['id'] . '">' . $userDetails['user'] . '</a>' . ( $userDetails['admin'] == 'true' ? '*' : '' ). '</li>';
				}
				$userList .= '</ul>';
				$userList .= '* = admin';
				$userList .= '</div>';
				
				$HTML .= $userList;
				
				$teamListSQL = "SELECT * FROM teams";
				$teamListData = $db->queryFetchMulti( $teamListSQL );
				$teamList = '<div class="teamList">';
				$teamList .= '<ul>';
				foreach( $teamListData as $k => $teamDetails )
				{
					$teamList .= '<li><a href="/?admin&amp;team=' . $teamDetails['teamhash'] . '">' . $teamDetails['name'] . '</a></li>';
				}
				$teamList .= '</ul>';
				$teamList .= '</div>';
				
				$HTML .= $teamList;
				
			}
			
			$HTML .= '</div>';
			
			echo $HTML;
	}
?>


