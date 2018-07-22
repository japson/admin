<?php
class Social
{
    private $db;
    private $nametabl;
    public $prefix;
    private $myid;
    private $sig;
    private $logoprovaider;
    private $massdat = array();
    public $session = array();
    private $params;
    private $clientSecret; private $provider; private $publicKey;
    private $noname=array('admin','хуй','пизд','ебли','ебла','ебля','japson','japsan','gapson','gapsan','жепсон','жепсан','джипсан','жипсон','жапсон','жапсан');

    public function __construct($nmtbl, $dbh)
    {
        $this->db = $dbh;
        $this->nametabl = $nmtbl;
        $this->prefix = 'https://' . $_SERVER["HTTP_HOST"] . '/';

        //  $this->myid='76206';
        //  $this->sig='c052497084b20340babd5f8e57de182a'; //loginza japson 216345
    }

    private function curlQuery($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($this->params)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl); return $result;
}

   public function tokenGet($token)
    {
        switch($this->provider) {
            case 'vk':  $this->params['client_secret'] = $this->clientSecret;
                        $this->params['code'] = $token;
                        $url='https://oauth.vk.com/access_token?';
                $content = @file_get_contents($url . http_build_query($this->params));
                $response = json_decode($content);
                        break;

            case 'go': $this->params['client_secret'] = $this->clientSecret;
                    $this->params['grant_type'] = 'authorization_code';
                  $this->params['code'] = $token;
                unset($this->params['response_type']);
                unset($this->params['scope']);
                $url = 'https://accounts.google.com/o/oauth2/token';
               // debug_to_alert(($this->params));
                $result=$this->curlQuery($url);
                $response = json_decode($result);
                break;

            case 'ok':  $this->params['client_secret'] = $this->clientSecret;
                $this->params['grant_type'] = 'authorization_code';
                $this->params['code'] = $token;
                unset($this->params['response_type']);
                $url = 'http://api.odnoklassniki.ru/oauth/token.do';
                $result=$this->curlQuery($url);
                $response = json_decode($result);
                break;
            case 'mail': unset($this->params['response_type']);
                $this->params['client_secret'] = $this->clientSecret;
                $this->params['grant_type'] = 'authorization_code';
                $this->params['code'] = $token;
                $url = 'https://connect.mail.ru/oauth/token';
                $result=$this->curlQuery($url);
                $response = json_decode($result);
                break;
            case 'ya': unset($this->params['response_type']); unset($this->params['display']);
                $this->params['grant_type'] = 'authorization_code';
                $this->params['code'] = $token;
                $this->params['client_secret'] = $this->clientSecret;
                $url = 'https://oauth.yandex.ru/token';
                $result=$this->curlQuery($url);
                $response = json_decode($result);
                break;
            case 'fb': unset($this->params['response_type']); unset($this->params['scope']);
                $this->params['client_secret'] = $this->clientSecret;
                $this->params['code'] = $token;
                $url = 'https://graph.facebook.com/oauth/access_token';
                $tokenInfo = null;
                parse_str(file_get_contents($url . '?' . http_build_query($this->params)), $tokenInfo);
                //$response=$tokenInfo;
                $response=($tokenInfo); $mass=array();
                foreach ($response as $key=>$value){
                    $mass[] =$key;
                }
                $response= json_decode($mass[0]);
              //  debug_to_alert($response);
              //  $response= new stdClass();
              //  $response->access_token=$tokenInfo[0];

        }

     //   debug_to_alert($response);
        // Если при получении токена произошла ошибка
        if (!isset($response->error)) {
            $token = $response->access_token; // Токен
          // $expiresIn = $response->expires_in; // Время жизни токена
           // $userId = $response->user_id; // ID авторизовавшегося пользователя
            $_SESSION['token'] = $token;
           // debug_to_alert(($token));
            $tmp=$this->getUserinfo();
           // debug_to_alert(($tmp));
            if(count($tmp)) {
                $tmp = $this->receiveInfo($tmp);
              // debug_to_alert(($tmp));
                if ($tmp['uid'] && $tmp['provider']) {
                    $this->checkRega();
                    $this->makeInvite();
                }
            }
        } else{throw new Exception('При получении токена произошла ошибка. Error: ' . $response->error . '. Error description: ' . $response->error_description);}

    }

    private function getUserinfo()
    {
        $token = $_SESSION['token']; // Извлекаем токен из сессии
       // debug_to_alert(($token));
        switch($this->provider) {
            case 'vk':// unset($this->params['client_secret']);
            $this->params['fields']='first_name,last_name,screen_name,about'; // Список опциональных полей https://vk.com/dev/objects/user
            $this->params['access_token']=$token;
            $url='https://api.vk.com/method/users.get?'; break;
            case 'go':
                $this->params['access_token']=$token;
                $url='https://www.googleapis.com/oauth2/v1/userinfo?';
                break;
            case 'ok': unset($this->params['client_id']); unset($this->params['grant_type']);
                unset($this->params['client_secret']); unset($this->params['redirect_uri']);
                unset($this->params['code']);
                $sign = md5("application_key={$this->publicKey}format=jsonmethod=users.getCurrentUser" . md5("{$token}{$this->clientSecret}"));
                //$sign=mb_strtolower ($sign);
                $this->params['method']='users.getCurrentUser';
                $this->params['access_token']=$token;
                $this->params['application_key']=$this->publicKey;
                $this->params['format']='json';
                $this->params['sig']=$sign;
                $url='http://api.odnoklassniki.ru/fb.do?';
                break;
            case 'mail':
                $sign = md5("app_id={$this->params['client_id']}method=users.getInfosecure=1session_key={$token}{$this->clientSecret}");
                 unset($this->params['grant_type']);unset($this->params['client_secret']);
                 unset($this->params['redirect_uri']); unset($this->params['code']);
                $this->params['method']='users.getInfo';
                $this->params['secure']='1';
                $this->params['app_id']=$this->params['client_id'];
                $this->params['session_key']=$token;
                $this->params['sig']=$sign;
                unset($this->params['client_id']);
                $url='http://www.appsmail.ru/platform/api?';
                break;
            case 'ya': $this->params = array(
                'format'       => 'json', 'oauth_token'  => $token  );
                $url='https://login.yandex.ru/info?';
                break;
            case 'fb': $this->params = array('access_token' => $token);
                $url='https://graph.facebook.com/me?';
                break;

        }
// Формируем запрос

       $content = @file_get_contents($url . http_build_query($this->params));

        $response = json_decode($content);
        //
        $massiv=array();
// Если no возникла ошибка
        if (!isset($response->error)) {
            switch($this->provider) {
                case 'vk': $response = $response->response;
                $massiv=array('uid'=>$response[0]->id,'first_name'=>$response[0]->first_name,'last_name'=>$response[0]->last_name,'identity'=>$token,'nick'=>$response[0]->screen_name,'email'=>'' );
                break;
                case 'go':
                    //debug_to_alert(($response));
                    $massiv=array('uid'=>$response->id,'first_name'=>$response->name,'last_name'=>'','identity'=>$token,'email'=>$response->email,'nick'=>'' );
                    break;
                case 'ok':
                    $massiv=array('uid'=>$response->uid,'first_name'=>$response->name,'last_name'=>'','identity'=>$token,'email'=>'','nick'=>'' );
                    break;
                case 'mail':
                    $massiv=array('uid'=>$response[0]->uid,'first_name'=>$response[0]->first_name,'last_name'=>$response[0]->last_name,'identity'=>$token,'email'=>$response[0]->email,'nick'=>$response[0]->nick );
                    break;
                case 'ya':
                    $massiv=array('uid'=>$response->id,'first_name'=>$response->real_name,'last_name'=>'','identity'=>$token,'email'=>$response->default_email,'nick'=>$response->display_name );
                    break;
                case 'fb':
                    $massiv=array('uid'=>$response->id,'first_name'=>$response->name,'last_name'=>'','identity'=>$token,'email'=>'','nick'=>'');
                    break;
            }

           // debug_to_console($response);
         //   debug_to_alert(($response));
        }
        return $massiv;
    }


    private function checkRega()
    { //проверяю таблицу есть ли данная рега уид и провайдер
        $dbl = $this->db;
        $sol = $this->genSol();
        $sql = 'SELECT * FROM ' . $this->nametabl . ' WHERE provider = "' . $this->massdat['provider'] . '" AND uid = "' . $this->massdat['uid'] . '"';
        // debug_to_console($sol);
        $stmt = $dbl->prepare($sql);
        $stmt->execute();
        if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            // todo: проверить имя мож изменилось
            $this->session = array('id' => $sms[0]['id'], 'uid' => $sms[0]['uid'], 'provider' => $sms[0]['provider'], 'name' => $sms[0]['firstname'] . ' ' . $sms[0]['lastname'], 'logoprov' => $this->logoprovaider, 'ident' => $sol, 'nick'=>$sms[0]['nick']);

        } else {
            foreach ($this->massdat as $key => $value) {
                $keys[] = $key;//. '=?';
                $quest[] = '?';
                $znach[] = htmlspecialchars($value);
            }
            // $sql = 'INSERT INTO '.$this->nametabl.' ('.implode(',',$keys).') VALUES ("'.implode(',',$znach).'")';
            $sql = 'INSERT INTO ' . $this->nametabl . ' (' . implode(',', $keys) . ') VALUES (' . implode(',', $quest) . ')';
            //  debug_to_console($znach);
            $stmt = $dbl->prepare($sql);
            $stmt->execute($znach);
            $ider = $dbl->lastInsertId();
            $this->session = array('id' => $ider, 'uid' => $this->massdat['uid'], 'provider' => $this->massdat['provider'], 'name' => $this->massdat['firstname'] . ' ' . $this->massdat['lastname'], 'logoprov' => $this->logoprovaider, 'ident' => $sol, 'nick'=>'');
        }
        $this->updateUser($sol);
    }

    private function receiveInfo($user)
    { // получить  инфу из логинзы
        $this->massdat = array('uid' => $user['uid'], 'provider' => $this->provider, 'firstname' => $user['first_name'], 'identity' => $user['identity'], 'lastname' => $user['last_name'], 'nick' => $user['nick'],'email' => $user['email']);
        $this->logoprovaider = $this->provider;
        //debug_to_alert(($this->massdat));
        return $this->massdat;
    }

    public function makeInvite()
    {
        // debug_to_console($this->session);
        $_SESSION['jlogin']['is_auth'] = 1;
        $_SESSION['jlogin']['profile'] = $this->session;

    }

    public function exitSol()
    {
        $sol = $this->genSol();
        $this->massdat['provider'] = $_SESSION['jlogin']['profile']['provider'];
        $this->massdat['uid'] = $_SESSION['jlogin']['profile']['uid'];
        $this->updateUser($sol);
    }

    private function genSol()
    {
        $seed = sha1(mt_rand());
        return $seed;
    }

    private function updateUser($sol)
    {
        $dbl = $this->db;
        $sql = 'UPDATE ' . $this->nametabl . ' SET auntf="' . $sol . '" WHERE provider = "' . $this->massdat['provider'] . '" AND uid = "' . $this->massdat['uid'] . '"';
        $stmt = $dbl->prepare($sql);
        $stmt->execute();

    }

    public function outName($user)
    {
        switch ($user['logoprov']) {
            case 'fb': $tmp = 'sc_f.png';  break;
            case 'vk': $tmp = 'sc_vk.png'; break;
            case 'googleplus':$tmp = 'sc_g.png'; break;
            case 'ok': $tmp = 'sc_o.png'; break;
            case 'mail': $tmp = 'sc_mail.png'; break;
            case 'twitter': $tmp = 'sc_t.png'; break;
            case 'go': $tmp = 'sc_gog.png'; break;
            case 'ya': $tmp = 'sc_ya.png'; break;
            case 'instagram':  $tmp = 'sc_inst.png';  break;
        }
        $logo = ' <img class="socimg" src="/img_n/' . $tmp . '">';
        $tmp = $logo . $user['name'] . ' <a id="outsession" href="" onclick="ExitSocial();return false;">Выйти</a>';
        if(!strlen($user['nick'])){$nick=$user['name'];}else{$nick=$user['nick'];}
        $tmp.='<div class="nickname">Ваш ник:<span>'.$nick.'</span><a id="outnick" href="" onclick="changeNick();return false;">Сменить</a></div>';
        return $tmp;
    }

    public function outLogSoc($param)
    {   $log = $this->createAtoken();

        if ($param) {
            $tmp = 'Comments with:' . $log['vk'].$log['go'].$log['ok'].$log['mail'].$log['ya'].$log['fb'];
        } else {
            $tmp = 'Comments with:' . $log['vk'].$log['go'].$log['ok'].$log['mail'].$log['ya'].$log['fb'];//.$log['fb']
        }
        return $tmp;
    }

    private function createAtoken()
    {

// Выводим на экран ссылку для открытия окна диалога авторизации
        $this->createId('vk');
        $vk = '<img class="socimg" id="vkapi" src="/img_n/sc_vk.png">';
                $vk= '<a href="http://oauth.vk.com/authorize?' . http_build_query($this->params) . '" title="Аутентификация через ВКонтакте">'.$vk.'</a>';

        $this->createId('go');
            $go='<img class="socimg" id="goapi" src="/img_n/sc_gog.png">';
                $url = 'https://accounts.google.com/o/oauth2/auth';
                $go= '<a href="' . $url . '?' . urldecode(http_build_query($this->params)) . '" title="Аутентификация через Google">'.$go.'</a>';

        $this->createId('ok');
            $ok='<img class="socimg" id="goapi" src="/img_n/sc_o.png">';
            $url = 'http://www.odnoklassniki.ru/oauth/authorize';
            $ok= '<a href="' . $url . '?' . urldecode(http_build_query($this->params)) . '" title="Аутентификация через Одноклассники">'.$ok.'</a>';
        $this->createId('mail');
        $mail='<img class="socimg" id="goapi" src="/img_n/sc_mail.png">';
        $url = 'https://connect.mail.ru/oauth/authorize';
        $mail= '<a href="' . $url . '?' . urldecode(http_build_query($this->params)) . '" title="Аутентификация через MailRu">'.$mail.'</a>';
        $this->createId('ya');
        $ya='<img class="socimg" id="goapi" src="/img_n/sc_ya.png">';
        $url = 'https://oauth.yandex.ru/authorize';
        $ya= '<a href="' . $url . '?' . urldecode(http_build_query($this->params)) . '" title="Аутентификация через Yandex">'.$ya.'</a>';
        $this->createId('fb');
        $fb='<img class="socimg" id="goapi" src="/img_n/sc_f.png">';
        $url = 'https://www.facebook.com/dialog/oauth';
        $fb= '<a href="' . $url . '?' . urldecode(http_build_query($this->params)) . '" title="Аутентификация через Facebook">'.$fb.'</a>';

return array('vk'=>$vk,'go'=>$go,'ok'=>$ok,'mail'=>$mail,'ya'=>$ya,'fb'=>$fb);

    }


    public function createId($net)
    {
        switch ($net) {

            case 'vk':
                $this->clientSecret = 'mUj4lV3UhYxet8vu9ckM'; // Защищённый ключ
                $this->provider='vk';
                $this->params = array(
                    'client_id' => '6607626', // ID приложения
                    'redirect_uri' => 'http://japson.ru/vk_aunth',// Адрес,  переадресован  после  авторизации
                    'response_type' => 'code',
                    'v' => '5.78', // (обязательный параметр) версия API, которую Вы используете https://vk.com/dev/versions
                    'display' => 'page',

                    // Права доступа приложения
                    // Если указать "offline", полученный access_token будет "вечным" (токен умрёт, если пользователь сменит свой пароль или удалит приложение).
                    // Если не указать "offline", то полученный токен будет жить 12 часов.
                    'scope' => 'first_name, last_name,  offline',
                );
                break;
            case 'go':
                $this->clientSecret = 'dwlx989pnpnGnATny9BJEiQo';
                $this->provider='go';
                $this->params = array(
                    'client_id' => '552551007325-8rrjjkl257ckd1f91qms70topkjuim2l.apps.googleusercontent.com', // ID приложения
                    'redirect_uri' => 'http://japson.ru/google_aunth',// Адрес,  переадресован  после  авторизации
                    'response_type' => 'code',
                    'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
                ); break;
            case 'ok':
                $this->clientSecret = 'FFF5DC2A945BE69715A74738';
                $this->publicKey='CBALFCJMEBABABABA';
                $this->provider='ok';
                $this->params = array(
                    'client_id'     => '1267816960', // Application ID
                    'response_type' => 'code',
                    'redirect_uri'  => 'http://japson.ru/ok_aunth'
                ); break;

            case 'mail':
                $this->provider='mail';
                $this->clientSecret = '96edaff029eed30e8a9784fc00b73939';
                $this->params = array(
                    'client_id'     => '760627', // Application ID
                    'response_type' => 'code',
                    'redirect_uri'  => 'http://japson.ru/mailru_aunth'
                ); break;
            case 'ya':
                $this->provider='ya';
                $this->clientSecret = '1f5b26227705412f8347579ece89ab39';
                $this->params = array(
                    'client_id'     => 'c444d1d5928f445998040666bd4d6b99', // Application ID
                    'response_type' => 'code',
                    'display'       => 'popup',
                    'redirect_uri'  => 'http://japson.ru/ya_aunth'
                ); break;
            case 'fb':
                $this->provider='fb';
                $this->clientSecret = '14b282807de1142181f7f372ca47c4a0';
                $this->params = array(
                    'client_id'     => '229182897668357', // Application ID
                    'response_type' => 'code',
                    'scope'         => 'email,user_birthday',
                    'redirect_uri'  => 'https://japson.ru/fb_aunth'
                ); break;
        }

    }
    public function makeNick($nick){
        $newnick=htmlspecialchars($nick); $tmp=0;
        $no=(mb_strtolower($newnick));
        for($i=0;$i<count($this->noname);$i++){
           if(stripos(mb_strtolower($newnick),$this->noname[$i])=='false'){$tmp=1;};
           //debug_to_console(mb_strtolower($newnick));
        }
        if(!$tmp){
            $dbl=$this->db;
            $sql = 'UPDATE ' . $this->nametabl . ' SET nick="' . $newnick . '" WHERE provider = "' . $this->provider . '" AND uid = "' . $_SESSION['jlogin']['profile']['uid'] . '"';
           //  debug_to_console($sql);
            $stmt = $dbl->prepare($sql);
            $stmt->execute();
            $name=$newnick;
            $_SESSION['jlogin']['profile']['nick']=$newnick;
        } else {
            if(strlen($_SESSION['jlogin']['profile']['nick'])) {$name=$_SESSION['jlogin']['profile']['nick'];}
            else{$name=$_SESSION['jlogin']['profile']['name'];}
        }
       // debug_to_console($name);
       return $name;
    }
}
?>