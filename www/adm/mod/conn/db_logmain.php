<?
session_start();

$logged_in = false;
$auth_in = false;
if(isset($_SESSION['login']))
{
$logged_in = true;
}

if(isset($_COOKIE['auth_key']))
{
$auth_in = true;
}
//if(count($_GET)>0) {header('Location:index.php'); exit();}

if ( $logged_in &&  $auth_in ){ //session_id()
			$perem2="КУка:".$_COOKIE['auth_key']." ".$_COOKIE['user']; include('mod/main.php') ;
			//debug_to_console( "Test" );
		//if ( $auth_in ){$perem2="КУка:".$_COOKIE['auth_key']." ".$_COOKIE['user']; include('main.php') ;} 
		//else {$perem2="Нет куки".$_COOKIE['user'].isset($_SESSION['rol']); include('form_log.php') ;}
		}
		else {$perem2="Нет куки".$_COOKIE['user'].isset($_SESSION['rol']); include('form_log.php') ; }
?>