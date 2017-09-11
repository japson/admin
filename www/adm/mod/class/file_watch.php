<?php
class fileWatch extends DirWatch// дочерний класс вывода файлов----------
{
    public $zapmp3 = array();

    public function __construct($put, $tablic)
        //mb_detect_encoding
    {   //echo ':::'.$put.' :::<br>';
        parent::__construct($put, $tablic);
        //$this->put=$temp;
    }

    public function checkFile($put,$core)
    { // установить файлы это или директория.
        //$core = $this->coreDir();
        if (is_array($put)) {
            foreach ($put as $value) {
                $tt = $this->checkStr($value);
                //echo ';;;'.$tt[2];
                $putnew = $this->conv8( $value, 0);
                // $putnew=$this->conv8($putnew,1);
                // $putnew=$core.$value;
                // $fileconv=urlencode($fileconv);
                //   $putnew= htmlentities($putnew, ENT_QUOTES, 'utf-8');
                //   $putnew= htmlentities($putnew, ENT_QUOTES, 'CP1251');
                //   $stat = stat($putnew);
               // echo filetype(($putnew)); //echo ($stat['size']);
                $info = pathinfo($putnew);
                $exten= $info['extension'];
                echo $exten;
               // $dir=dir($putnew);
               // $entry = $dir->read();
                if (!strlen($exten)  && $dh = opendir($putnew)) { //!is_file($putnew)
                    // echo ($dh);
                    while (($file = readdir($dh)) !== false) {
                        $fileconv = $this->conv8($file, 1);
                        if ($fileconv == '.' || $fileconv == '..') continue;
                     //   echo 'put:'.$file.' put2:'.$fileconv.' <br>';
                       // $temp2=$this->checkRen($value.$file);
                       // echo 'put2:'.$temp2[0].'<br>';
                        // if(strlen($fileconv)==0){$fileconv=$this->conv8($file,1);
                        //     $fileconv222222=urlencode($fileconv);}
                        // echo '--core: '. $core.' --val: '.$value.'/ -- fil: '.$fileconv;
                        $temp2=$this->checkRen($fileconv);
                        $pieces = explode("/", $temp2[0]);
                        $pieces[0]=$this->conv8( $pieces[0], 1);
                        $newcat=implode('/',$pieces);//
                        $newcat=$temp2[0];//
                        //$newcat=$this->conv8( $newcat, 1);
                       // echo 'put2:'.$core .'>'. $newcat . '/'. '>'. $fileconv.'<br>';
                        $newput=str_replace('//','/',$core . $newcat . '/' . $fileconv);
                        echo $newput.'<br>';
                        //$newdir = new fileWatch($newput, $this->Tablic());

                     //   echo $core . $value . '/' . $fileconv . '-' . strlen($fileconv) . '+' . (is_file($putnew)) . '<br>';
                        //echo '++'. $value.'/'.$this->conv8($fileconv,1);
                        //  $this->zapmp3[] = $newdir->checkFile(array($value.'/'.$file));
                        echo 'F->' . $this->checkFile(array($core . '/' . $fileconv),$core);
                        //zapusk dir engine
                    }
                } else {
                     echo ('--else--'.$putnew);
                    if (filetype($putnew) == 'file') {
                        //zapusk file engine
                        $temp = 'tt: ' . $this->processFile($putnew, $value);
                    }
                }
            }
        }


        //return $fileconv.'<br>';//$this->zapmp3;
    }

    private function processFile($file, $value)
    {
        preg_match('/.+\.(\w+)$/xis', $file, $pocket);
        switch ($pocket[1]) {
            case 'mp3':
                $this->zapmp3[] = $this->getMP3Params($file, $value);
                break;
            case 'pdf':
                echo "i равно 1";
                break;
        }
        return $pocket[1];
    }

    public function checkRen($str){ // проверка строки на кодировку всех символов
        $len=strlen($str); $flag=0;
        $newstr=''; $newstrnorm = '';
        for($i=0;$i<$len;$i++){
            $kod=mb_detect_encoding(mb_substr ($str,$i,1));
            if($kod!='UTF-8') {$newstr.=mb_substr ($str,$i,1);
                $newstrnorm.=mb_substr ($str,$i,1);}
            else{$newstr.=htmlentities(mb_substr ($str,$i,1),ENT_QUOTES,cp1251);
                $newstrnorm.=iconv('UTF-8','cp1251',mb_substr ($str,$i,1));
                $flag=1;
            }
        }
        return array($newstrnorm,$newstr,$flag);
    }

