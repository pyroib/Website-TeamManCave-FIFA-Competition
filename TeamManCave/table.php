<?PHP
	$db = new DATABASE();
	$db->connect();
	$teamHashToURL = teamHashToName($db);

	$SQL = "SELECT * FROM fixtures WHERE `date`< cast(DATE_ADD(CURDATE(), INTERVAL 14 DAY) as datetime) AND submitted != '' ORDER BY date";
	$fixtures = $db->queryFetchMulti( $SQL );
	
			/*
	if( $fixtures != 0 )
	{
		$sortedData = arrangeTableData( $fixtures, $teamHashToURL );
		
		$tableData = $sortedData['tableData'];
		
		$tableOrder = array( "name","played","win","draw","lose","gf","ga","gd","points" );

		$HTML = '';
		$HTML .= '<section>';
		$HTML .= '<table border="0" cellpadding="0" cellspacing="0" id="sportsTable" class="table table-hover competitionTable">';
		$HTML .= '<thead>';
		$HTML .= '<tr>';
		$HTML .= '<th scope="col" class="counter"></th>';
		foreach( $tableOrder as $td )
		{
			$HTML .= '<th scope="col" class="'.$td.' ">'. ucfirst( $td ) .'</th>';
		}
		$HTML .= '</tr>';
		$HTML .= '</thead>';
		$HTML .= '<tbody>';
		$count = 1;
		foreach( $tableData as $k => $team )
		{
			if( $team['teamhash'] != '' )
			{
				$HTML .= '<tr class="table-light tr_pos_' . $count. '">';
				$HTML .= '<td scope="row" class="counter ">' . $count . '</td>';
				$count++;
				foreach( $tableOrder as $td )
				{
					if( $td == 'name' )
					{
						$HTML .= '<td class="' . $td . '">' . ucfirst( $team['name'] ) . '</td>';
					} else {
						$HTML .= '<td class="' . $td . '">' . $team[$td] . '</td>';
					}
				}
				$HTML .= '</tr>';
			}
		}
		$HTML .= '</tbody>';
		$HTML .= '</table>';
		$HTML .= '</section>';
	} else {
		$HTML = '<div style="text-align:center;"><h2>Starting Soon!</h2></div>';
	}
	echo $HTML;
	
	/*
	
	NEW
	*/
		
	if( $fixtures != 0 )
	{
		$sortedData = arrangeTableData( $fixtures, $teamHashToURL );
		
		$tableData = $sortedData['tableData'];
		
		$tableOrder = array( "name","played","win","draw","lose","gf","ga","gd","points" );
		
		$HTML = '';
		$HTML .= '<section>';
		$HTML .= '<div class="table table-hover competitionTable">';
			$HTML .= '<div class="thead">';
				$HTML .= '<div class="tableRow">';
					$HTML .= '<div class="counter tableCell"></div>';
					foreach( $tableOrder as $td ){
						if( $td != 'name' ) {
							$HTML .= '<div class="tableCell '.$td.' "><span class="largeScreenText">'. ucfirst( $td ) .'</span><span class="smallScreenText">'. substr( ucfirst( $td ),0,1) .'</span></div>';
						} else {
							$HTML .= '<div class="tableCell '.$td.' ">'. ucfirst( $td ) .'</span></div>';
						}
					}
				$HTML .= '</div>';//tableRow
			$HTML .= '</div>'; // thead
			$HTML .= '<div class="tbody">';

				$count = 1;
				foreach( $tableData as $k => $team )
				{
					if( $team['teamhash'] != '' )
					{
						$HTML .= '<div class="tableRow table-light tr_pos_' . $count. '">';
							
							$HTML .= '<div class="counter tableCell">' . $count . '</div>';
							$count++;
							foreach( $tableOrder as $td ) {
								if( $td == 'name' ) {
									$HTML .= '<div class="tableCell ' . $td . '" >' . ucfirst( $team['name'] ) . '</div>';
								} else {
									$HTML .= '<div class="tableCell ' . $td . '" >' . $team[$td] . '</div>';
								}
							}
							
						$HTML .= '</div>'; // tableRow
							
					}
				}
			$HTML .= '</div>'; // tbody
		$HTML .= '</div>'; // table
		$HTML .= '</section>';
	} else {
		$HTML = '<div style="text-align:center;"><h2>Starting Soon!</h2></div>';
	}
	
	
	echo $HTML;
	
	
?>