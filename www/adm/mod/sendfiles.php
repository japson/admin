<? //need

if(isset($_POST['roddir'])) {$rod=$_POST['roddir']; }// путь для сканирования
if(isset($_POST['uroven'])) {$uroven=$_POST['uroven']; }// уровень для сканирования
if(isset($_POST['mus'])) {$tipmus=$_POST['mus']; }// файлы для получения getid3
if (strlen($tipmus)>0) {$tip=$tipmus; 
require_once('getid3/getid3.php');} 
else {$tip="";}

if(isset($_POST['pict'])) {$tipict=$_POST['pict']; }// файлы для получения внешних атрибутов
if (strlen($tipict)>0) {
						if($tipict=='jpnote') {$tipict='jpnote';}
						 else  if($tipict=="") {$tipict=$tipict;}
}
//if ($tipict=="mp3") {require_once('getid3/getid3.php');} 
$rod2=$rod;
$rod=iconv("UTF-8", "cp1251", $rod);
$filelist = array();
$zapmp3=array();
//-------------------------подготовка директорий
require_once('dbconn.php');
if ($db->connect_errno) {
   echo "Не удалось установить подключение к базе данных";
   exit();
}
require_once('constlocal.php');
$korendir=$GLOBALS['KOREN_DIR']."/";
$aliasdir=$GLOBALS['ALIAS_DIR'];

$rod2=str_replace(DIRECTORY_SEPARATOR, '/', $rod2."/"); // непонятно надо ли это делать? под windows можно не делать
$pututobrez=str_replace($korendir,"",$rod2) ;

//--------------------------сканирование директории
if ($handle = opendir($rod)) {

scandeep($rod, $pututobrez);	   
	
	}
closedir($handle);
	//echo $tipict.'\n';
//--------------------------------создание блока песен	
	$blockpicture="";
	$vyvodpicture="";
	$blockalbum="";
	$blocksongs="";
	foreach ($filelist as $massiv) {
	//echo strlen($filelist).'\nnnn';	
		if ($massiv["tip"]=="jpg") {
			if(strlen($vyvodpicture)==0){$vyvodpicture.='<img id="curpicture" kd="'.$aliasdir.'/'.'" title="'.$massiv['put'].''.$massiv['value'].'" src="'.$aliasdir.'/'.$massiv['put'].''.$massiv['value'].'">';}
			$curepicture='curpicture';
			$blockpicture.='<div onclick="vybor_picture(this,\''.$curepicture.'\');" kd="'.$aliasdir.'/'.'" title="'.$massiv['put'].''.$massiv['value'].'">'.$massiv['value'].' ('.number_format($massiv["rasmer"]/1000,"0","."," ").' Kb)</div>';}
			
			if ($massiv["tip"]=="jpnote") {
			if(strlen($vyvodpicture)==0){$vyvodpicture.='<img id="notepicture" kd="'.$aliasdir.'/'.'" title="'.$massiv['put'].''.$massiv['value'].'" src="'.$aliasdir.'/'.$massiv['put'].''.$massiv['value'].'">';}
			$curepicture='notepicture';
			$blockpicture.='<div onclick="vybor_picture(this, \''.$curepicture.'\');" kd="'.$aliasdir.'/'.'" title="'.$massiv['put'].''.$massiv['value'].'">'.$massiv['value'].' ('.number_format($massiv["rasmer"]/1000,"0","."," ").' Kb)</div>';}
			
					
		if ($massiv["tip"]==$tip) {
			if (strlen($massiv['title'])==0){$titl="<span class='noname'>НЕТ НАЗВАНИЯ</span>";} else {$titl=$massiv['title'];}
		   if(!strlen($tipict)) {
			   
			   $blocksongs.='<ul class="treesong" name="new">'.
				'<li class="Isroot">  <div class="Expand"><span class="IsrootPlus"></span><span title="Номер трека" name="number">'.$massiv['track_number'].
				'</span> — <span title="Исполнитель" name="singer">'.$massiv['artist'].
				'</span> — <span title="Композиция" name="title">'.$titl.
				'</span> — <span title="Время" name="vremya">'.$massiv['time'].
				'</span> </div> </li>'.  //<span title="добавление" class="delsong"></span>
				'<li class="Childer ExpandClose">  <div class="Expand"><span class="ChilderMinus"></span>'.
				'<span title="Расположение" name="put">'.$massiv['put_utf'].
				'</div> </li>'.
				'<li class="Laster ExpandClose">  <div class="Expand"><span class="LasterMinus"></span><span title="Битрейт" name="bytrate">'.$massiv['bytrate'].
				'</span> — <span title="Размер в байтах" name="rasmer">'.$massiv['rasmer'].
				'</span></div> </li> </ul>';
			   	}
		   else{
				if(strlen($blockalbum)==0){$blockalbum=' <div class="scanarrows"  value="1"><span id="leftscan" class="arrowleft"></span>'.
				'<span id="rightscan" class="arrowright"></span><input type="button" class="buttonblue" id="vybor_alb" value="Выбрать" /></div>'.
				'<div class="albuminfoscan"><div id="nomalbumscan">Track: <span>1</span></div><div id="namealbumscan"> ALBUM: <span>'.$massiv['album'].'</span></div>'.
				' <div id="yearalbumscan">YEAR: <span>'.$massiv['year'].'</span></div><div id="genrealbumscan">GENRE: <span>'.$massiv['genre'].'</span></div></div>';}
				
				$blocksongs.='<tr title="'.$massiv['put_utf'].'" alb="'.$massiv['album'].'" ye="'.$massiv['year'].'" gr="'.$massiv['genre'].'"><td>'.
				$massiv['track_number'].'</td><td>'.$massiv['artist'].'</td><td>'.$titl.'</td><td   width="7em" align="right">'.$massiv['time'].
				'</td><td width="5em">'.$massiv['bytrate'].'</td><td  width="7em" align="right">'.$massiv['rasmer'].'</td><td width="10%">'.
				$massiv['genre'].'</td><td  width="4em">'.$massiv['year'].'</td></tr>';
				}
			}
		}
	//$filelist2[0]='<div class="blvbpicture"><table class="vyborpicture"><tr><td class="vpicture">'.$vyvodpicture.'</td><td class="picturelist">'.$blockpicture.'</td></tr></table></div>';
	$filelist2[0]='<div class="vpicture">'.$vyvodpicture.'</div><div class="blvbpicture"><table class="vyborpicture"><tr><td class="picturelist">'.$blockpicture.'</td></tr></table></div>';
	//echo $filelist2[0]."            *****      ";
	$filelist2[1]='<div class="vvodalbumdata">'.$blockalbum.'</div>';
	$filelist2[2]='<div class="vyborsong"><table>'.$blocksongs.'</table></div>';
	echo json_encode($filelist2);
