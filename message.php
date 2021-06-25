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
case 'write':
if (!$user_id)
relogin();
$to=isset($_GET['to']) ? trim(angka($_GET['to'])) : angka($_POST['to']);
$msg=$_POST['msg'];
$req=mysql_query("select * from `user` where `id`='".mysql_real_escape_string($to)."' and `username`!='".mysql_real_escape_string($user_username)."' and `ban`='0'");

if (isset($_POST['send']))
{
if (mysql_num_rows($req) == 0)
$hsl='<ol id="error"><li>'.$LANG['receiver_not_found'].'</li></ol>';
if (mb_strlen($msg) > 500)
$hsl='<ol id="error"><li>'.str_replace('::number::','500',$LANG['text_max']).'</li></ol>';
elseif (empty($msg))
$hsl='<ol id="error"><li>'.$LANG['empty_text'].'</li></ol>';
if (empty($hsl))
{
mysql_query("insert into `pm` set `receiver_id`='".mysql_real_escape_string($to)."', `sender_id`='".$user_id."', `name`='".mysql_real_escape_string($user_name)."', `email`='".mysql_real_escape_string($user_email)."', `text`='".mysql_real_escape_string($msg)."', `read`='1', `time`='".time()."'") or die(mysql_error());
header('location: message.php?send_successfully');
exit;
}
}
$head_title=$LANG['write_message'];
require_once('inc/head.php');
echo '<div id="message">';
if (!empty($hsl))
echo $hsl;
echo '</div>
<div id="content">
<div id="main-content">';
echo '<div id="show_bar"><a href="message.php">'.$LANG['inbox'].'</a> | '.$LANG['write_message'].'</div>';

echo '<form action="message.php?iwb=write" method="post">';
echo '<h4>'.$LANG['to'].'</h4>';
if (mysql_num_rows($req) == 0)
{
echo '<div class="two-col-btn">
<select class="iwb-
select" name="to"><option value="">--pilih--</option>';
$total=mysql_result(mysql_query("select count(*) as num from `following` where `site_id`='".mysql_real_escape_string($user_id)."'"), 0);
if ($total > 0)
{
$res=mysql_query("select `url` from `following` where `site_id`='".mysql_real_escape_string($user_id)."' order by `time` desc");
while ($ke=mysql_fetch_array($res))
{
$us=mysql_fetch_array(mysql_query("select id, name from user where site='".$ke['url']."'"));
echo '<option value="'.$us['id'].'">'.htmlspecialchars($us['name']).'</option>';
}
}
echo '</select></div>';
}

else
{
$untuk=mysql_fetch_array($req);
echo '<a href="user.php?id='.$untuk['id'].'">'.htmlspecialchars($untuk['name']).'</a><input type="hidden" name="to" value="'.$untuk['id'].'">';
}
echo '<h4>'.$LANG['message'].'</h4>
<textarea class="iwb-textarea" name="msg" rows="5" cols="30"></textarea><br/>
<input class="iwb-button" name="send" type="submit" value="'.$LANG['send'].'"/></form>';
echo '</div></div>';
require_once('inc/foot.php');


break;
case 'delete':
$id=angka($_GET['id']);
if (!$user_id)
relogin();
$cek=mysql_query("select * from `pm` where `id`='".mysql_real_escape_string($id)."'");
if (mysql_num_rows($cek) == 0)
{
require_once('inc/head.php');
page_not_found();
require_once('inc/foot.php');
exit;
}
$pm=mysql_fetch_array($cek);
if ($pm['receiver_id'] != $user_id)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
if (isset($_GET['yes']))
{
mysql_query("delete from `pm` where `id`='".mysql_real_escape_string($pm['id'])."'");
header('location: message.php?deleted_successfully');
}
$head_title='Hapus Pesan';
require_once('inc/head.php');

echo '<div id="message"></div><div id="content"><div id="main-content">'.$LANG['delete_confirm'].'<br/>[<a href="message.php?iwb=delete&amp;id='.$pm['id'].'&amp;yes=ok">'.$LANG['yes'].'</a>] [<a href="message.php">'.$LANG['no'].'</a>]</div></div>';
require_once('inc/foot.php');

