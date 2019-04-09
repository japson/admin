<?php
$prefix='';
/* Показываем браузеру, что это xml-документ */

header("content-type:text/xml");

/* Выводим название и описание канала*/
$zz='<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">';
$zz2='<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">';
echo $zz."
<channel>
<title>Japson's Undeground</title>
<link>https://japson.ru</link>
<description>Творчество поэта и музыканта Дмитрия Кривякина. А также заметки, статьи участника Искитимского андеграунда папы Джепсона.</description>
<copyright>Copyright 1994-2018 papa Japson</copyright>
<language>ru</language>";

//echo("<!DOCTYPE html><html><body>");
require_once($prefix.'adm/mod/conn/db_conn.php');
require_once($prefix.'adm/mod/debug.php');
include('mod/class/createmenu.php');
include('mod/class/rssclass.php');

$take = new rssclass('news',$db);
$where=' WHERE redirect=0 and vyvod=1 and kodrasdel>0 order by data desc ';
$take->createRss($where);

foreach ($take->news as $row)
{
    $img='<img src="https://japson.ru'.$row['pictur'].'">';
    $syl='<a href="https://japson.ru'.$row['url'].'" target="_blank"> Далее...</a>';
    $descr='<![CDATA[<table><tr><td>'.$img.'</td><td valign="top">'.$row['description'].$syl.'</td></tr></table>]]>';
    // <enclosure url="https://japson.ru'.$row['pictur'].'" type="image/jpeg"/>
    echo '<item>
            <title>'.$row['title'].'</title>
           
            <link>https://japson.ru'.$row['url'].'</link>
            <guid>https://japson.ru'.$row['url'].'</guid>
            <description>'.$descr.'</description>
            <author>Japson</author>
            <pubDate>'.$row['data'].'</pubDate>
            <category>'.$row['tag'].'</category>
         </item>';

}
//debug_to_console( $take->mainmenu);
//echo json_encode($take->news);

//echo("</body></html>");
echo "</channel></rss>"
?>