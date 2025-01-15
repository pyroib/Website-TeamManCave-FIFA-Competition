<?PHP
$gameData = json_decode( file_get_contents('champions.json'));

$admin = ( isset($_GET['pw']) && $_GET['pw'] = 'dave69dave69' ? true : false );

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<style>
    * { text-align:center; }
    table { width:100%;}

    body {
        font-family: 'lato', sans-serif;
        color: #333;
        font-size:1em;

    }

    td { padding:20px 0px; border:1px solid #333; width:25%; }
    th {background-color:#333; color:#fff; padding:10px 0px; text-align:center; }

    input {padding:10px 30px; border:1px solid #333; background-color:#FFF; }

    .container {
        margin-left: auto;
        margin-right: auto;
        padding-left: 10px;
        padding-right: 10px;
    }

    .vs{
        display:block;
        LINE-HEIGHT: 100;
        overflow: hidden;
        margin:10px auto;
        background-image:url("images/vs-icon-png-6.png");
        background-position: center center;
        background-size: 100%;
        background-position: center center;
        background-repeat: no-repeat;
        height:30px;
        width:30px;

    }

    .bs-example{
        margin: 20px;
    }
</style>

</head>
<body>


<?PHP if ( $admin ) {

    if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {

        $JSON   = array();

        $JSON['final']['away'] = $_POST['final_home'];
        $JSON['final']['home'] = $_POST['final_away'];




        $JSON['semi_one']['away'] = $_POST['semi_one_home'];
        $JSON['semi_two']['away'] = $_POST['semi_two_home'];
        $JSON['semi_one']['home'] = $_POST['semi_one_away'];
        $JSON['semi_two']['home'] = $_POST['semi_two_away'];





        $JSON['quarter_one']['home'] = $_POST['quarter_one_home'];
        $JSON['quarter_two']['home'] = $_POST['quarter_two_home'];
        $JSON['quarter_three']['home'] = $_POST['quarter_three_home'];
        $JSON['quarter_four']['home'] = $_POST['quarter_four_home'];
        $JSON['quarter_one']['away'] = $_POST['quarter_one_away'];
        $JSON['quarter_two']['away'] = $_POST['quarter_two_away'];
        $JSON['quarter_three']['away'] = $_POST['quarter_three_away'];
        $JSON['quarter_four']['away'] = $_POST['quarter_four_away'];






        $JSON['sixteen_one']['home'] = $_POST['sixteen_one_home'];
        $JSON['sixteen_two']['home'] = $_POST['sixteen_two_home'];
        $JSON['sixteen_three']['home'] = $_POST['sixteen_three_home'];
        $JSON['sixteen_four']['home'] = $_POST['sixteen_four_home'];
        $JSON['sixteen_five']['home'] = $_POST['sixteen_five_home'];
        $JSON['sixteen_six']['home'] = $_POST['sixteen_six_home'];
        $JSON['sixteen_seven']['home'] = $_POST['sixteen_seven_home'];
        $JSON['sixteen_eight']['home'] = $_POST['sixteen_eight_home'];
        $JSON['sixteen_one']['away'] = $_POST['sixteen_one_away'];
        $JSON['sixteen_two']['away'] = $_POST['sixteen_two_away'];
        $JSON['sixteen_three']['away'] = $_POST['sixteen_three_away'];
        $JSON['sixteen_four']['away'] = $_POST['sixteen_four_away'];
        $JSON['sixteen_five']['away'] = $_POST['sixteen_five_away'];
        $JSON['sixteen_six']['away'] = $_POST['sixteen_six_away'];
        $JSON['sixteen_seven']['away'] = $_POST['sixteen_seven_away'];
        $JSON['sixteen_eight']['away'] = $_POST['sixteen_eight_away'];

        file_put_contents('champions_log/'.time().'.txt', json_encode($JSON, JSON_PRETTY_PRINT));
        file_put_contents('champions.json', json_encode($JSON, JSON_PRETTY_PRINT));

        $gameData = json_decode( file_get_contents('champions.json'));

        echo '<div class="bs-example"><div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> Your save was  successful.</div></div>';
    }

   ?>

<div class="container">

    <form method="post" action="#">

        <table>
            <tr>
                <th>Final 16</th>
                <th>Quarter Finals</th>
                <th>Semi Finals</th>
                <th>Final</th>
            </tr>
            <tr>
                <td><input type="text" name="sixteen_one_home" value="<?=$gameData->sixteen_one->home; ?>"><span class="vs">Vs.</span><input type="text" name="sixteen_one_away" value="<?=$gameData->sixteen_one->away; ?>"></td>
                <td rowspan=2><input type="text" name="quarter_one_home" value="<?=$gameData->quarter_one->home; ?>"><span class="vs">Vs.</span><input type="text" name="quarter_one_away" value="<?=$gameData->quarter_one->away; ?>"></td>
                <td rowspan=4><input type="text" name="semi_one_home" value="<?=$gameData->semi_one->home; ?>"><span class="vs">Vs.</span><input type="text" name="semi_one_away" value="<?=$gameData->semi_one->away; ?>"></td>
                <td rowspan=8><input type="text" name="final_home" value="<?=$gameData->final->home; ?>"><span class="vs">Vs.</span><input type="text" name="final_away" value="<?=$gameData->final->away; ?>"></td>
            </tr>
            <tr>
                <td><input type="text" name="sixteen_two_home" value="<?=$gameData->sixteen_two->home; ?>"><span class="vs">Vs.</span><input type="text" name="sixteen_two_away" value="<?=$gameData->sixteen_two->away; ?>"></td>
            </tr>
            <tr>
                <td><input type="text" name="sixteen_three_home" value="<?=$gameData->sixteen_three->home; ?>"><span class="vs">Vs.</span><input type="text" name="sixteen_three_away" value="<?=$gameData->sixteen_three->away; ?>"></td>
                <td rowspan=2><input type="text" name="quarter_two_home" value="<?=$gameData->quarter_two->home; ?>"><span class="vs">Vs.</span><input type="text" name="quarter_two_away" value="<?=$gameData->quarter_two->away; ?>"></td>
            </tr>
            <tr>
                <td><input type="text" name="sixteen_four_home" value="<?=$gameData->sixteen_four->home; ?>"><span class="vs">Vs.</span><input type="text" name="sixteen_four_away" value="<?=$gameData->sixteen_four->away; ?>"></td>
            </tr>
            <tr>
                <td><input type="text" name="sixteen_five_home" value="<?=$gameData->sixteen_five->home; ?>"><span class="vs">Vs.</span><input type="text" name="sixteen_five_away" value="<?=$gameData->sixteen_five->away; ?>"></td>
                <td rowspan=2><input type="text" name="quarter_three_home" value="<?=$gameData->quarter_three->home; ?>"><span class="vs">Vs.</span><input type="text" name="quarter_three_away" value="<?=$gameData->quarter_three->away; ?>"></td>
                <td rowspan=4><input type="text" name="semi_two_home" value="<?=$gameData->semi_two->home; ?>"><span class="vs">Vs.</span><input type="text" name="semi_two_away" value="<?=$gameData->semi_two->away; ?>"></td>
            </tr>
            <tr>
                <td><input type="text" name="sixteen_six_home" value="<?=$gameData->sixteen_six->home; ?>"><span class="vs">Vs.</span><input type="text" name="sixteen_six_away" value="<?=$gameData->sixteen_six->away; ?>"></td>
            </tr>
            <tr>
                <td><input type="text" name="sixteen_seven_home" value="<?=$gameData->sixteen_seven->home; ?>"><span class="vs">Vs.</span><input type="text" name="sixteen_seven_away" value="<?=$gameData->sixteen_seven->away; ?>"></td>
                <td rowspan=2><input type="text" name="quarter_four_home" value="<?=$gameData->quarter_four->home; ?>"><span class="vs">Vs.</span><input type="text" name="quarter_four_away" value="<?=$gameData->quarter_four->away; ?>"></td>
            </tr>
            <tr>
                <td><input type="text" name="sixteen_eight_home" value="<?=$gameData->sixteen_eight->home; ?>"><span class="vs">Vs.</span><input type="text" name="sixteen_eight_away" value="<?=$gameData->sixteen_eight->away; ?>"></td>
            </tr>
            <tr>
                <td colspan=4><input type="submit" value="submit" name="submit" ></td>
            </tr>
        </table>
    </form>


<?PHP
    } else {
?>

    <div class="container">
    <table>
        <tr>
            <th>Final 16</th>
            <th>Quarter Finals</th>
            <th>Semi Finals</th>
            <th>Final</th>
        </tr>
        <tr>
            <td><?=$gameData->sixteen_one->home; ?><span class="vs">Vs.</span><?=$gameData->sixteen_one->away; ?></td>
            <td rowspan=2><?=$gameData->quarter_one->home; ?><span class="vs">Vs.</span><?=$gameData->quarter_one->away; ?></td>
            <td rowspan=4><?=$gameData->semi_one->home; ?><span class="vs">Vs.</span><?=$gameData->semi_one->away; ?></td>
            <td rowspan=8><?=$gameData->final->home; ?><span class="vs">Vs.</span><?=$gameData->final->away; ?></td>
        </tr>
        <tr>
            <td><?=$gameData->sixteen_two->home; ?><span class="vs">Vs.</span><?=$gameData->sixteen_two->away; ?></td>
        </tr>
        <tr>
            <td><?=$gameData->sixteen_three->home; ?><span class="vs">Vs.</span><?=$gameData->sixteen_three->away; ?></td>
            <td rowspan=2><?=$gameData->quarter_two->home; ?><span class="vs">Vs.</span><?=$gameData->quarter_two->away; ?></td>
        </tr>
        <tr>
            <td><?=$gameData->sixteen_four->home; ?><span class="vs">Vs.</span><?=$gameData->sixteen_four->away; ?></td>
        </tr>
        <tr>
            <td><?=$gameData->sixteen_five->home; ?><span class="vs">Vs.</span><?=$gameData->sixteen_five->away; ?></td>
            <td rowspan=2><?=$gameData->quarter_three->home; ?><span class="vs">Vs.</span><?=$gameData->quarter_three->away; ?></td>
            <td rowspan=4><?=$gameData->quarter_four->home; ?><span class="vs">Vs.</span><?=$gameData->quarter_four->away; ?></td>
        </tr>
        <tr>
            <td><?=$gameData->sixteen_six->home; ?><span class="vs">Vs.</span><?=$gameData->sixteen_six->away; ?></td>
        </tr>
        <tr>
            <td><?=$gameData->sixteen_seven->home; ?><span class="vs">Vs.</span><?=$gameData->sixteen_seven->away; ?></td>
            <td rowspan=2><?=$gameData->semi_two->home; ?><span class="vs">Vs.</span><?=$gameData->semi_two->away; ?></td>
        </tr>
        <tr>
            <td><?=$gameData->sixteen_eight->home; ?><span class="vs">Vs.</span><?=$gameData->sixteen_eight->away; ?></td>
        <tr>
    </table>

<?PHP } ?>

        </div>

    </body>
</html>