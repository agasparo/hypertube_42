<?php
/*  GOOGLE LOGIN BASIC - Tutorial
 *  file            - index.php
 *  Developer       - Krishna Teja G S
 *  Website         - http://packetcode.com/apps/google-login/
 *  Date            - 28th Aug 2015
 *  license         - GNU General Public License version 2 or later
*/
// REQUIREMENTS - PHP v5.3 or later
// Note: The PHP client library requires that PHP has curl extensions configured. 
/*
 * DEFINITIONS
 *
 * load the autoload file
 * define the constants client id,secret and redirect url
 * start the session
 */
require_once __DIR__.'/vendor/autoload.php';
const CLIENT_ID = '';
const CLIENT_SECRET = '';
const REDIRECT_URI = 'http://192.168.99.100.xip.io:41062/www/hypertube/google_connexion/index.php';

if (!isset($_SESSION)) {
  require '../modules/bdd.php';
  session_start();
}
/* 
 * INITIALIZATION
 *
 * Create a google client object
 * set the id,secret and redirect uri
 * set the scope variables if required
 * create google plus object
 */
$client = new Google_Client();
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URI);
$client->setScopes('email');
$plus = new Google_Service_Plus($client);
/*
 * PROCESS
 *
 * A. Pre-check for logout
 * B. Authentication and Access token
 * C. Retrive Data
 */
/* 
 * A. PRE-CHECK FOR LOGOUT
 * 
 * Unset the session variable in order to logout if already logged in    
 */
if (isset($_REQUEST['logout'])) {
 session_unset();
}
/* 
 * B. AUTHORIZATION AND ACCESS TOKEN
 *
 * If the request is a return url from the google server then
 *  1. authenticate code
 *  2. get the access token and store in session
 *  3. redirect to same url to eleminate the url varaibles sent by google
 */
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}
/* 
 * C. RETRIVE DATA
 * 
 * If access token if available in session 
 * load it to the client object and access the required profile data
 */
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
  $me = $plus->people->get('me');
  // Get User data
  $name =  $me['displayName'];
  $email =  $me['emails'][0]['value'];
  $profile_image_url = $me['image']['url'];
} else {
  // get the login url   
  $authUrl = $client->createAuthUrl();
}
?>

<!-- HTML CODE with Embeded PHP-->
<div>
  <?php
    /*
     * If login url is there then display login button
     * else print the retieved data
    */
    if (isset($authUrl)) {
      echo "<a class='login' href='" . $authUrl . "'><img src='google_connexion/signin_button.png' style='margin-top: -0.2vh;width:11vw;'></a>";
    } else {
      if (empty($name)) {
        $es = explode("@", $email);
        $name = $es[0]."_api_co";
        $nn = $es[0];
      }
      require '../class/crypt.class.php';
      $e = new crypt();
      $caracteres = 'abcdefghijklmnopqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $longueurMax = strlen($caracteres);
      $chaineAleatoire = '';
      $length = rand(23, 58);
      $i = 0;
      while ($i < $length) {
        $chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
        $i++;
      }
      $check = $bdd->prepare('SELECT * FROM membre WHERE user_login = ? AND api = ?');
      $check->execute(array($name, 1));
      $infs = $check->fetch();
      if ($check->rowCount() == 0) {
        $insert_user = $bdd->prepare('INSERT INTO membre(user_login, prenom, nom, mail, password, lang, api) VALUES(?, ?, ?, ?, ?, ?, ?)');
        $insert_user->execute(array($name, $nn, "", $email, $e->encrypt($chaineAleatoire), "en_US", 1));
        $new_val = $bdd->prepare('SELECT * FROM membre WHERE user_login = ? AND api = ?');
        $new_val->execute(array($name, 1));
        $infs = $new_val->fetch();
        $inser_photo = $bdd->prepare('INSERT INTO img_users(id_user, img) VALUES(?, ?)');
        $inser_photo->execute(array($infs['id'], $profile_image_url));
      }
      $_SESSION['id'] = $infs['id'];
      $_SESSION['lang'] = $infs['lang'];
      ?><meta http-equiv="refresh" content="0;URL=../"><?php
    }
    ?>
  </div>
