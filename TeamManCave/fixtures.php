<?PHP

    date_default_timezone_set ( 'Australia/Sydney' );

	$db = new DATABASE();
	$db->connect();
	$teamHashToURL = teamHashToName($db);

	$start = 1533686400;
	$day = 86400;
	$week = ( $day * 7 );
	

    $HTML = '<input type="hidden" value="' . ( isset( $_SESSION['u'] ) ? $_SESSION['u'] : '' ) . '" name="u" id="u">';
    $HTML .= '<input type="hidden" value="' . ( isset( $_SESSION['q'] ) ? $_SESSION['q'] : '' ) . '" name="q" id="q">';
    $HTML .= '<input type="hidden" value="' . ( isset( $_SESSION['w'] ) ? $_SESSION['w'] : '' ) . '" name="w" id="w">';

    if( isset( $_SESSION['u'] ) ){
        $HTML .= '<div class="psnlist">';
        $HTML .= '<p>PSN => Name</p>';
        $HTML .= '<ul>';
        $HTML .= '<li>pyroib => Ian Blott</li>';
        $HTML .= '<li>mancavedave69 => David McKinder &amp; Curtis McKinder</li>';
        $HTML .= '<li>souz15275 => Daniel Robert</li>';
        $HTML .= '<li>kirby375 => Curtis Kirby</li>';
        $HTML .= '<li>bunnyk => Ky Easterbrook</li>';
        $HTML .= '<li>Jimmy1992thfc => Jimmy Pavlich</li>';
        $HTML .= '<li>mick22jb => Michael Barényi</li>';
        $HTML .= '<li>BRYCECAN => ManCave Jordie</li>';
        $HTML .= '<li>ThekiidSYD => Brendan Koomson</li>';



        $HTML .= '</ul>';
        $HTML .= '</div>';
    }

    $HTML .= '<a href="/?fixtures#thisweek" alt="jump to this week">Jump To This Week</a>';


    $HTML .= '<div class="table table-hover competitionTable">';

    $HTML .= '<div class="tableRow thead">';
    $HTML .= '<div class="tableCell relaseDate ">FIFA 18 Games Until September 28</div>';
    $HTML .= '</div>';

    $usersTeams = array();
    $checkedUser = false;

    if( isset( $_SESSION['u'] ) ){
        $checkedUser = true;
        $usersTeamsSQL = "SELECT team1, team2, team3 FROM users WHERE user = '" . $_SESSION['u'] . "'";
        $usersTeams = $db->queryFetch( $usersTeamsSQL );
    }

    $teamsArray = array();
    if( count( $usersTeams ) > 0 ){
        $teamsArray = array( $usersTeams['team1'], $usersTeams['team2'], $usersTeams['team3'] );
    }

    $x = 0;
    $sect_id = '';

    while( $x <=39 ) {

        $weekStartDate = ($start + ($week * $x ) );
        $weekEndDate = $weekStartDate + $week;

        if( $weekStartDate <= time() && $weekEndDate >= time() ) {
            $sect_id =  "thisweek";
        }

        $SQL = "SELECT * FROM fixtures WHERE date >= " . $weekStartDate . " AND date <= " . $weekEndDate . " ORDER BY date";
        $fixtures = $db->queryFetchMulti( $SQL );


        if( $weekStartDate == 1538524800 && $weekEndDate == 1539129600 ) {
            $HTML .= '<div class="tableRow thead">';
            $HTML .= '<div class="tableCell relaseDate ">FIFA 19</div>';
            $HTML .= '</div>';
        }

        $HTML .= '<div class="tableCell dateTitle ">' . date( 'M d', $weekStartDate ) . " - " . date( 'M d', $weekEndDate - $day ) . "</div>";

        if( ( $weekStartDate == 1550930400 && $weekEndDate == 1551535200 ) ||
            ( $weekStartDate == 1545400800 && $weekEndDate == 1546005600 ) ||
            ( $weekStartDate == 1548856800 && $weekEndDate == 1549461600 ) ) {

            $HTML .= '<div class="tableRow">';
            $HTML .= '<div class="tableCell doubleGameWeek ">Double Game Week!</div>';
            $HTML .= '</div>';
        }

    //    $HTML .= '<div class="">'.$weekStartDate.' -- '.$weekEndDate.'</div>';

        $HTML .= '<div class="tableRow weekDate" id="'.$sect_id.'">';

        if( $fixtures != 0 ) {
            foreach ($fixtures as $k => $detail) {

                $HTML .= '<div class="tableRow">';



                if( $checkedUser ) {
                    $meClass = '';
                    if ((in_array($detail['hometeam'], $teamsArray) || in_array($detail['awayteam'], $teamsArray) && $detail['date'] > time())) {
                        $meClass = 'me';
                    }
                    $HTML .= '<div class="tableCell smlName hometeam ' . $meClass . ' game_' . $detail['gamehash']  . '" id="hometeam_' . $detail['gamehash'] . '">';
                } else {
                    $HTML .= '<div class="tableCell lrgName hometeam game_' . $detail['gamehash'] . '" id="hometeam_' . $detail['gamehash'] . '">';
                }


                $HTML .= '<!--'.$detail['date'] . '-->';
/*
                $HTML .= '<div class="tableRow">';
                 $HTML .=  gmdate( 'M d Y, H:i:s', $detail['date'] );
                $HTML .= '</div>';
*/
                $HTML .= ($detail['hometeamscore']>$detail['awayteamscore']?'<strong>':'');
                $HTML .= $teamHashToURL[$detail['hometeam']] ;
                $HTML .= ($detail['hometeamscore']>$detail['awayteamscore']?'</strong>':'');


                $HTML .= '<span class="teamColors team_' . $detail['hometeam'] . '"></span>';


                $HTML .= '</div>';
/*
 *
 * 1543971600
 *
 *
                1546387200
                1546600000
                1546992000

1546354800 -> 1546500000


*/
                if( $checkedUser ){
                    $meClass = '';
                    if ((in_array($detail['hometeam'], $teamsArray) || in_array($detail['awayteam'], $teamsArray) && $detail['date'] > time())) {
                        $meClass = 'me';
                    }
                    if( $weekEndDate > time() || $checkedAdmin ) {


                        $HTML .= '<div class=" tableCell ' . $meClass .' lgScores" id="gameTD_' . $detail['gamehash'] . '">';
                        $HTML .= '<div id="gameContainer_' . $detail['gamehash'] . '">';
                        $HTML .= '<input type="hidden" value="' . $detail['gamehash'] . '" name="g" id="g">';


                        if ((in_array($detail['hometeam'], $teamsArray) && $detail['date'] > time()) || $checkedAdmin) {
                            $class = ($detail['hometeamsim'] == 'true' ? "hometeamsim" : "homeSim");
                            $HTML .= '<span id="simhome_' . $detail['gamehash'] . '" class="' . $class . '" onclick="javascript:simGame(\'home\', \'' . $detail['gamehash'] . '\',\'' . $detail['hometeam'] . '\',\'' . $_SESSION['u'] . '\');"></span>';
                        } else if ($detail['hometeamsim'] == 'true') {
                            $HTML .= '<span class="hometeamsim"></span>';
                        } else {
                            $HTML .= '<span class="nosim"></span>';
                        }


                        $HTML .= '<input type="number" class="scoreInput" min="0" value="' . $detail['hometeamscore'] . '" name="game_h_' . $detail['gamehash'] . '" id="game_h_' . $detail['gamehash'] . '" placeholder="0">';
                        $HTML .= '-';
                        $HTML .= '<input type="number" class="scoreInput" min="0" value="' . $detail['awayteamscore'] . '" name="game_a_' . $detail['gamehash'] . '" id="game_a_' . $detail['gamehash'] . '" placeholder="0">';

                        $HTML .= '<button type="submit" class="disabled" class="btn btn-lg btn-success" onclick=javascript:submitScores(\'' . $detail['gamehash'] . '\');>Save</button>';


                        if ((in_array($detail['awayteam'], $teamsArray) && $detail['date'] > time()) || $checkedAdmin) {
                            $class = ($detail['awayteamsim'] == 'true' ? "awayteamsim" : "awaySim");
                            $HTML .= '<span  id="simaway_' . $detail['gamehash'] . '" class="' . $class . '" onclick="javascript:simGame(\'away\', \'' . $detail['gamehash'] . '\',\'' . $detail['awayteam'] . '\',\'' . $_SESSION['u'] . '\');"></span>';
                        } else if ($detail['awayteamsim'] == 'true') {
                            $HTML .= '<span class="awayteamsim"></span>';
                        } else {
                            $HTML .= '<span class="nosim"></span>';
                        }


                        $HTML .= '</div>'; //gameContainer_


                        if ($detail['submitted'] != '') {
                            $HTML .= '<div class="submitted_details">Posted by ' . $detail['submitted'] . '</div>';
                        }

                        $HTML .= '</div>';

                    } else {
                        $HTML .= '<div class="tableCell lgScores">' . $detail['hometeamscore'] . '-' . $detail['awayteamscore'] . '</div>';
                    }
                } else {
                    $HTML .= '<div class="tableCell smScores">-</div>';
                }




                if( $checkedUser ) {
                    if ((in_array($detail['hometeam'], $teamsArray) || in_array($detail['awayteam'], $teamsArray) && $detail['date'] > time())) {
                        $meClass = 'me';
                    }
                    $HTML .= '<div class="tableCell smlName awayteam ' . $meClass . ' game_' . $detail['gamehash'] . '" id="awayteam_' . $detail['gamehash'] . '">';
                } else {
                    $HTML .= '<div class="tableCell lrgName awayteam game_' . $detail['gamehash'] . '" id="awayteam_' . $detail['gamehash'] . '">';
                }

                $HTML .= '<span class="teamColors  team_' . $detail['awayteam'] . '"></span>';
/*
                $HTML .= '<div class="tableRow">';
                $HTML .=  $detail['id'];
                $HTML .= '</div>';
*/

                $HTML .= ($detail['hometeamscore']<$detail['awayteamscore']?'<strong>':'');
                $HTML .= $teamHashToURL[$detail['awayteam']] ;
                $HTML .= ($detail['hometeamscore']<$detail['awayteamscore']?'</strong>':'');
                $HTML .= '</div>';

                $HTML .= '</div>';
            }



            $HTML .= '</div>';
        }




        $x++;
    }

    $HTML .= '</div>'; // table

	echo $HTML;
?>