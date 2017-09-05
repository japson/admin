<?
$massTablAlias=array('editors'=>'users','mainmenu'=>'menu','init'=>'admmenu','sets'=>'settings','rasdel'=>'section','rasdelimg'=>'sectionimg','news'=>'post','newsimg'=>'postsimg', 'punkt'=>'position');
$picturTbl=array('rasdel'=>'rasdelimg','news'=>'newsimg');
$picturKat=array('rasdel'=>'../../catalog/imgrasdel/', 'news'=>'../../catalog/imgnews/');

// иерархия
$massLink=array('mainmenu'=>array('rasdel'=>'1'),'rasdel'=>array('rasdel'=>'1','news'=>'0'));

$massUroven=array('mainmenu'=>0,'rasdel'=>1, 'editors'=>2, 'sets'=>2, 'news'=>2, 'punkt'=>2);

?>