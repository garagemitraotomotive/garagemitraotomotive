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
require_once('inc/indowapblog.php');
$iwb=isset($_GET['iwb']) ? trim($_GET['iwb']) : '';
switch ($iwb)
{
case 'phone_number':
if (!$user_id)
relogin();
$head_title=$LANG['phone_number'];
require_once('inc/head.php');
echo '<div id="message">';
echo '</div>';
echo '<div id="content">
<div id="main-content">';
echo '<div id="show_bar"><a href="user.php">'.$LANG['profile'].'</a> | <a href="user.php?iwb=edit">'.$LANG['edit'].'</a> | <a href="user.php?iwb=upload">'.$LANG['upload_photo'].'</a> | <a href="user.php?iwb=password">'.$LANG['change_password'].'</a></div>';
if (isset($_POST['confirm']))
{
$kode = "<".htmlentities($_POST['code']).">";
if (preg_match("#".$kode."#s",$indowapblog['no_hp']))
{
$hp = str_replace($kode,"",$indowapblog['no_hp']);
mysql_query("UPDATE user SET no_hp='".mysql_real_escape_string($hp)."' WHERE id='".$user_id."'");
echo $LANG['confirmation_successfully'];
}
else {
echo $LANG['incorrect_confirm_code'];
}
}
elseif (isset($_POST['save']))
{
$cc = str_replace('+','',$_POST['cc']);
$hp = $_POST['hp'];
if (!ctype_digit($cc) || !ctype_digit($hp))
{
echo $LANG['incorrect_phone_number'];
}
elseif (strlen($cc) < 1 || strlen($cc) > 4 || strlen($hp) < 6 || strlen($hp) > 12)
{
echo $LANG['incorrect_phone_number'];
}
elseif (md5($_POST['pwd']) != $indowapblog['password'])
{
echo $LANG['incorrect_password'];
}
else {
if (substr($hp,0,1) == "0")
$hp = substr($hp,1);
$kode = $user_id . rand(100,999);
$phone = "[+".$cc."]".$hp."<".$kode.">";
$get = implode("",file("http://smester.net/sms2.php?api=133968184436511&kode=".$cc."&nomor=".$hp."&pesan=".urlencode("Kode komfirmasi Anda adalah ".$kode.". IndoWapBlog.  ")));
if (preg_match("#Berhasil#si",$get))
{
mysql_query("UPDATE user SET no_hp='".mysql_real_escape_string($phone)."' WHERE id='".$user_id."'");
echo "".str_replace("::via::","Nomor Handphone",$LANG['confirm_code_was_sent'])."<br /><form method=\"post\" action=\"user.php?iwb=phone_number\"><h4>".$LANG['code']."</h4><input class=\"iwb-text\" type=\"text\" name=\"code\" value=\"\"/><br ><input class=\"iwb-button\" type=\"submit\" name=\"confirm\" value=\"".$LANG['confirm']."\"/></form><br />";
}
else {
echo $LANG['service_not_available'];
}
}
}
else {
if (preg_match("#<#si",$indowapblog['no_hp']))
$myHP = "";
else
$myHP = str_replace("[","",str_replace("]","",$indowapblog['no_hp']));
echo '<b>'.$LANG['phone_number'].': '.$myHP.'</b><br /><form method="post"
action="user.php?iwb=phone_number">';
echo '<table border="0"><tr><td><b>CC</b></td><td><b>HP</b></td><tr><td><input class="iwb-text"
name="cc" type="text" value=""
maxlength="4" size="5"/></td><td><input class="iwb-text"
name="hp" type="text" value=""
maxlength="12" size="25"/></td></tr></table><br/>CC: '.$LANG['country_code'].'<br />HP: '.$LANG['phone_number'].'<br /><h4>'.$LANG['password'].'</h4>
    <input class="iwb-text"
name="pwd" type="password" value=""
maxlength="12" size="30"/><br/>    <input class="iwb-button" name="save" type="submit" value="'.$LANG['send'].'"/></form><br/>';
echo "<div class=\"row1\"><form method=\"post\" action=\"user.php?iwb=phone_number\"><h4>".$LANG['code']."</h4><input class=\"iwb-text\" type=\"text\" name=\"code\" value=\"\"/><br ><input class=\"iwb-button\" type=\"submit\" name=\"confirm\" value=\"".$LANG['confirm']."\"/></form></div><br />";
}
echo '</div></div>';
require_once('inc/foot.php');
break;

case 'search':
if (!$user_id)
relogin();
$nama=$_GET['name'];
$total=mysql_result(mysql_query("SELECT COUNT(*) FROM user WHERE name LIKE '%".mysql_real_escape_string($nama)."%'"), 0);
$head_title=$LANG['search'];
require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content">';
echo '<h4>'.$LANG['search'].'</h4><form action="'.$site['url'].'/user.php" method="get"><input type="hidden" name="iwb" value="search"/><div class="two-col-btn"><input class="iwb-text" name="name" type="text" value="'.htmlspecialchars($nama).'"/><input class="iwb-button" type="submit" style="width: 50%" value="'.$LANG['search_submit'].'"/></div></form>';

if ($total == 0)
{
echo '<p>'.str_replace('::number::',$total,str_replace('::query::',htmlspecialchars($nama),$LANG['search_result'])).'</p>';
}
else
{
$page=$_GET['page'];
if (!ctype_digit($page) || empty($page) || $page == 0 || $page > (ceil($total / $site['num_post_main'])))
$page='1';
$page--;
$max_view=$site['num_post_main'];
$limit=$page*$max_view;
$page++;
$req=mysql_query("SELECT id, name, author, admin, date_reg FROM user WHERE name LIKE '%".mysql_real_escape_string($nama)."%' ORDER BY date_reg DESC LIMIT $limit,$max_view");
echo 'Ditemukan '.$total.' untuk pencarian '.htmlspecialchars($nama).'.<br /><ul>';
while ($res=mysql_fetch_array($req))
{
echo $i % 2 ? '<li class="row1">' : '<li class="row0">';
echo '<img src="img.php?img='.$res['id'].'.jpg&amp;w=40&amp;h=40" alt="'.htmlspecialchars($res['name']).'" /> ';
if (($res['author'] == '1') && ($res['admin'] == '0'))
{
$font="green";
}
elseif (($res['author'] == '2') && ($res['admin'] == '0'))
{
$font="red";
}
elseif (($res['author'] == '3') && ($res['admin'] == '0'))
{
$font="blue";
}
elseif (($res['author'] == '4') && ($res['admin'] == '0'))
{
$font="yellow";
}
elseif ($res['admin'] == '1')
{
$font="#731174";
}
else
{
$font="black";
}

echo '<a href="user.php?id='.$res['id'].'"><font color="'.$font.'">'.htmlspecialchars($res['name']).'</font></a>';
++$i;
echo '</li>';
}
$link='user.php?iwb=search&amp;name='.htmlentities(urlencode($nama)).'&amp;page=';
$q='';
pagination($page,$max_view,$total,$link,$q);
echo '</ul>';
}
echo '</div></div>';
require_once('inc/foot.php');

break;

case 'list':
if (!$user_id)
relogin();
$view=isset($_GET['view'])? trim($_GET['view']) : '';
$page=$_GET['page'];
if ($view == 'member')
{
$total=mysql_result(mysql_query("select count(*) as num from user where author='0' and ban='0'"), 0);
$head_title='List Member';
}
elseif ($view == 'ban')
{
$total=mysql_result(mysql_query("select count(*) as num from user where ban='1'"), 0);
$head_title='Member Diblokir';
}
elseif ($view == 'author')
{
$total=mysql_result(mysql_query("select count(*) as num from user where author='1' and ban='0'"), 0);
$head_title='List Penulis';
}
elseif ($view == 'admin')
{
$total=mysql_result(mysql_query("select count(*) as num from user where admin='1'"), 0);
$head_title='List Administrator';
}
elseif ($view == 'notlogin')
{
$total=mysql_result(mysql_query("select count(*) as num from user where lastdate=''"), 0);
$head_title='Belum Masuk';
}
else
{
$total=mysql_result(mysql_query("select count(*) as num from user"), 0);
$head_title='List Pengguna';
}
if (!ctype_digit($page) || empty($page) || $page == 0 || $page > (ceil($total / $site['num_post_main'])))
$page='1';
$page--;
$max_view=$site['num_post_main'];
$limit=$page*$max_view;
$page++;
require_once('inc/head.php');
echo '<div id="message">';
echo '</div>';
echo '<div id="content">
<div id="main-content">';
echo '<div id="show_bar">';
if (empty($view))
echo $LANG['all'];
else
echo '<a href="user.php?iwb=list">'.$LANG['all'].'</a>';
echo ' | ';
if ($view == 'member')
echo $LANG['member'];
else
echo '<a href="user.php?iwb=list&amp;view=member">'.$LANG['member'].'</a>';
echo ' | ';
if ($view == 'ban')
echo $LANG['baned'];
else
echo '<a href="user.php?iwb=list&amp;view=ban">'.$LANG['baned'].'</a>';
echo ' | ';
if ($view == 'author')
echo $LANG['author'];
else
echo '<a href="user.php?iwb=list&amp;view=author">'.$LANG['author'].'</a>';
echo ' | ';
if ($view == 'admin')
echo $LANG['admin'];
else
echo '<a href="user.php?iwb=list&amp;view=admin">'.$LANG['admin'].'</a>';
echo ' | ';
if ($is_admin)
{
if ($view == 'notlogin')
echo $LANG['not_login'];
else
echo '<a href="user.php?iwb=list&amp;view=notlogin">'.$LANG['not_login'].'</a>';
}
echo '</div>';
echo '<h4>'.$LANG['search'].'</h4><form action="'.$site['url'].'/user.php" method="get"><input type="hidden" name="iwb" value="search"/><div class="two-col-btn"><input class="iwb-text" name="name" type="text" value=""/><input class="iwb-button" type="submit" style="width: 50%" value="'.$LANG['search_submit'].'"/></div></form><br />';
if ($view == 'member')
{
$req=mysql_query("select * from user where author='0' and ban='0' order by id desc limit $limit,$max_view");
}
elseif ($view == 'ban')
{
$req=mysql_query("select * from user where ban='1' order by id desc limit $limit,$max_view");
}
elseif ($view == 'author')
{
$req=mysql_query("select * from user where author='1' and ban='0' order by id desc limit $limit,$max_view");
}
elseif ($view == 'admin')
{
$req=mysql_query("select * from user where admin='1' order by id desc limit $limit,$max_view");
}
elseif ($view == 'notlogin')
{
$req=mysql_query("select * from user where lastdate='' order by id asc limit $limit,$max_view");
}
else
{
$req=mysql_query("select * from user order by id desc limit $limit,$max_view");
}
if ($total > 0)
{
echo '<ul>';
while ($res=mysql_fetch_array($req))
{
echo $i % 2 ? '<li class="row1">' : '<li class="row0">';
echo '<img src="img.php?img='.$res['id'].'.jpg&amp;w=40&amp;h=40" alt="'.htmlspecialchars($res['name']).'" /> ';

if (($res['author'] == '1') && ($res['admin'] == '0'))
{
$font="green";
}
elseif (($res['author'] == '2') && ($res['admin'] == '0'))
{
$font="red";
}
elseif (($res['author'] == '3') && ($res['admin'] == '0'))
{
$font="blue";
}
elseif (($res['author'] == '4') && ($res['admin'] == '0'))
{
$font="yellow";
}
elseif ($res['admin'] == '1')
{
$font="#731174";
}
else
{
$font="black";
}

echo '<a href="user.php?id='.$res['id'].'"><font color="'.$font.'">'.htmlspecialchars($res['name']).'</font></a>';
++$i;
echo '</li>';
}
echo '</ul>';
}
else
{
echo '<p>'.$head_title.' '.$LANG['empty'].'</p>';
}
echo '</div>';
if (empty($view))
$link='user.php?iwb=list&amp;page=';
else
$link='user.php?iwb=list&amp;view='.htmlentities($view).'&amp;page=';
$q='';
pagination($page,$max_view,$total,$link,$q);
echo '</div>';
require_once('inc/foot.php');
break;

case 'email':
if (!$user_id)
relogin();
if (isset($_POST['change']))
{
$pwd=md5($_POST['pwd']);
$email=strtolower($_POST['email']);
$err='Maaf email tidak bisa dirubah. Untuk merubahnya harap hubungi Administrator.';
if ($indowapblog['password'] != $pwd)
$err=$LANG['incorrect_password'];
if (mb_strlen($email) < 2 || mb_strlen($email) > 250)
$err=$LANG['lenght_email'];
if (!eregi("^[a-z0-9\._-]+@[a-z0-9\._-]+\.[a-z]{2,4}\$", $email))
$err=$LANG['incorrect_email'];
if (empty($email))
$err=$LANG['empty_email'];
$check_email=mysql_query("select * from `user` where `email`='".mysql_real_escape_string($email)."'");
if (mysql_num_rows($check_email) != 0)
$err=$LANG['email_was_used'];
if (empty($err))
{
mysql_query("update user set email='".mysql_real_escape_string($email)."' where id='".$user_id."'");
$hsl='<ol id="success"><li>'.$LANG['change_saved'].'</li></ol>';
}
else
{
$hsl='<ol id="error"><li>'.$err.'</li></ol>';
}
}
$head_title=$LANG['change_email'];
require_once('inc/head.php');
echo '<div id="message">';
if (!empty($hsl))
echo $hsl;
echo '</div>';
echo '<div id="content">
<div id="main-content">';
echo '<div id="show_bar"><a href="user.php">'.$LANG['profile'].'</a> | <a href="user.php?iwb=edit">'.$LANG['edit'].'</a> | <a href="user.php?iwb=upload">'.$LANG['upload_photo'].'</a> | <a href="user.php?iwb=password">'.$LANG['change_password'].'</a></div>';
echo '<form method="post"
action="user.php?iwb=email">';

echo '<h4>'.$LANG['email'].'</h4>
    <input class="iwb-text"
name="email" type="text" value="'.htmlspecialchars($user_email).'"
maxlength="255" size="30"/><br/><h4>'.$LANG['password'].'</h4>
    <input class="iwb-text"
name="pwd" type="password" value=""
maxlength="12" size="30"/><br/>    <input class="iwb-button" name="change" type="submit" value="'.$LANG['save'].'"/></form><br/>';
echo '</div></div>';
require_once('inc/foot.php');
break;

case 'edit':
if (!$user_id)
relogin();

if (isset($_POST['save']))
{
if (!$user_id)
relogin();

$name=$_POST['name'];
$gender=$_POST['gender'];
$birthday=$_POST['birthday'];
$address=$_POST['location'];
$about=$_POST['about'];
if ($name != $user_name && $indowapblog['credit'] < 300)
$error = 'Perubahan Nama dikenakan kredit Rp 300';

if (mb_strlen($name) < 2 || mb_strlen($name) > 40)
$error=$LANG['lenght_name'];
if (empty($name))
$error=$LANG['empty_name'];
if (preg_match("/[^a-zA-Z0-9\ \-\.\@\{\}\_]/", $name))
$error=$LANG['incorrect_name'];
if (substr($name,0,1) == ' ' || substr($name,-1) == ' ')
$error=$LANG['incorrect_name'];
if (!eregi("^[0-9]{2}-[0-9]{2}-[0-9]{4}\$", $birthday))
$error=$LANG['format_birthday'];

if (empty($error))
{
if ($name == $user_name) {
mysql_query("update user set name='".mysql_real_escape_string($name)."', gender='".mysql_real_escape_string($gender)."', birthday='".mysql_real_escape_string($birthday)."', address='".mysql_real_escape_string($address)."', about='".mysql_real_escape_string($about)."' where id='".$user_id."'");
}
else {
$kr = $indowapblog['credit'] - 300;
mysql_query("update user set name='".mysql_real_escape_string($name)."', gender='".mysql_real_escape_string($gender)."', birthday='".mysql_real_escape_string($birthday)."', address='".mysql_real_escape_string($address)."', about='".mysql_real_escape_string($about)."', credit='".mysql_real_escape_string($kr)."' where id='".$user_id."'");
}
$hsl='<ol id="success"><li>'.$LANG['change_saved'].'</li></ol>';
}
else
{
$hsl='<ol id="error"><li>'.$error.'</li></ol>';
}
}

$head_title=$LANG['edit'];
require_once('inc/head.php');
echo '<div id="message">';
if (!empty($hsl))
echo $hsl;
echo '</div>';
echo '<div id="content">
<div id="main-content">';
echo '<div id="show_bar"><a href="user.php">'.$LANG['profile'].'</a> | '.$LANG['edit'].' | <a href="user.php?iwb=upload">'.$LANG['upload_photo'].'</a> | <a href="user.php?iwb=password">'.$LANG['change_password'].'</a></div>';
echo '<form method="post"
action="user.php?iwb=edit">
<h4>'.$LANG['photo'].' (<a
href="user.php?iwb=upload">'.$LANG['upload'].'</a>)</h4>
<img src="img.php?img='.$user_id.'.jpg&amp;w=80&amp;h=80" alt="'.htmlspecialchars($indowapblog['name']).'"/><h4>'.$LANG['name'].'</h4>
    <input class="iwb-text"
name="name" type="text" value="'.htmlspecialchars($indowapblog['name']).'"
maxlength="49" size="30"/><br/>
<h4>'.$LANG['gender'].'</h4><select class="iwb-select" name="gender">';
if ($indowapblog['gender'] == 'female')
echo '<option value="female">'.$LANG['female'].'</option><option value="male">'.$LANG['male'].'</option>';
else
echo '<option value="male">'.$LANG['male'].'</option><option value="female">'.$LANG['female'].'</option>';
echo '</select><br/>';
echo '<h4>'.$LANG['birthday'].'</h4>
    <input class="iwb-text" name="birthday" type="text" value="'.htmlentities($indowapblog['birthday']).'" maxlength="49" size="30"/><br/><span>HH-BB-TTTT</span><br/><h4>'.$LANG['site'].'</h4><b>'.htmlspecialchars($indowapblog['site']).'</b><br/><h4>'.$LANG['email'].' (<a href="user.php?iwb=email">'.$LANG['change'].'</a>)</h4>'.htmlentities($indowapblog['email']).'<br/>';
if (preg_match("#<#si",$indowapblog['no_hp']))
$myHP = "";
else
$myHP = str_replace("[","",str_replace("]","",$indowapblog['no_hp']));
echo '<h4>'.$LANG['phone_number'].' (<a href="user.php?iwb=phone_number">'.$LANG['change'].'</a>)</h4>'.htmlentities($myHP).'<br/>';
echo '<h4>'.$LANG['address'].'</h4><input class="iwb-text" name="location" type="text" value="'.htmlspecialchars($indowapblog['address']).'" maxlength="20" size="30"/><br/><h4>'.$LANG['about'].'</h4><textarea class="iwb-textarea" name="about" rows="3"/>'.htmlspecialchars($indowapblog['about']).'</textarea>
    <br/>
    <input class="iwb-button" name="save" type="submit" value="'.$LANG['save'].'"/></form>';

echo '</div></div>';
require_once('inc/foot.php');

break;
case 'password':
if (!$user_id)
relogin();
if (isset($_POST['change']) && $user_id)
{
$old=$_POST['old_pass'];
$pass=$_POST['new_pass'];
$re_pass=$_POST['re_new_pass'];
if ($indowapblog['password'] != md5($old))
$hasil='<ol id="error"><li>'.$LANG['incorrect_password'].'</li></ol>';
if ($pass != $re_pass)
$hasil='<ol id="error"><li>'.$LANG['incorrect_password'].'</li></ol>';
if (mb_strlen($pass) < 4 || mb_strlen($pass) > 12) $hasil='<ol id="error"><li>'.$LANG['lenght_password'].'</li></ol>';
if (empty($pass) || empty($re_pass)) $hasil='<ol id="error"><li>'.$LANG['empty_password'].'</li></ol>';if (empty($hasil))
{
$new_password=md5($pass);
$cook = md5($user_id) . substr($new_password,0,17);
$cookie = md5($cook);
mysql_query("update `user` set `password`='".mysql_real_escape_string($new_password)."', `cookie`='".mysql_real_escape_string($cookie)."' where `id`='".$user_id."'");
$hasil='<ol id="success"><li>'.$LANG['change_saved'].'</li></ol>';
}
}

$head_title=$LANG['change_password'];
require_once('inc/head.php');
echo '<div id="message">';
if (!empty($hasil))
echo $hasil;
echo '</div>';
echo '<div id="content">
<div id="main-content">';
echo '<div id="show_bar"><a href="user.php">'.$LANG['profile'].'</a> | <a href="user.php?iwb=edit">'.$LANG['edit'].'</a> | <a href="user.php?iwb=upload">'.$LANG['upload_photo'].'</a> | '.$LANG['change_password'].'</div>';
echo '<form method="post" action="user.php?iwb=password"><h4>'.$LANG['old_password'].'</h4>
<input class="iwb-password" name="old_pass" type="password" size="30"/><h4>'.$LANG['new_password'].'</h4>
<input class="iwb-password" name="new_pass" type="password" size="30"/>
<h4>'.$LANG['re_new_password'].'</h4>
<input class="iwb-password" name="re_new_pass" type="password" size="30"/><br/>    <input class="iwb-button" name="change" type="submit" value="'.$LANG['change'].'"/></form>';
echo '</div></div>';
require_once('inc/foot.php');

break;

case 'upload':
if (!$user_id)
relogin();
$maxsize='200';
$fail=$_FILES['file']['tmp_name'];
$failname=$_FILES['file']['name'];
if (isset($_POST['upload']) && $user_id)
{
$types = array("image/jpg","image/png","image/gif","image/jpeg");
if (!in_array(mime_content_type($fail), $types))
$hsl='<ol id="error"><li>'.$LANG['incorrect_file_type'].'</li></ol>';
if ($_FILES['file']['size'] > (1024*$maxsize))
$hsl='<ol id="error"><li>'.$LANG['photo_max_200kb'].'</li></ol>';
if (empty($failname))
$hsl='<ol id="error"><li>'.$LANG['failed'].'</li></ol>';
if ($_FILES['file']['size'] == 0)
$hsl='<ol id="error"><li>'.$LANG['failed'].'</li></ol>';
if (empty($hsl))
{
if (move_uploaded_file($fail, "images/profile/$user_id.jpg"))
$hsl='<ol id="success"><li>'.$LANG['photo_successfully_upload'].'</li><li><img src="img.php?img='.$user_id.'.jpg&amp;w=64&amp;h=64" alt="'.htmlspecialchars($user_name).'" /></li></ol>';
else
$hsl='<ol id="error"><li>'.$LANG['failed'].'</li></ol>';
}
}
$head_title=$LANG['upload_photo'];
require_once('inc/head.php');
echo '<div id="message">';
if (!empty($hsl))
echo $hsl;
echo '</div>
<div id="content">
<div id="main-content">';
echo '<div id="show_bar"><a href="user.php">'.$LANG['profile'].'</a> | <a href="user.php?iwb=edit">'.$LANG['edit'].'</a> | '.$LANG['upload_photo'].' | <a href="user.php?iwb=password">'.$LANG['change_password'].'</a></div>';
echo '<form action="user.php?iwb=upload" method="post" enctype="multipart/form-data">
    <input name="MAX_FILE_SIZE" value="200000" type="hidden"/>
    <h4>'.$LANG['select_file'].'</h4><input type="file" name="file"/>
    <input class="iwb-button" name="upload" type="submit" value="'.$LANG['upload'].'"/></form></div></div>';
require_once('inc/foot.php');


break;


default:
if (!$user_id)
relogin();
$id=abs((int)$_GET['id']);
if (empty($id))
$id=$user_id;
$req=mysql_query("select * from user where id='".mysql_real_escape_string($id)."'");
if (mysql_num_rows($req) == 0)
{
require_once('inc/head.php');
echo '<div id="message"><ol id="error"><li>Pengguna dengan id '.htmlentities($id).' tidak ada</li></ol></div>';
require_once('inc/foot.php');
exit;
}
$USER=mysql_fetch_array($req);
$head_title=htmlspecialchars($USER['name']);
require_once('inc/head.php');
echo '<div id="message"></div><div id="content">
<div id="main-content">';
if ($USER['id'] == $user_id)
echo '<div id="show_bar">'.$LANG['profile'].' | <a href="user.php?iwb=edit">'.$LANG['edit'].'</a> | <a href="user.php?iwb=upload">'.$LANG['upload_photo'].'</a> | <a href="user.php?iwb=password">'.$LANG['change_password'].'</a></div>';
if ($is_admin && $USER['id'] != $user_id && $USER['admin'] < 1)
{
echo '<div id="show_bar">';
if ($USER['author'] > 0)
echo '<a href="owner.php?iwb=author&amp;action=delete&amp;id='.$USER['id'].'">'.$LANG['delete_author'].'</a>';
else
echo '<a href="owner.php?iwb=author&amp;action=add&amp;id='.$USER['id'].'">'.$LANG['as_author'].'</a>';
echo ' | ';
if ($USER['ban'] == 0)
echo '<a href="owner.php?iwb=banned&amp;id='.$USER['id'].'">'.$LANG['ban'].'</a>';
else
echo '<a href="owner.php?iwb=unbanned&amp;id='.$USER['id'].'">'.$LANG['unban'].'</a>';
echo ' | ';
echo '<a href="owner.php?iwb=delete_user&amp;id='.$USER['id'].'">'.$LANG['delete_user'].'</a>';
echo '</div>';
}
echo '<table>
    <tr>
<td style="width: 50%;">';
$foto_profile='images/profile/'.$USER['id'].'.jpg';
if (file_exists($foto_profile))
echo '<a href="'.$foto_profile.'"><img src="'.$foto_profile.'" width="80" height="100" alt="" style="width: 100%"/></a>';
else
echo '<img src="images/profile/default.jpg" width="80" height="100" alt="" style="width: 100%"/>';
echo '</td>';

if (($USER['author'] == '1') && ($USER['admin'] == '0'))
{
$font="green";
}

elseif (($USER['author'] == '2') && ($USER['admin'] == '0'))
{
$font="red";
}
elseif (($USER['author'] == '3') && ($USER['admin'] == '0'))
{
$font="blue";
}
elseif (($USER['author'] == '4') && ($USER['admin'] == '0'))
{
$font="yellow";
}
elseif ($USER['admin'] == '1')
{
$font="#731174";
}
else
{
$font="black";
}
echo '<td><strong><font color="'.$font.'">'.htmlspecialchars($USER['name']).'</font></strong></td>';
echo '</tr></table>';
if ($USER['id'] != $user_id)
echo '<form method="get" action="message.php"><input type="hidden" name="iwb" value="write"/><input type="hidden" name="to" value="'.$USER['id'].'"/>
<input class="iwb-button" type="submit" value="'.$LANG['send_message'].'"/></form>';
$view=isset($_GET['view']) ? trim($_GET['view']) : '';
switch ($view)
{
case 'info':
echo '<div id="show_bar"><a href="user.php?id='.$USER['id'].'&amp;view=post">'.$LANG['post'].'</a> | '.$LANG['info'].'</div>';
echo '<h4>'.$LANG['info'].'</h4>
<ol>';
if ($is_admin || $USER['id'] == $user_id)
{
 echo '<li>ID: '.htmlspecialchars($USER['id']).'</li><li>'.$LANG['username'].': '.htmlspecialchars($USER['username']).'</li><li>'.$LANG['credit'].': <a href="admin.php?iwb=credit">Rp '.strrev(wordwrap(strrev($USER['credit']),3,".",true)).'</a></li><li>'.$LANG['email'].': '.htmlspecialchars($USER['email']).'</li>';
if (preg_match("#<#si",$USER['no_hp']))
$myHP = "";
else
$myHP = str_replace("[","",str_replace("]","",$USER['no_hp']));
echo '<li>'.$LANG['phone_number'].': '.htmlspecialchars($myHP).'</li>';
}
echo '<li>'.$LANG['name'].': '.htmlspecialchars($USER['name']).'</li>
    <li>'.$LANG['gender'].': ';
if ($USER['gender'] == 'female')
echo $LANG['female'];
else
echo $LANG['male'];
echo '</li>
<li>'.$LANG['birthday'].': '.htmlspecialchars($USER['birthday']).'</li>
<li>'.$LANG['address'].': '.htmlspecialchars($USER['address']).'</li>
<li>'.$LANG['site'].': <a href="'.htmlentities($USER['site']).'">'.htmlentities($USER['site']).'</a></li><li>'.$LANG['date_register'].': '.waktu($USER['date_reg']).'</li><li>'.$LANG['last_login'].': '.time_ago($USER['lastdate']).'</li><li>'.$LANG['about'].': '.htmlspecialchars($USER['about']).'</li>';
$following=mysql_result(mysql_query("SELECT COUNT(*) AS NUM FROM `following` WHERE `site_id`='".$USER['id']."' AND `url`!='".$USER['site']."'"), 0);
echo '<li>'.$LANG['following'].': '.$following.'</li>';
$follower=mysql_result(mysql_query("SELECT COUNT(*) AS NUM FROM `following` WHERE `site_id`!='".$USER['id']."' AND `url`='".$USER['site']."'"), 0);
echo '<li>'.$LANG['follower'].': <a href="follower.php?id='.$USER['id'].'">'.$follower.'</a></li>';
echo '</ol>';
break;
case 'post':
default:
echo '<div id="show_bar">'.$LANG['post'].' | <a href="user.php?id='.$USER['id'].'&amp;view=info">'.$LANG['info'].'</a></div>';
$totl=mysql_result(mysql_query("select count(*) as num from blog where site_id='".$USER['id']."' and draft='0'"), 0);
$Res=mysql_query("select * from blog where site_id='".$USER['id']."' and draft='0' order by time desc limit 10;");
if ($totl != 0)
{
while ($res=mysql_fetch_array($Res))
{
echo $i % 2 ? '<div class="row0">' : '<div class="row1">';
echo '<a href="'.$USER['site'].'/'.$res['link'].'.xhtml">'.htmlspecialchars($res['title']).'</a><br/>'.time_ago($res['time']).'<br/>';
$total=mysql_result(mysql_query("select count(*) as num from comment where blog_id='".$res['id']."' AND status='1'"), 0);
echo ''.$LANG['comments'].': <a href="'.$USER['site'].'/'.$res['link'].'.xhtml#comments">'.$total.'</a>';
if ($is_admin)
echo ' [<a href="owner.php?iwb=post&amp;action=delete&amp;id='.$res['id'].'">'.$LANG['delete'].'</a>]';
++$i;
echo '</div>';
}
}
else
{
if ($USER['id'] == $user_id)
echo '<p>'.$LANG['empty'].'</p>';
else
echo '<p>'.$LANG['empty'].'</p>';
}
echo '</ol>';
break;
}
echo '</div></div>';
require_once('inc/foot.php');
break;
}
?>