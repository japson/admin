<?php
class DirWatch //анализ и вывод каталога
{
    public $put;
    private $tabl;
    private $aliastbl;
    private $coredir;
    private $saveput;
    private $backput;
    public $nodir;
    private $alldir =array();
    private $allfile=array();
    public $temper;
    public function __construct($put,$tablic) // получение запроса

    {     //  echo '***'.$put;
        $mass=$this->checkStr($put);
            if($mass[2]==1){$temp=$this->conv8($put,0);}   else{$temp=$put;}
       // $temp=$put;
       // $this->temper=strlen($temp).'==='.($put);
        //$temp=$put;
       // $temp=iconv('utf-8','cp1251', $put);
       // $temp=iconv('cp1251','utf-8', $temp);
        $this->put=$temp; //передаваемый путь
        $this->tabl=$tablic;
        $this->nodir=$this->classicPut();
        //$this->temper=$this->put.'-'.$this->coredir;
    }
    public function comeback($put){ // путь для возврата
        $mass=$this->checkStr($put);
        if($mass[2]==1){$temp=$this->conv8($put,0);}   else{$temp=$put;}
        $this->backput=$temp;
    }

    private function classicPut(){ // все пути корневой, записываемый к файлу
        include('var_alt.php');
        $this->aliastbl=$massTablAlias[$this->tabl];
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
        //$this->temper=iconv('cp1251','utf-8', $this->put);
        $tempput=$this->put;
      // $tempput=$this->conv8($tempput,0);
        if ($dh = opendir($tempput)) {
                 while (($file = readdir($dh)) !== false) {
                     $kod=$this->checkStr($file);
                     //$this->temper=$kod[0].'--'.$kod[1];
                    // $t=$kod[1];
                     if(filetype($this->put.$file)=='dir'){
                         if($kod[0]!='.' && $kod[0]!='..'){
                         array_push($newdir, array($kod[0],$kod[1]));}
                            // array_push($newdir, array($kod[0],$file));}
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
       // $this->temper=$newdir;
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
    public function checkStr($str){ // проверка строки на кодировку всех символов
            $len=strlen($str); $flag=0;
            $newstr=''; $newstrnorm = '';
            for($i=0;$i<$len;$i++){
                if(($kod=mb_detect_encoding(mb_substr ($str,$i,1)))=='ASCII') {$newstr.=mb_substr ($str,$i,1);
                    $newstrnorm.=mb_substr ($str,$i,1);}
                else{$newstr.=htmlentities(mb_substr ($str,$i,1),ENT_QUOTES,cp1251);
                    $newstrnorm.=iconv('cp1251','utf-8',mb_substr ($str,$i,1));
                    $flag=1;
                }
            }
            return array($newstrnorm,$newstr,$flag);
    }
    public function outDir(){ //вывод html директории
        $massiv=array();
        $html='<table id="tbl'.$this->aliastbl.'"class="table table-striped tblposition">';
        $html.='<tr><th>№</th><th>Название</th><th>Тип</th><th>Выбрать</th></tr>';
        $dir=$this->alldir;
        $file=$this->allfile;

        for($i=0;$i<count($dir);$i++){
            $html.='<tr  id="direct'.$i.'"><td>'.($i+1).'</td><td class="tbldir"><span class="tbldirsel">'.$dir[$i][0].'</span></td><td>dir</td><td class="allcenter"><div class="buttonselect"></div></td></tr>';
            $massiv[$i]=$dir[$i];
        }

        for($j=0;$j<count($file);$j++){
            $html.='<tr id="direct'.($i+$j).'"><td>'.($i+$j+1).'</td><td class="tblfile" title="'.$file[$j][0].'">'.$file[$j][0].'</td><td>---</td><td class="allcenter"><div class="buttonselect "></div></td></tr>';
            $massiv[$i+$j]=$file[$j];
        }

        $massiv['core']=array($this->conv8($this->put,1),$this->conv8($this->saveput,1),$this->coredir,$this->conv8($this->backput,1));
        $html.='</table>';
        $html=str_replace('[_ZAM]',$html,$this->title());
        //$this->temper=$html;
        return array($html,$massiv);
    }
    private function title(){
        $put=iconv('cp1251','utf-8',$this->put);
        $temp='<div class="tbltitle">'.$put.'<span id="direturn">Назад</span></div><div class="selectwindow">[_ZAM]'.'</div>';
       // $temp.='<div class="inputbl"><input type="text" id="nameart"><input type="text" id="nameson">';
        $temp.='<div class="col-sm-3 col-sm-offset-3"><button class="btn btn-info" onclick="{scanElement();}">OK</button></div>';
        $temp.='<div class="col-sm-3"><button class="btn btn-success" onclick="{saveElements();}">Сохранить</button></div>';
        $temp.='<div class="col-sm-3"><button class="btn btn-warning" onclick="{delOverley();}">Отмена</button></div>';
        return $temp;
    }

    public function conv8($perem,$t){
        if($t) {return iconv('cp1251','utf-8',$perem);}
        else {return iconv('utf-8','cp1251',$perem);}
    }

    public function coreDir() {return $this->coredir;}
    public function Tablic() {return $this->tabl;}
} // end class
?>