//----------------------------------------------функция сканирования вглубь каталога----------------
function scandeep($rod, $pututobrez){
global $filelist;
global $tip;
global $tipict;
global $uroven;
global $korendir;
$file_list = scandir($rod);	
 
	foreach ($file_list as $entry) {	
//echo $tip." ***  ";
		if ( $uroven==1 && is_dir($rod."/".$entry) && $entry!="."&& $entry!=".."){// сканирование вглубь
		$pututobrez2=$pututobrez.iconv("CP1251", "UTF-8", $entry."/");
		scandeep($rod."/".$entry, $pututobrez2);
		}
		$direntry=$rod."/".$entry."";
		//echo ($entry.":пипецявначале-----fgfgd---------явконце");
		if (strlen($tip)>0 && stripos($entry,".".$tip) > 0) {  // if mp3 then take in
			$entry=iconv("CP1251", "UTF-8", $entry);
			$df=htmlspecialchars($entry);
		 	$zapmp3[]=getMP3Params($entry, $rod);
		 	$put=str_replace($korendir,"",$zapmp3[0]["put"]) ;
			$put_utf=str_replace($korendir,"",$zapmp3[0]["put-utf"]) ;
			//echo $zapmp3[0]["put-utf"];
			$zapr=array("tip"=>$tip);
			//echo json_encode($zapr);return(0);
			$filelist[] = array("tip"=>$tip,"value"=>$df,"artist"=> $zapmp3[0]["artist"],"album"=>$zapmp3[0]["album"],"year"=>$zapmp3[0]["year"],"track_number"=>$zapmp3[0]["track_number"],"title"=>$zapmp3[0]["title"],"time"=>$zapmp3[0]["time"],"genre"=>$zapmp3[0]["genre"],"bytrate"=>$zapmp3[0]["bytrate"],"rasmer"=>$zapmp3[0]["rasmer"],"put_title"=>$put,"put_utf"=>$put_utf); // put_utf нужен для дальнейшего
			$zapmp3=array();
		}
		switch ($tipict) {
			case 'jpg':
			//if (strpos($entry,"."."jpg") > 0 or strpos($entry,"."."png") > 0 or strpos($entry,"."."jpeg") > 0) {  // if picture
			if ( preg_match('/.jpg$/',$entry)  or preg_match('/.png$/',$entry) or preg_match('/.jpeg$/',$entry)) {  // if picture
			  $entry=iconv("CP1251", "UTF-8", $entry);
			  $rasmer=filesize($rod."/".$entry);
			  $filelist[] = array("tip"=>"jpg","value"=>htmlspecialchars($entry),"put"=>htmlspecialchars($pututobrez),"rasmer"=>$rasmer);
			//echo $pututobrez. ":::".$entry;
			}
			break;
			case 'jpnote':
			//if (strpos($entry,"."."jpg") > 0 or strpos($entry,"."."png") > 0 or strpos($entry,"."."jpeg") > 0) {  // if picture
			if ( preg_match('/.jpg$/',$entry)  or preg_match('/.png$/',$entry) or preg_match('/.jpeg$/',$entry)) {  // if picture
			  $entry=iconv("CP1251", "UTF-8", $entry);
			  $rasmer=filesize($rod."/".$entry);
			  $filelist[] = array("tip"=>"jpnote","value"=>htmlspecialchars($entry),"put"=>htmlspecialchars($pututobrez),"rasmer"=>$rasmer);
			//echo $pututobrez. ":::".$entry;
			}
			break;
		}
								  
                                  }	
	//closedir($handle); 
	//echo json_encode($filelist[0]);
	}

