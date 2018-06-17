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
    private $clientSecret; private $provider;

    public function __construct($nmtbl, $dbh)
    {
        $this->db = $dbh;
        $this->nametabl = $nmtbl;
        $this->prefix = 'http://' . $_SERVER["HTTP_HOST"] . '/';

        //  $this->myid='76206';
        //  $this->sig='c052497084b20340babd5f8e57de182a'; //loginza japson 216345
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
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($this->params)));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $result = curl_exec($curl);
                curl_close($curl);

                $response = json_decode($result);
                break;

        }

      //  $content = @file_get_contents($url . http_build_query($this->params));


       // debug_to_alert(($response));
        // Если при получении токена произошла ошибка
        if (!isset($response->error)) {
            $token = $response->access_token; // Токен
          // $expiresIn = $response->expires_in; // Время жизни токена
           // $userId = $response->user_id; // ID авторизовавшегося пользователя
            $_SESSION['token'] = $token;
            $tmp=$this->getUserinfo();
           // debug_to_alert(($token));
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
        }
// Формируем запрос

       $content = @file_get_contents($url . http_build_query($this->params));

        $response = json_decode($content);
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
            }

           // debug_to_console($response);

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
            $this->session = array('id' => $sms[0]['id'], 'uid' => $sms[0]['uid'], 'provider' => $sms[0]['provider'], 'name' => $sms[0]['firstname'] . ' ' . $sms[0]['lastname'], 'logoprov' => $this->logoprovaider, 'ident' => $sol);

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
            $this->session = array('id' => $ider, 'uid' => $this->massdat['uid'], 'provider' => $this->massdat['provider'], 'name' => $this->massdat['firstname'] . ' ' . $this->massdat['lastname'], 'logoprov' => $this->logoprovaider, 'ident' => $sol);
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
            case 'facebook':
                $tmp = 'sc_f.png';
                break;
            case 'vk':
                $tmp = 'sc_vk.png';
                break;
            case 'googleplus':
                $tmp = 'sc_g.png';
                break;
            case 'odnoklassniki':
                $tmp = 'sc_o.png';
                break;
            case 'mailru':
                $tmp = 'sc_mail.png';
                break;
            case 'twitter':
                $tmp = 'sc_t.png';
                break;
            case 'go':
                $tmp = 'sc_gog.png';
                break;
            case 'yandex':
                $tmp = 'sc_ya.png';
                break;
            case 'instagram':
                $tmp = 'sc_inst.png';
                break;
        }
        $logo = ' <img class="socimg" src="/img_n/' . $tmp . '">';
        $tmp = $logo . $user['name'] . ' <a id="outsession" href="" onclick="ExitSocial();return false;">Выйти</a>';
        return $tmp;
    }

    public function outLogSoc($param)
    {   $log = $this->createAtoken();

        if ($param) {

            /*  $tmp = 'Comments only with:<br>
              <div id="uLogin22addfea" data-ulogin="display=panel;fields=first_name,last_name,email;theme=flat;providers=vkontakte,odnoklassniki,mailru,facebook,google,instagram;hidden=twitter,yandex,googleplus;redirect_uri=;callback=callUlogin"></div> ';*/
           // $vk = '<img class="socimg" id="vkapi" src="/img_n/sc_vk.png">';
         //   $log = str_replace('[_ZAM]', $vk, $log);
            $tmp = 'Comments only with:' . $log['vk'].$log['go'];
        } else {
           // $vk = '<img class="socimg" id="vkapi" src="/img_n/sc_vk.png">';
           // $log = str_replace('[_ZAM]', $vk, $log);
            $tmp = 'Comments only with:' . $log['vk'].$log['go'];
        }
        return $tmp;
    }

    private function createAtoken()
    {

// Выводим на экран ссылку для открытия окна диалога авторизации
        $this->createId('vk');
        $vk = '<img class="socimg" id="vkapi" src="/img_n/sc_vk.png">';
                $vk= '<a href="http://oauth.vk.com/authorize?' . http_build_query($this->params) . '" title="Авторизация через ВКонтакте">'.$vk.'</a>';
        $this->createId('go');
            $go='<img class="socimg" id="goapi" src="/img_n/sc_gog.png">';
                $url = 'https://accounts.google.com/o/oauth2/auth';
                $go= '<a href="' . $url . '?' . urldecode(http_build_query($this->params)) . '" title="Аутентификация через Google">'.$go.'</a>';

return array('vk'=>$vk,'go'=>$go);

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
                );
        }

    }
}
?>