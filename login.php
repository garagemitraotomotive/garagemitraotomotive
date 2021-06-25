<?php

/*

IndoWapBlog-beta-v01.zip Full Editing by : Master Chef IWB
Facebook : http://fb.com/mhozacuplis1
Website : http://cuplascuplis.jw.lt
Website : http://cuplis.tk
Website : http://cuplis.fixi.in

*Nama Script: IndoWapBlog-beta-v01
*Versi: (Lihat VERSION.txt)
*Pembuat: Achunk JealousMan
*Email: achunk17[at]gmail[dot]com
*Situs: http://indowapblog.com
*Facebook: http://www.facebook.com/achunks
*/

define('_IWB_', 1);

$iwb=isset($_GET['iwb']) ? trim($_GET['iwb']) : '';
switch ($iwb)
{
case 'conf':
require_once('inc/indowapblog.php');
if (isset($_GET['code']))
{
$kode=stripslashes(htmlentities($_GET['code']));
$cek=mysql_query("select * from user where substr(confirm,-6)='".mysql_real_escape_string($kode)."' limit 1;") or die(mysql_error());
if (mysql_num_rows($cek) == 0)
{
$err='<ol id="error"><li>'.$LANG['incorrect_confirm_code'].'</li><ol>';
}
else
{
$res=mysql_fetch_array($cek);
$uid = md5(base64_encode($res['id']));
$pwd = md5(substr($res['confirm'],0,17));
$cook = $uid . $pwd;
$cookie = md5($cook);
mysql_query("update `user` set `password`='".substr($res['confirm'],0,32)."', `cookie`='".mysql_real_escape_string($cookie)."', `confirm`='0' where `id`='".$res['id']."'");
$head_title = $LANG['confirm'] . $LANG['password'];
require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content">';
echo '<div id="show_bar"><a href="login.php">'.$LANG['login'].'</a> | <a href="login.php?iwb=lost_password">'.$LANG['forgot_password'].'</a> | '.$LANG['confirm'].'</div>';
echo '<p>'.$LANG['successfully_confirm'].'</p>';
echo '</div></div>';
require_once('inc/foot.php');
exit;
}
}
$head_title = $LANG['confirm'] . $LANG['password'];
require_once('inc/head.php');
echo '<div id="message">';
if (!empty($err))
echo $err;
echo '</div><div id="content"><div id="main-content">';
echo '<div id="show_bar"><a href="login.php">'.$LANG['login'].'</a> | <a href="login.php?iwb=lost_password">'.$LANG['forgot_password'].'</a> | '.$LANG['confirm'].'</div>';
echo '<form method="get" action="login.php"><input type="hidden" name="iwb" value="conf"/><h4>'.$LANG['code'].'</h4><input name="code" type="text" maxlength="6" size="25"/><br/><p align="center"><input class="iwb-button" type="submit" value="'.$LANG['confirm'].'"/></p></form>';
echo '</div></div>';
require_once('inc/foot.php');
break;

case 'setcookie';
$iwb_redirecting = "off";
include('inc/indowapblog.php');
function karfil($var)
{
$var=preg_replace('#([\W_]+)#','',$var);
return $var;
}
$kont=count(explode(".", $_SERVER['HTTP_HOST']));
if ($kont > 2)
{
$arr=explode(".", $_SERVER['HTTP_HOST'], 2);
$dom=$arr[1];
}
else
{
$dom=$_SERVER['HTTP_HOST'];
}
$cookie=karfil($_GET['key']);
$password=karfil($_GET['secred']);
$redir=str_replace('&amp;','',htmlspecialchars(urldecode($_GET['redir'])));
$ck_us = mysql_result(mysql_query("SELECT COUNT(*) FROM user WHERE password='".mysql_real_escape_string($password)."' AND cookie='".mysql_real_escape_string($cookie)."'"),0);
if ($ck_us == 0) {
}
else {
setcookie("keylog2", $cookie, time() +60 * 60 * 24 * 30, "/", $dom);
session_destroy();
}
header('location: '.$redir);
break;

case 'lost_password':
require_once('inc/indowapblog.php');
if ($user_id) {
header('location: dashboard.php');
exit;
}
if (isset($_POST['send']))
{

$login=$_POST['log'];
$pw=$_POST['pass'];
$rpw=$_POST['re_pass'];
$code=$_POST['code'];
if ($code != $_SESSION['captcha_code'])
$err=$LANG['incorrect_security_code'];
$cek=mysql_query("select * from user where email='".mysql_real_escape_string($login)."'");
if (mysql_num_rows($cek) == 0)
$err=$LANG['email_not_found'];
$us=mysql_fetch_array($cek);
if (empty($us['email']))
$err=$LANG['user_not_have_email'];
if (mb_strlen($pw) > 12 || mb_strlen($pw) < 4)
$err=$LANG['incorrect_password'];
if ($pw != $rpw)
$err=$LANG['incorrect_password'];
if (empty($err))
{
$pwd=md5($pw);
$kode=rand(100000, 999999);
$lp=''.$pwd.$kode.'';
mysql_query("update user set confirm='".$lp."' where id='".$us['id']."'");
$adm=mysql_fetch_array(mysql_query("select * from user where id='1' limit 1;"));

$email = $us['email'];
$subject=$LANG['confirm_new_pwd_sbjct'];
$pesan = str_replace('::site_url::',$site_url,str_replace('::site_name::',htmlspecialchars($site['name']),str_replace('::link::',''.$site['url'].'/login.php?iwb=conf&code='.$kode,$LANG['confirm_new_pwd_msg'])));
$dari = "From: ".htmlspecialchars($site['name'])." <".$adm['email'].">\r\n";
$dari .= "X-sender: ".htmlspecialchars($site['name'])." <".$adm['email'].">\r\n";
$dari .= "Content-type:text/plain; charset=iso-8859-1\r\n";
$dari .= "MIME-Version: 1.0\r\n";
$dari .= "Content-Transfer-Encoding: 8bit\r\n";
$dari .= "X-Mailer: PHP v.".phpversion();
mail($email,$subject,$pesan,$dari);
$head_title=$LANG['forgot_password'];
require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content"><p>'.$LANG['confirm_code_was_send'].'</p></div>';
echo '</div>';
require_once('inc/foot.php');
exit;
}
else
{
$hsl='<ol id="error"><li>'.$err.'</li></ol>';
}
}

$head_title=$LANG['forgot_password'];
require_once('inc/head.php');
echo '<div id="message">';
if (!empty($hsl))
echo $hsl;
echo '</div><div id="content"><div id="main-content">';
echo '<div id="show_bar"><a href="login.php">'.$LANG['login'].'</a> | '.$LANG['forgot_password'].' | <a href="login.php?iwb=conf">'.$LANG['confirm'].'</a></div>';
echo '<form method="post" action="login.php?iwb=lost_password"><h4>'.$LANG['email'].'</h4><input name="log" type="text" size="25"/><br/><h4>'.$LANG['new_password'].'</h4>
<input name="pass" type="password" maxlength="12" size="25"/><br/>
<h4>'.$LANG['re_new_password'].'</h4>
<input class="iwb-password" name="re_pass" type="password" maxlength="12" size="30"/><br/>';
$_SESSION['captcha_code'] = strval(rand(1000, 9999));
echo '<h4>'.$LANG['security_code'].':</h4><img src="captcha.php" alt=""/><br /><input type="text" name="code" value=""/><p align="center"><input name="send" type="submit" value="'.$LANG['send'].'"/></p></form></div></div>';
require_once('inc/foot.php');
break;

case 'logout':
define('_IWB_', 1);
include('inc/config.php');
session_name('IndoWapBlog');
session_start();
$domain = isset($_GET['domain']) ? trim($_GET['domain']) : $iwb_main_domain;
$rdr = isset($_GET['redir']) ? trim($_GET['redir']) : '';
if (preg_match("/".$iwb_main_domain."/i", $_SERVER['HTTP_HOST']))
{
setcookie('keylog', '', time()-3600, '/', $iwb_main_domain);
session_destroy();
$redir='login.php?redir='.htmlentities($rdr).'';
}
else
{
$kont=count(explode(".", $_SERVER['HTTP_HOST']));
if ($kont > 2)
{
$arr=explode(".", $_SERVER['HTTP_HOST'], 2);
$dom=$arr[1];
}
else
{
$dom=$_SERVER['HTTP_HOST'];
}
setcookie('keylog2', '', time()-3600, '/', $dom);
session_destroy();
$redir='http://www.'.$iwb_main_domain.'/login.php?iwb=logout&redir='.htmlentities($rdr).'';
}
header('Location: '.str_replace('&amp;','&',htmlentities($redir)).'');
break;

default:
require_once('inc/indowapblog.php');
$redir=isset($_GET['redir']) ? trim($_GET['redir']) : $_POST['redir'];
if ($user_id)
{
if (empty($redir) || $redir == '')
$redirect=''.$user_site.'/dashboard.php';
else
$redirect=str_replace('&amp;', '&', htmlspecialchars(base64_decode($redir)));

$custom_domain=explode("/", str_replace("http://", "", $redirect));
$cd=$custom_domain[0];
if (!preg_match("/".$iwb_main_domain."/i", $cd))
{
$continue='http://'.$cd.'/login.php?iwb=setcookie&key='.$indowapblog['cookie'].'&secred='.$indowapblog['password'].'&redir='.urlencode($redirect).'';
}
else
{
$continue = $redirect;
}
header('location: '.str_replace('&amp;','&',htmlentities($continue)).'');
exit;
}
if (isset($_POST['login']))
{
$login=$_POST['log'];
$domain=$_POST['domain'];
$password=md5($_POST['password']);
$key = $_POST['key'];
$usite='http://'.strtolower($login).'.'.strtolower($domain).'';

$cek=mysql_query("SELECT `id` FROM `site` WHERE `url`='".mysql_real_escape_string($usite)."' or `url_www`='".mysql_real_escape_string($usite)."'");

if (mysql_num_rows($cek) == 0 || $_SESSION['key'] != $key)
{
$error=str_replace('::site::',htmlentities($usite),$LANG['site_not_found']);
}
else
{
$st_user=mysql_fetch_array($cek);
$cek2=mysql_query("SELECT * FROM `user` WHERE `id`='".mysql_real_escape_string($st_user['id'])."' and `password`='".mysql_real_escape_string($password)."'");
if (mysql_num_rows($cek2) == 0)
{
$error=$LANG['incorrect_password'];
}
else
{
$user=mysql_fetch_array($cek2);

if (empty($redir) || $redir == '')
$redirect=''.$user['site'].'/dashboard.php';
else
$redirect=str_replace('&amp;', '&', htmlspecialchars(base64_decode($redir)));

if ($user['cookie'] == '')
{
$uid = md5(base64_encode($user['id']));
$pwd = substr($password,0,17);
$cook = $uid . $pwd . time();
$cookie = md5($cook);

mysql_query("UPDATE `user` SET `cookie`='".mysql_real_escape_string($cookie)."' WHERE `id`='".$user['id']."'");
}
else
{
$cook = $user['cookie'] . time();
$cookie = md5($cook);
mysql_query("UPDATE `user` SET `cookie`='".mysql_real_escape_string($cookie)."' WHERE `id`='".$user['id']."'");
}
$custom_domain=explode("/", str_replace("http://", "", $redirect));
$cd=$custom_domain[0];

setcookie("keylog", $cookie, time() +60 * 60 * 24 * 30, "/", $iwb_main_domain);
if (!preg_match("/".$iwb_main_domain."/i", $cd))
{
$continue='http://'.$cd.'/login.php?iwb=setcookie&key='.$cookie.'&secred='.$user['password'].'&redir='.urlencode($redirect).'';
}
else
{
$continue = $redirect;
}
header('location: '.$continue.'');
exit;
}
}
}

$live_chat='off';
$head_title=$LANG['login'];
require_once('inc/head.php');
echo '<div id="message">';
if (!empty($error))
echo '<ol id="error"><li>'.$error.'</li></ol>';
echo '</div><div id="content"><div id="main-content">';
echo '<div id="show_bar">'.$LANG['login'].' | <a href="login.php?iwb=lost_password">'.$LANG['forgot_password'].'</a> | <a href="login.php?iwb=conf">'.$LANG['confirm'].'</a></div>';
echo '<p>'.$LANG['not_have_account'].'? <a href="register.php">'.$LANG['register_here'].'!</a>';
echo '</p><form method="post" action="login.php?"><h4>'.$LANG['username'].'</h4>
<input class="iwb-text" name="log" type="text" value="'.htmlentities($username).'" maxlength="32" size="25"/><br/>
<h4>'.$LANG['domain'].'</h4><select name="domain"><option value="'.$iwb_main_domain.'">'.$iwb_main_domain.'</option></select><br/>
<h4>'.$LANG['password'].'</h4>
<input name="password" type="password" maxlength="12" size="25"/><br/>';
$_SESSION['key'] = rand(100000, 999999);
echo '<input name="key" type="hidden" value="'.htmlentities($_SESSION['key']).'"/><input name="redir" type="hidden" value="'.str_replace('&amp;', '&', htmlspecialchars($redir)).'"/><br/><a href="login.php?iwb=lost_password" rel="dofollow">'.$LANG['forgot_password'].'?</a><br/><input class="iwb-button" name="login" type="submit" value="'.$LANG['login'].'"/></form></div></div>';
require_once('inc/foot.php');
}
?>