break;
case 'read':
$id=angka($_GET['id']);
if (!$user_id)
relogin();
$cek=mysql_query("select * from `pm` where `id`='".mysql_real_escape_string($id)."'");
if (mysql_num_rows($cek) == 0)
{
require_once('inc/head.php');
page_not_found();
require_once('inc/foot.php');
exit;
}
$pm=mysql_fetch_array($cek);
if ($pm['receiver_id'] != $user_id)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}

if (isset($_POST['button']))
{
if (!$user_id)
relogin();
if ($pm['receiver_id'] != $user_id)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
$reply=$_POST['reply'];
if (empty($reply))
$hsl='<ol id="error"><li>'.$LANG['empty_text'].'</li></ol>';
$_flood = ($is_admin) ? 30 : 80;
$flooding = time() - $_flood;
$isflood=mysql_query("select id from `pm` where `sender_id`='".$user_id."' and time > $flooding");
if (mysql_num_rows($isflood) != 0)
{
$hsl='<ol id="error"><li>'.$LANG['flooding'].'</li></ol>';
}
if (empty($hsl) && ($pm['sender_id'] != 0))
{
mysql_query("insert into `pm` set `receiver_id`='".$pm['sender_id']."', `sender_id`='".$user_id."', `name`='".mysql_real_escape_string($user_name)."', `email`='".mysql_real_escape_string($user_email)."', `text`='".mysql_real_escape_string($reply)."', `read`='1', `time`='".time()."'") or die(mysql_error());
header('location: message.php?send_successfully');
exit;
}
if (empty($hsl) && ($pm['sender_id'] == 0))
{
$email = $pm['email'];
$subject="RE: FeedBack From ".htmlspecialchars($site['name'])."";$pesan="Kami telah membaca Feedback Anda yang berisi\r\n\r\n";
$pesan .= "\r\n\r\n";
$pesan .= htmlspecialchars($pm['text']);
$pesan .= "\r\n\r\n";
$pesan .= "\r\n\r\nBerikut adalah balasan dari Kami\r\n\r\n";
$pesan .= "\r\n\r\n";
$pesan .= htmlspecialchars($reply);$pesan .= "\r\n\r\n";

$pesan .= "\r\nTerima Kasih\r\n";
$pesan .= htmlspecialchars($site['name']);
$pesan .= "\r\n\r\n";
$pesan .= $site['url'];
$dari = "From: ".htmlspecialchars($site['name'])." <".$user_email.">\r\n";$dari .= "X-sender: ".htmlspecialchars($site['name'])." <".$user_email.">\r\n";$dari .= "Content-type:text/plain; charset=iso-8859-1\r\n";$dari .= "MIME-Version: 1.0\r\n";$dari .= "Content-Transfer-Encoding: 8bit\r\n";$dari .= "X-Mailer: PHP v.".phpversion();mail($email,$subject,$pesan,$dari);
header('location: message.php?send_successfully');
exit;
}
}
$head_title=$LANG['private_message'];
require_once('inc/head.php');
echo '<div id="message">';
if (!empty($hsl))
echo $hsl;
echo '</div>';
echo '<div id="content">
<div id="main-content">';
echo '<div id="show_bar"><a href="message.php">'.$LANG['inbox'].'</a> | <a href="message.php?iwb=write">'.$LANG['write_message'].'</a></div>';
$page=$_GET['page'];
$total=mysql_result(mysql_query("select count(*) as num from pm where receiver_id='".$user_id."' and sender_id='".$pm['sender_id']."' or receiver_id='".$pm['sender_id']."' and sender_id='".$user_id."'"), 0);
$gp = ceil($total / $site['num_post_main']);

