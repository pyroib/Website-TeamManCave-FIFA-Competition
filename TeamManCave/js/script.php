<?PHP
	error_reporting( E_ALL );
	chdir( __DIR__ );
	
	( file_exists( '../functions.php' ) ? include_once('../functions.php') : die( 'functions not found' ) );
	( file_exists( '../db-connect/class_database.php' ) ? include_once('../db-connect/class_database.php') : throw500( 'class_database' ) );
	
	date_default_timezone_set ( 'Australia/Sydney' );
	ini_set( 'display_errors', 1 );
	cleanPostData();

	if( !session_id() ) session_start();
	
	header("Content-Type: application/javascript");
	
	$db = new DATABASE();
	$db->connect();
	
	echo ( isset( $_SESSION['u'] ) ? "var u=\"" . $_SESSION['u'] . "\";\r\n" : '' );
	echo ( isset( $_SESSION['q'] ) ? "var q=\"" . $_SESSION['q'] . "\";\r\n" : '' );
	echo ( isset( $_SESSION['w'] ) ? "var w=\"" . $_SESSION['w'] . "\";\r\n" : '' );
	
?>
$(document).ready( function(){
	$(document).on('change','.scoreInput', function(){
		enableSave(this.parentNode.id);
	});
});

function enableSave(i)
{
	$('#'+i+' button').each( function () {
		$(this).removeClass( 'disabled' );
		$(this).addClass( 'enabled' );
	});
}


function simGame(h_a,game,team,usr){

	$.post("/?simGame",{u,q,w,game,team,usr},function(d){
		if (d.r == 'true') {
			setTimeout( function() {
				$('#sim'+h_a+'_'+game).addClass( h_a+'teamsim' );
				$('#sim'+h_a+'_'+game).removeClass( h_a+'Sim' );
			}, 800);
		} else if (d.r == 'false') {
			setTimeout( function() {
				$('#sim'+h_a+'_'+game).removeClass( h_a+'teamsim' );
				$('#sim'+h_a+'_'+game).addClass( h_a+'Sim' );
			}, 800);
			
		} else {
			alert('Something went wrong. This didnt save. Please reload the page.');
		}
	});
}

function submitScores(i)
{
	addSaving('gameContainer_'+i);
	var g = i;
	var h = $('#game_h_'+i).val();
	var a = $('#game_a_'+i).val();
	$.post("/?saveResult", { u, q, w, g, h, a }, function(d){
		if (d.r == '1') {
			setTimeout( function() {
				removeSaving('gameContainer_'+i+'_saving'); 
				saveConfirmed(i); 
			}, 800);
		} else {
			removeSaving('gameContainer_'+i+'_saving');
			saveFailed(i);
			alert('Something went wrong. This didnt save. Please reload the page.');
		}
	});
}

function saveConfirmed(i){
	$('#gameContainer_'+i+' input').each( function () {
		this.classList.add('gameScoreSaved');
		$('#gameContainer_'+i+' button').each( function () {
			$(this).addClass( 'disabled' );
			$(this).removeClass( 'enabled' );
		});
	
	});
}

function saveFailed(i){
	$('#gameContainer_'+i+' input').each( function () {
		this.classList.add('gameScoreFailed');
	});
}

function addSaving(g) {
	var d = document.createElement("div");
	d.id = g+'_saving';
	d.classList.add('savingOverlay');
	d.innerHTML = '<img src="/images/spinner.gif" />';
	document.getElementById(g).appendChild(d);
}

function removeSaving(i) {
	var s = document.getElementById(i);
	s.parentNode.removeChild(s);
}
