<?
$perem='<div class="row"> 
<div class="col-lg-4 offset2"><h4>Вход в административную часть</h4> </div>
</div>
<div class="row"><div class="control-group offset2 " id="errorsave"> </div></div>
<form class="form-horizontal col-sm-5" role="form">
  <div class="form-group">
    <label for="inputLogin" class="col-sm-2 control-label">Логин</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" id="logname" placeholder="Логин">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword" class="col-sm-2 control-label">Пароль</label>
    <div class="col-sm-7">
      <input type="password" class="form-control" id="logpass" placeholder="Пароль">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
     <button type="submit" id="log_butt" onClick="login_a(); return false;" class="btn btn-default">Войти</button>
    </div>
  </div>
</form>';

?>