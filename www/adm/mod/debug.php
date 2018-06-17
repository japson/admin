<?
function debug_to_console($data) {
    if(is_array($data) || is_object($data))
	{
		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
	} else {
		echo("<script>console.log('PHP: ".$data."');</script>");
	}
}
function debug_to_alert($data) {
    if(is_array($data) || is_object($data))
    {
        echo("<script>alert('PHP: ".json_encode($data)."');</script>");
    } else {
        echo("<script>alert('PHP: ".$data."');</script>");
    }
}
?>