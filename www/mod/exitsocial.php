<?php
session_start();
require_once($prefix.'../adm/mod/conn/db_conn.php');
require_once($prefix.'../adm/mod/debug.php');
include('class/socialclass.php');
$aunt = new Social('comuser',$db);
//debug_to_console('5465464');
$aunt->exitSol();
unset($_SESSION['ulogin']);
echo ($aunt->outLogSoc(0));
//debug_to_console($_SESSION['loginza']);
?>