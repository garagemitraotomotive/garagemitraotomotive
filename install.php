<?php


function head()
{
echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>';
echo '<title>Installasi IndoWapBlog</title><meta name="viewport" content="width=320" /><meta name="viewport" content="initial-scale=1.0" /><meta name="viewport" content="user-scalable=false" /><meta http-equiv="Cache-Control" content="max-age=1" /><meta name="HandheldFriendly" content="True" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><link rel="stylesheet" href="mobile.css" type="text/css" media="all"/><link rel="stylesheet" href="mobile.css" type="text/css" media="handheld"/>';
echo '<script type="text/javascript">';
echo '<![CDATA[
function init()
{
// Try to clear attcahed onclick events
var hrefs = document.getElementsByTagName("a");
for (i = 0; i < hrefs.length; i++)
{
if(hrefs[i].className != null)
{
hrefs[i].onclick = null;
}
}
}
//]]></script>';
echo '</head><body onload="javascript:init()">';
}

function foot()
{
echo '<div id="footer">Copyright 2011 - '.date('Y',time()).'<br/>Powered by <a href="http://indowapblog.com">IndoWapBlog.com</a></div></body></html>';
}
$iwb=isset($_GET['iwb']) ? trim($_GET['iwb']) : '';
switch ($iwb)
{
case 'finish':
$db_host=$_POST['db_host'];
$db_user=$_POST['db_user'];
$db_pass=$_POST['db_pass'];
$db_name=$_POST['db_name'];
$st_name=$_POST['st_name'];
$st_url=$_POST['st_url'];
$adm_user=$_POST['adm_user'];
$adm_name=$_POST['adm_name'];
$adm_pass=$_POST['adm_pass'];
$adm_email=$_POST['adm_email'];

if (isset($_POST['finish']))
{
if (!mysql_connect($db_host,$db_user,$db_pass))
{
head();
echo '<div id="header"><h2>Pengaturan Koneksi</h2></div>';
echo '<div id="message"><ol id="error"><li>Tidak dapat terhubung dengan MySQL</li><ol></div>';
echo '<div id="content"><div id="main-content">';
echo '<p>Silakan periksa setelan MySQL</p><p><a href="install.php?iwb=install">Kembali</a></p></div></div>';
foot();
exit;
}
if (!mysql_select_db($db_name))
{
head();
echo '<div id="header"><h2>Pengaturan Koneksi</h2></div>';
echo '<div id="message"><ol id="error"><li>Tidak dapat terhubung dengan Database '.htmlentities($db_name).'</li><ol></div>';
echo '<div id="content"><div id="main-content">';
echo '<p>Silakan periksa setelan MySQL DB Name</p><p><a href="install.php?iwb=install">Kembali</a></p></div></div>';
foot();
exit;
}


if (mb_strlen($adm_email) < 2 || mb_strlen($adm_email) > 250)
$err='Panjang email maksimal 250 karakter';
if (!eregi("^[a-z0-9\._-]+@[a-z0-9\._-]+\.[a-z]{2,4}\$", $adm_email))
$err='Alamat email tidak benar';
if (empty($adm_email))
$err='Silakan masukan alamat email';
if (mb_strlen($adm_name) < 2 || mb_strlen($adm_name) > 40)
$err='Nama minimal 2 dan maksimal 40 karakter';
if (empty($adm_name))
$err='Silakan masukan Nama anda';
if (mb_strlen($adm_pass) < 4 || mb_strlen($adm_pass) > 12) $err='Kata sandi minimal 4 dan maksimal 12 karakter';
if (empty($adm_pass)) $err='Silakan masukan kata sandi';
if (mb_strlen($adm_user) < 4 || mb_strlen($adm_user) > 32)
$err='Username minimal 4 dan maksimal 32 karakter';

if (preg_match("/[^a-z0-9\_\-]/", $adm_user))
$err='Karakter Username hanya diperbolehkan a-z, 0-9, -, _';
if (empty($adm_user))
$err='Silakan masukan Username';

if (empty($err))
{
session_name('IndoWapBlog');
session_start();

mysql_connect($db_host,$db_user,$db_pass);

mysql_select_db($db_name);

$domain = str_replace('http://','',$st_url);
$dbfile = "<?php\r\n\r\n" .
"defined('_IWB_') or die ('Kesalahan: pembatasan akses');\r\n\r\n" .
'$dbhost = ' . "'$db_host';\r\n" .
'$dbname = ' . "'$db_name';\r\n" .
'$dbuser = ' . "'$db_user';\r\n" .
'$dbpass = ' . "'$db_pass';\r\n" .
'$iwb_main_domain = ' . "'$domain';\r\n\r\n" .
'?>';
if (!file_put_contents('inc/config.php', $dbfile))
$error='Tidak dapat membuat file inc/config.php, silakan ubah nama inc/config-sample.php ke inc/config.php dan ubah pengaturan didalamnya';
$handle = fopen("install.sql", "r");
$contents = fread($handle, filesize("install.sql"));
fclose($handle);
$sep=';';
$contents=preg_replace("~(--|##)[^\r]*\r~","\r",$contents);
$contents=explode($sep,$contents);
foreach($contents as $coo) {
if(!empty($coo)) {
mysql_query($coo);
}
}

$st_url_www=str_replace('http://','http://'.$adm_user.'.',$st_url);
mysql_query("INSERT INTO `site` SET name='".mysql_real_escape_string($st_name)."', url='".mysql_real_escape_string($st_url)."', url_www='".mysql_real_escape_string($st_url_www)."', description='My Mobile Blog Powered By IndoWapBlog', keywords='tips, trick, gratisan, xl, indosat, tsel, indowapblog, open source, mobile blog', theme='default', theme_web='desktop', logo='', favicon='favicon.ico', cat_loc='index', display_following='1', comment_email='1', comment_mod='0', comment_captcha='1', num_post_main='10', desc_post_main='0', gmt='+7', category='10'") or die(mysql_error());

$password=md5($adm_pass);
mysql_query("insert into user set username='".mysql_real_escape_string($adm_user)."', password='".$password."', email='".mysql_real_escape_string($adm_email)."', name='".mysql_real_escape_string($adm_name)."', gender='male', site='".mysql_real_escape_string($st_url)."', admin='1', author='1', credit='9999999999', date_reg='".time()."'");

$user_id = mysql_insert_id();
setcookie("user_id", $user_id, time() +60 * 60 * 24 * 30);
setcookie("password", $password, time() +60 * 60 * 24 * 30);

//*Posting Pertama Blog*//
$title='Hallo Dunia';
$teks='Ini adalah postingan pertama Anda, silakan ubah atau hapus postingan ini.';
mysql_query("insert into blog set site_id='".$user_id."', user_id='".$user_id."', title='".mysql_real_escape_string($title)."', description='".mysql_real_escape_string($teks)."', link='hallo-dunia', time='".time()."', category='', allow_comment='1', draft='0'");

$bid=mysql_insert_id();
//*Komentar*//
mysql_query("insert into comment set site_id='".$user_id."', user_id='0', blog_id='".$bid."', blog_user_id='".$user_id."', name='IndoWapBlog.com', site='http://indowapblog.com', email='achunk17@gmail.com', text='Terima kasih telah berkreasi dengan IndoWapBlog.', status='1', time='".time()."'");

head();
echo '<div id="header">';
//*Jangan hapus kode img di bawah ini. Fungsi Kode ini untuk berkoneksi dengan pengguna IWB Lainnya agar Anda dapat saling Follow Memfollow*//

//*Mulai Kode IWB Konek*//
//*Selesai Kode IWB Konek*//

echo '<h2>Installasi IndoWapBlog</h2></div>';
echo '<div id="message"><ol id="success"><li>Istallasi berhasil diselesaikan</li></ol>';
if (!empty($error))
echo '<ol id="error"><li>'.$error.'</li></ol>';
echo '</div>';
echo '<div id="content"><div id="main-content">';
echo '<p>Selamat... Proses installasi berhasil diselesaikan. Kini Anda bisa mengedit pengaturan blog di <a href="admin.php">Admin Panel</a> atau <a href="index.php">Lihat Blog</a> Anda.<br/>Demi keamanan silakan hapus file <b>install.php</b><br/><br/>Salam <a href="http://facebook.com/achunks">Achunk JealousMan</a></p>';
echo '</div></div>';
foot();
mysql_close();
}
else
{
head();
echo '<div id="header"><h2>Installasi IndoWapBlog Beta V01</h2></div>';
echo '<div id="message"><ol id="error"><li>'.$err.'</li></ol></div>';
echo '<div id="content"><div id="main-content">';
echo '<p>Silakan periksa dan perbaiki kesalahan<br/></p><form method="post" action="install.php?iwb=set"><input name="host" type="hidden" value="'.htmlentities($db_host).'" /><input name="user" type="hidden" value="'.htmlentities($db_user).'" /><input name="pass" type="hidden" value="'.htmlentities($db_pass).'" /><input name="db" type="hidden" value="'.htmlentities($db_name).'" /><br/>
<input class="iwb-button" name="set" type="submit" value="Kembali Dan Ulangi"/></form>';
echo '</div></div>';
foot();
}
}
else
{
header('location: install.php?iwb=install');
}
break;

case 'set':
$host=$_POST['host'];
$user=$_POST['user'];
$pass=$_POST['pass'];
$db=$_POST['db'];
if (isset($_POST['set']))
{
if (!mysql_connect($host,$user,$pass))
{
head();
echo '<div id="header"><h2>Pengaturan Koneksi</h2></div>';
echo '<div id="message"><ol id="error"><li>Tidak dapat terhubung dengan MySQL</li><ol></div>';
echo '<div id="content"><div id="main-content">';
echo '<p>Silakan periksa setelan MySQL</p><p><a href="install.php?iwb=install">Kembali</a></p></div></div>';
foot();
exit;
}
if (!mysql_select_db($db))
{
head();
echo '<div id="header"><h2>Pengaturan Koneksi</h2></div>';
echo '<div id="message"><ol id="error"><li>Tidak dapat terhubung dengan Database '.htmlentities($db).'</li><ol></div>';
echo '<div id="content"><div id="main-content">';
echo '<p>Silakan periksa setelan MySQL DB Name</p><p><a href="install.php?iwb=install">Kembali</a></p></div></div>';
foot();
exit;
}

head();
echo '<div id="header"><h2>Installasi IndoWapBlog Beta V01</h2></div>';
echo '<div id="message"><ol id="success"><li>Berhasil Terhubung Dengan MySQL</li></ol></div>';
echo '<div id="content"><div id="main-content">';
echo '<div id="show_bar">Pengaturan Situs</div>';
echo '<p><form method="post" action="install.php?iwb=finish"><input type="hidden" name="db_host" value="'.htmlentities($host).'"><input type="hidden" name="db_user" value="'.htmlentities($user).'"><input type="hidden" name="db_pass" value="'.htmlentities($pass).'"><input type="hidden" name="db_name" value="'.htmlentities($db).'"><h4>Nama Situs</h4>
<input class="iwb-text" name="st_name" type="text" value="IndoWapBlog Mobile Blog" maxlenght="40" size="30"/><br/><h4>URL Situs</h4>
<input class="iwb-text" name="st_url" type="text" value="http://'.$_SERVER['HTTP_HOST'].str_replace('/install.php?iwb=set','',$_SERVER['REQUEST_URI']).'" size="30"/><br/><span>Tanpa diakhiri garis miring (slash).</span></p>';
echo '<div id="show_bar">Registrasi Administrator</div>';
echo '<p><h4>Nama Pengguna</h4>
<input class="iwb-text" name="adm_user" type="text" value="admin" maxlenght="32" size="30"/><br/><h4>Nama Anda</h4>
<input class="iwb-text" name="adm_name" type="text" value="Administrator" maxlenght="40" size="30"/><br/><h4>Kata Sandi</h4>
<input class="iwb-text" name="adm_pass" type="text" value="" maxlenght="32" size="30"/><br/><h4>Email</h4>
<input class="iwb-text" name="adm_email" type="text" value="@" size="30"/></p><input class="iwb-button" name="finish" type="submit" value="Install Sekarang"/></form>';
echo '</div></div>';
foot();
}
else
{
header('location: install.php?iwb=install');
}
break;

case 'install':
head();
echo '<div id="header"><h2>Pengaturan Koneksi</h2></div>';
echo '<div id="message">';
echo '</div>';
echo '<div id="content"><div id="main-content">';
echo '<form method="post" action="install.php?iwb=set"><h4>MySQL Host</h4>
<input class="iwb-text" name="host" type="text" value="localhost" size="30"/><br/><h4>MySQL User</h4>
<input class="iwb-text" name="user" type="text" value="root" size="30"/><br/><h4>MySQL Password</h4>
<input class="iwb-text" name="pass" type="text" value="" size="30"/><br/><h4>MySQL DB Name</h4>
<input class="iwb-text" name="db" type="text" value="" size="30"/><br/>
<input class="iwb-button" name="set" type="submit" value="Selanjutnya"/></form>';
echo '</div></div>';
foot();
break;

default:
head();
if (!file_exists('inc/courbd.ttf'))
copy('http://indowapblog.googlecode.com/files/courbd.ttf','inc/courbd.ttf');
echo '<div id="header"><h2>Installasi IndoWapBlog Beta V01</h2></div>';
echo '<div id="message">';
echo '</div>';
echo '<div id="content"><div id="main-content">';
echo '<div id="show_bar">Syarat Dan Ketentuan</div>';
echo '<p>Dengan melakukan istallasi IndoWapBlog (IWB) ( IndoWapBlog-MS-Beta-V01 Full Editing By : Master Chef IWB
Website : http://cuplascuplis.jw.lt ) Berarti Anda telah setuju dengan syarat dan ketentuan yang ditetapkan oleh pembuat IWB (<a href="http://m.facebook.com/achunks">Achunk JealousMan</a>), ada pun ketetentuan dan syarat tersebut adalah sebagai berikut:<br/><ul><li>Dilarang menghapus Copyright IndoWapBlog</li><li>Dilarang memperjual belikan Engine atau Modul IndoWapBlog</li><li>Segala isi atau konten pada blog ini adalah tanggung jawab Anda</li></ul><br/>Apabila Anda setuju dengan Syarat dan Ketentuan di atas silakan lanjutkan installasi dengan mengklik &quot;Mulai Installasi&quot; di bawah ini<br/><form method="get" action="install.php"><input type="hidden" name="iwb" value="install"/><input class="iwb-button" type="submit" value="Mulai Installasi"/></form></p></div></div>';
foot();
break;
}
?>