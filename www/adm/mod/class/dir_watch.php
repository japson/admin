<?php
class DirWatch //анализ и вывод каталога
{
    private $put;
    private $tabl;
    private $coredir;
    private $saveput;
    public $nodir;
    private $alldir =array();
    private $allfile=array();
    public function __construct($put,$tablic) // получение запроса и вывод колва полей
    {
        $temp=iconv('utf-8','cp1251', $put);
        $this->put=$temp; //передаваемый путь
        $this->tabl=$tablic;
        $this->nodir=$this->classicPut();
    }
    private function classicPut(){ // все пути корневой, записываемый к файлу
        include('var_alt.php');
        $put=$massElements[$this->tabl];
        $prefix=$massPrefix[$this->tabl];
        if(strlen($prefix)>0){$this->coredir=$prefix.$put;}
        else{$this->coredir=$put;}
        $temp=mb_substr ($this->put,0,strlen($this->coredir));
        if($temp==$this->coredir){$this->saveput=mb_substr($this->put,strlen($this->coredir)); return 1;}
        else {return 0;}
       // $pattern = '/^'.$this->coredir.'/i';
       // $this->saveput=preg_replace($pattern,'',$this->put);
    }
    public function scandir($exten){
        If(strlen($exten)>0){$reg='/\.'.$exten.'$/i';} else{$reg='';}
        $newfile=array();
        $newdir=array();
        if ($dh = opendir($this->put)) {
                 while (($file = readdir($dh)) !== false) {
                     $kod=$this->checkStr($file);
                    // $t=$kod[1];
                     if(filetype($this->put.$file)=='dir'){
                         if($kod[0]!='.' && $kod[0]!='..'){
                         array_push($newdir, array($kod[0],$kod[1]));}
                     } else{
                         If(strlen($reg)) {
                             if(preg_match($reg,$kod[0])){
                             array_push($newfile, array($kod[0],$kod[1]));
                             }
                         } else{array_push($newfile, array($kod[0],$kod[1]));}
                     }

                    // $newfile[$t]=$kod[0];
                     //asort($newfile);
                    // array_push($newfile, $kod[0].'-'.filetype($this->put.$file));
                }
            closedir($dh);
        }
        $this->alldir=$newdir;
        $this->allfile=$newfile;
        return array($newdir,$newfile);

        /*$files1 = scandir(iconv('utf-8','cp1251', $this->put));
       // $files1 = scandir($this->put);
            foreach ($files1 as $file){
                $kod=$this->checkStr($file);
                if(is_file ($this->put.$kod[1])){$temp='file -';}else{$temp = 'dir -';}
                $temp.=filetype($this->put.$kod[0]);
               // if(is_dir ($this->put.$kod[1])){$temp.='- dir';}else{$temp .= '- file';}
                //$name = htmlentities($file, ENT_QUOTES, cp1251);
                //$kod=mb_detect_encoding(mb_substr ($file,0,1));
                array_push($newfile, $kod[0].'-'.$temp);
            }
        return $newfile;*/
    }
    private function checkStr($str){ // проверка строки на кодировку всех символов
            $len=strlen($str);
            $newstr=''; $newstrnorm = '';
            for($i=0;$i<$len;$i++){
                if(($kod=mb_detect_encoding(mb_substr ($str,$i,1)))=='ASCII') {$newstr.=mb_substr ($str,$i,1);
                    $newstrnorm.=mb_substr ($str,$i,1);}
                else{$newstr.=htmlentities(mb_substr ($str,$i,1),ENT_QUOTES,cp1251);
                    $newstrnorm.=iconv('cp1251','utf-8',mb_substr ($str,$i,1));
                }
            }
            return array($newstrnorm,$newstr);
    }

} // end class
?>