if (!ctype_digit($page) || empty($page))
{
$page=$gp;
}
if ($page == 0 || $page > $gp)
$page='1';
$page--;
$max_view=$site['num_post_main'];
$limit=$page*$max_view;
$page++;
$resid=mysql_query("select * from pm where receiver_id='".$user_id."' and sender_id='".$pm['sender_id']."' or receiver_id='".$pm['sender_id']."' and sender_id='".$user_id."' order by time asc limit $limit,$max_view");
while ($chatmess=mysql_fetch_array($resid))
{
echo $i % 2 ? '<div class="row0">' : '<div class="row1">';
if ($chatmess['sender_id'] != 0)
{
$by=mysql_fetch_array(mysql_query("select name, author, admin from user where id='".$chatmess['sender_id']."'"));
if ($by['author'] == 0)
$font='black';
if ($by['author'] == 1 && $by['admin'] == 0)
$font='green';
if ($by['author'] == 2 && $by['admin'] == 0)
$font='red';
if ($by['author'] == 3 && $by['admin'] == 0)
$font='blue';
if ($by['author'] == 4 && $by['admin'] == 0)
$font='orange';
if ($by['admin'] > 0)
$font='#731174';
echo '<a href="'.$site['url'].'/user.php?id='.$chatmess['sender_id'].'"><b><font color="'.$font.'">'.htmlspecialchars($by['name']).'</font></b></a> : ';
}
else
{
echo '<b>'.htmlspecialchars($chatmess['name']).'</b> ('.htmlspecialchars($chatmess['email']).') : ';
}
echo '<span>'.bbsm($chatmess['text']).'</span><br /><small>'.waktu($chatmess['time']).'</small>';
if ($chatmess['receiver_id'] == $user_id && $chatmess['read'] != 0)
{
mysql_query("update `pm` set `read`='0' where `id`='".$chatmess['id']."'");
}
++$i;
echo '</div>';
}
$link='message.php?iwb=read&amp;id='.$pm['id'].'&amp;page=';
$q='';
echo '<ul>';
pagination($page,$max_view,$total,$link,$q);
echo '</ul>';
if ($pm['sender_id'] != $user_id)
{
echo '<p><form method="post" action="message.php?iwb=read&amp;id='.$pm['id'].'"><textarea class="iwb-textarea" rows="5" cols="30" name="reply"/></textarea><br/><input class="iwb-button" type="submit" name="button" value="'.$LANG['reply'].'"/>
</form></p>';
}

echo '</div></div>';
require_once('inc/foot.php');
break;

default:
$page=angka($_GET['page']);
$total=mysql_result(mysql_query("select count(*) as Num from `pm` where `receiver_id`='".$user_id."'"), 0);
$pages = ceil($total / $site['num_post_main']);
if (!ctype_digit($page) || empty($page) || $page == 0 || $page > $pages)
$page='1';
$page--;
$max_view=$site['num_post_main'];
$limit=$page*$max_view;$page++;
if (!$user_id)
relogin();
if (isset($_GET['send_successfully']))
$notif=$LANG['message_successfully_sent'];
if (isset($_GET['deleted_successfully']))
$notif=$LANG['message_successfully_deleted'];
$head_title=$LANG['message'];
require_once('inc/head.php');
echo '<div id="message">';
if (!empty($notif))
echo '<ol id="success"><li>'.$notif.'</li></ol>';
echo '</div>';
echo '<div id="content">
<div id="main-content">';
echo '<div id="show_bar">'.$LANG['inbox'].' | <a href="message.php?iwb=write">'.$LANG['write_message'].'</a></div>';
echo '<ol>';
$pm=mysql_query("select * from `pm` where `receiver_id`='".$user_id."' order by `time` desc limit $limit,$max_view");
if ($total == 0)
{
echo '<li>'.$LANG['empty'].'</li>';
}
else
{
while ($pms=mysql_fetch_array($pm))
{
echo $i % 2 ? '<li class="row0">' : '<li class="row1">';
echo '<a href="message.php?iwb=read&amp;id='.$pms['id'].'">';
if ($pms['read'] == 1)
echo '<font color="blue">'.htmlspecialchars($pms['name']).'</font>';
else
echo '<font color="#666666">'.htmlspecialchars($pms['name']).'</font>';
echo '</a> [<a href="message.php?iwb=delete&amp;id='.$pms['id'].'&amp;yes=ok"><font color="red">'.$LANG['delete'].'</font></a>]<br/><span>'.$LANG['sent'].': '.waktu($pms['time']).'</span>';
$sames=$pms['sender_id'];
++$i;
echo '</li>';
}
}
echo '</ol></div>';
$link='message.php?page=';
$q='';
pagination($page,$max_view,$total,$link,$q);
echo '</div>';
require_once('inc/foot.php');
}
?>