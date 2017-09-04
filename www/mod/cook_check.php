<?
function cook_check($sost){
$korendir=$_SERVER['HTTP_HOST'];
$korendir=str_replace('www.','',$korendir); 
$proverka=0;
$nalichie=0;
$kolvo=0;

if(isset($_COOKIE['jap17'])) 	{ $cook=$_COOKIE['jap17']; } 
else { $cook=1;setcookie("jap17", $cook, time() + 60 * 60 * 2 * 1, "/", $korendir, false, true); //underground
	 }
if($sost>0) { setcookie("jap17", $sost, time() + 60 * 60 * 2 * 1, "/", $korendir, false, true);
$cook=$sost;
}

return ($cook);	
}
?>