    //----------------------------------------------получение параметров mp3 файла----------------
    function getMP3Params($filename, $onefile)
    {
        require_once('getid3/getid3.php');
        //$filename=htmlspecialchars($filename);
        // $put=iconv("cp1251", "UTF-8", $put);
        // $filename=$put."/".$filename;
        $putut = $filename;
        //
        //echo $filename."<br>###<br>";

        //$filename=str_replace("%26","&",$filename);
        //echo $filename."<br>";
        //$filename=iconv("cp1251", "UTF-8", $filename);
        //$filename=iconv("UTF-8", "cp1251", $filename);// перекодировка в спец символы чтобы прочитать файл
        //$filename=str_replace("%2B","+",$filename);
        //echo $filename."<br>";
//$filename=htmlspecialchars($filename);
        $result = Array(
            'title' => '<span color="red">Есть файл, нет информации.</span>',
            'artist' => '',
            'length' => '',
            'year' => '',
            'track_number' => '',
            'genre' => '',
            'album' => '',
            'bytrate' => '',
            'time' => '',
            'rasmer' => '',
            'put' => '',
            'put-utf' => $putut,
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
        $btr = $info['bitrate'];
        $pltime = $info['playtime_string'];
        $filesize = $info['filesize'];


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
            $artist = iconv("UTF-8", "cp1251", $artist);
            $artist = iconv("CP1251", "UTF-8", $artist);
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
            $album = iconv("UTF-8", "cp1251", $album);
            $album = iconv("CP1251", "UTF-8", $album);
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

        $result['bytrate'] = intval($btr / 1000);

        $result['time'] = $pltime;
        $result['rasmer'] = $filesize;
        $result['put'] = $onefile;
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


//----------------------------------
    public function checkFile432423423423423($put)
    { // установить файлы это или директория.
        $core = $this->coreDir();
        if (is_array($put)) {
            foreach ($put as $value) {
                $tt = $this->checkStr($value);
                //echo ';;;'.$tt[2];
                $putnew = $this->conv8($core . $value, 0);
                // $putnew=$this->conv8($putnew,1);
                // $putnew=$core.$value;
                // $fileconv=urlencode($fileconv);
                //   $putnew= htmlentities($putnew, ENT_QUOTES, 'utf-8');
                //   $putnew= htmlentities($putnew, ENT_QUOTES, 'CP1251');
                //   $stat = stat($putnew);
                echo(filetype(($putnew))); //echo ($stat['size']);
                if (filetype($putnew) == 'dir' && $dh = opendir($putnew)) { //!is_file($putnew)
                    // echo ($putnew);
                    while (($file = readdir($dh)) !== false) {
                        $fileconv = $this->conv8($file, 1);
                        // if(strlen($fileconv)==0){$fileconv=$this->conv8($file,1);
                        //     $fileconv222222=urlencode($fileconv);}

                        if ($fileconv == '.' || $fileconv == '..') continue;
                        // echo '--core: '. $core.' --val: '.$value.'/ -- fil: '.$fileconv;
                        $newdir = new fileWatch($core . $value . '/' . $fileconv, $this->Tablic());

                        echo $core . $value . '/' . $fileconv . '-' . strlen($fileconv) . '+' . (is_file($putnew)) . '<br>';
                        //echo '++'. $value.'/'.$this->conv8($fileconv,1);
                        //  $this->zapmp3[] = $newdir->checkFile(array($value.'/'.$file));
                        echo '->' . $newdir->checkFile(array($value . '/' . $file));
                        //zapusk dir engine
                    }
                } else {
                    // echo ('--else--'.$putnew);
                    if (filetype($putnew) == 'file') {
                        //zapusk file engine
                        $temp = 'tt: ' . $this->processFile($putnew, $value);
                    }
                }
            }
        }


        //return $fileconv.'<br>';//$this->zapmp3;
    }

}
?>