//----------------------------------------------получение параметров mp3 файла----------------
function getMP3Params($filename, $put)
    {
	//$filename=htmlspecialchars($filename);
	$put=iconv("cp1251", "UTF-8", $put);
	$filename=$put."/".$filename;
	$putut=$filename;
	//	
	//echo $filename."<br>###<br>";
	
	//$filename=str_replace("%26","&",$filename);
	//echo $filename."<br>";
	//$filename=iconv("cp1251", "UTF-8", $filename);
	$filename=iconv("UTF-8", "cp1251", $filename);// перекодировка в спец символы чтобы прочитать файл	
	//$filename=str_replace("%2B","+",$filename);
	//echo $filename."<br>";
//$filename=htmlspecialchars($filename);
 $result = Array(
            'title'  => '<font color="red">Есть файл, нет информации.</font>',
            'artist' => '',
            'length' => '',
			'year' =>'',
			'track_number' =>'',
			'genre' =>'',
			'album' =>'',
			'bytrate' =>'',
			'time' =>'',
			'rasmer' =>'',
			'put' =>'',
			'put-utf' =>$putut,
        );


try {
	
            $g = new getID3();
			
           // $g->encoding = 'UTF-8'; // В примере было, ни на что не влияет
          // echo $filename."<br>***<br>";
		   @$g->analyze($filename); // Куча Strict Standarts
			
        } catch (Exception $e) {
			            /* Не знаю, что за исключения, но в примере был try-catch */
            return false;
        }
		
		//echo "<br><br>".json_encode($g->info) ;
		
		
        if (!isset($g->info)) {
			
            return false;
        }
		//echo $info['fileformat'];
		//echo $info['error'];
        $info = $g->info;
        if (isset($info['error'])) {
            return false;
        }
        if ($info['fileformat'] != 'mp3') {
			
            return false;
        }
        $length = intval(
                (($info['avdataend'] - $info['avdataoffset']) * 8) / 
                $info['audio']['bitrate']
        );
		$btr=$info['bitrate'];
		$pltime=$info['playtime_string'];
		$filesize=$info['filesize'];
		
       
        if (isset($info['id3v2'])) {
            $info = $info['id3v2'];
        } elseif (isset($info['id3v1'])) {
            $info = $info['id3v1'];
        } else {
            return $result;
        }
        if (!isset($info['comments'])) {
            return $result;
        }
        $info = $info['comments'];
        if (isset($info['artist'])) {
            $artist = $info['artist'][0];
            /* Танцы с бубном */
           // $ar     = mb_convert_encoding($artist, 'iso-8859-1', 'utf-8');
           // $artist = $ar ? $ar : $artist;
           // $artist = mb_convert_encoding($artist, 'utf-8', 'windows-1251');
		   $artist=iconv("UTF-8", "cp1251", $artist);
		   $artist=iconv("CP1251", "UTF-8", $artist);
            $result['artist'] = $artist;
        }
        if (isset($info['title'])) {
            $title = $info['title'][0];
			//$title=iconv("CP1251", "UTF-8", $title);
			//$title=iconv("UTF-8", "cp1251", $title);
			//echo  $title . strlen($title) ;
            //$tl    = mb_convert_encoding($title, 'iso-8859-1', 'utf-8');
           // $title = $tl ? $tl : $title;
           // $title = mb_convert_encoding($title, 'utf-8', 'windows-1251');
			//$title=iconv("CP1251", "UTF-8", $title); 
			//$title=iconv("UTF-8", "cp1251", $title);
		 //  
            $result['title'] = $title;
		}
		 if (isset($info['album'])) {
            $album = $info['album'][0];
            //$tl    = mb_convert_encoding($title, 'iso-8859-1', 'utf-8');
            //$title = $tl ? $tl : $title;
            //$title = mb_convert_encoding($title, 'utf-8', 'windows-1251');
			$album=iconv("UTF-8", "cp1251", $album);
		   $album=iconv("CP1251", "UTF-8", $album);
            $result['album'] = $album;
		}
		if (isset($info['year'])) {
            $title = $info['year'][0];
            $result['year'] = $title;
		}
		if (isset($info['genre'])) {
            $title = $info['genre'][0];
            $result['genre'] = $title;
		}
		if (isset($info['track_number'])) {
            $title = $info['track_number'][0];
            $result['track_number'] = $title;
		}
		
            $result['bytrate'] = intval($btr/1000);

			$result['time'] = $pltime;
			$result['rasmer'] = $filesize;
			$result['put'] = $filename;
			$result['put-utf'] = $putut;
			$result['length'] = $length;
		
		//echo ("Track: ". $result['track_number']);//."<br>");
		//echo ( " Title: ".$result['title']."<br>");
		//echo ("Artist: ". $result['artist']."<br>");
		//echo ( "Album: ".$result['album']."<br>");
		//echo ( "Year: ".$result['year']."<br>");
		//echo ( "genre: ".$result['genre']."<br>");
		//echo ( "Bytrate: ".$btr."<br>");
		//echo ( "Time: ".$pltime."<br>");
		//echo ( "Rasmer: ".$filesize."<br>");
		//echo (  json_encode($info)."<br>");
		return $result;
		
	}	
	
	///------------------------
	function scandeep2222222222222222($handle2){
 while (false !== ($entry = readdir($handle2))) {
			
		if (stripos($entry,".".$tip) > 0) {  // if mp3 then take in
			 $entry=iconv("CP1251", "UTF-8", $entry);
			 // echo $entry;
		 	$zapmp3[]=getMP3Params($entry, $rod);
		 	$put=str_replace($korendir,"",$zapmp3[0]["put"]) ;
			$put_utf=str_replace($korendir,"",$zapmp3[0]["put-utf"]) ;
			//echo $zapmp3[0]["put-utf"];
		    $filelist[] = array("tip"=>$tip,"value"=>htmlspecialchars($entry),"artist"=> $zapmp3[0]["artist"],"album"=>$zapmp3[0]["album"],"year"=>$zapmp3[0]["year"],"track_number"=>$zapmp3[0]["track_number"],"title"=>$zapmp3[0]["title"],"time"=>$zapmp3[0]["time"],"genre"=>$zapmp3[0]["genre"],"bytrate"=>$zapmp3[0]["bytrate"],"rasmer"=>$zapmp3[0]["rasmer"],"put_title"=>$put,"put_utf"=>$put_utf); // put_utf нужен для дальнейшего
 			$zapmp3=array();
		  						  }
		if (strpos($entry,"."."jpg") > 0 or strpos($entry,"."."png") > 0 or strpos($entry,"."."jpeg") > 0) {  // if picture
		  $entry=iconv("CP1251", "UTF-8", $entry);
		  $rasmer=filesize($rod.'/'.$entry);
		  $filelist[] = array("tip"=>"jpg","value"=>htmlspecialchars($entry),"put"=>htmlspecialchars($pututobrez),"rasmer"=>$rasmer);
		
		}
								  
                                  }	
	}
?>