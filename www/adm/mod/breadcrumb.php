<?
// вроде не надо 

function ret($uroven, $rasdel) {
	if($uroven==1) return ('<ol class="breadcrumb "><li class="active">Главная</li>  </ol>');
}

function create_menu($uroven, $rasdel){

	$menu='<div class="row">
		<ul class="nav nav-pills col-md-5">
       <li><a href="#" onClick="createRasd('.$uroven.'); return false;">Создать раздел...</a></li>
	   <li><a href="#" onClick="createPos('.$uroven.'); return false;">Создать позицию...</a></li>
	   <li><a href="#" onClick="createNew('.$uroven.',\'r\'); return false;">Создать новость...</a></li>
       </ul> 
  </div>';
return($menu.vyvod_rasd_pos($uroven)); 
}












?>