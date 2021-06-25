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
if (!$user_id)
relogin();
function jawaban($var)
{
$var=preg_replace('#([\W_]+)#',' ',$var);
$var=str_replace(' ','',$var);
return strtolower($var);
}
$iwb=isset($_GET['iwb']) ? trim($_GET['iwb']) : '';
switch ($iwb)
{
case 'money_back':
$head_title=$LANG['credit_back'];
require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content"><div id="show_bar"><a href="kuis.php">'.$LANG['create_quiz'].'</a> | <a href="kuis.php?iwb=kuisku">'.$LANG['my_quiz'].'</a></div>';
$t=mysql_fetch_array(mysql_query("select sum(hadiah) as total from kuis where bandar='".$user_id."' and status='1' and time < '".time()."'"));
if ($t['total'] > 100)
{
$kr = $indowapblog['credit'] + $t['total'];
mysql_query("UPDATE user SET credit='".$kr."' WHERE id='".$user_id."'");
mysql_query("update kuis set status='0' where bandar='".$user_id."' and status='1' and time < '".time()."'") or die(mysql_error());
echo '<p>'.str_replace('::number::',strrev(wordwrap(strrev($t['total']),3,".",true)),$LANG['credit_was_back']).'</p>';
}
else
{
echo '<p>'.$LANG['credit_not_back'].'</p>';
}
echo '</div></div>';
require_once('inc/foot.php');
break;

case 'kuisku':
if (isset($_GET['delete']))
{
mysql_query("DELETE FROM kuis WHERE bandar='".$user_id."' AND (status='2' OR time < '".time()."')");
}
$head_title=$LANG['my_quiz'];
require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content"><div id="show_bar"><a href="kuis.php">'.$LANG['create_quiz'].'</a> | '.$LANG['my_quiz'].'</div>';
$cek=mysql_query("SELECT * FROM kuis WHERE bandar='".$user_id."' ORDER BY id DESC");
if (mysql_num_rows($cek) == 0)
{
echo '<p>'.$LANG['quiz'].' '.$LANG['empty'].'</p>';
}
else
{
echo '<ol>';
while ($k=mysql_fetch_array($cek))
{
echo $i % 2 ? '<li class="row0">' : '<li class="row1">';
echo '<b>'.$LANG['question'].':</b> '.bbsm($k['pertanyaan']).'<br /><b>'.$LANG['answer'].':</b> '.htmlspecialchars($k['jawaban']).' ('.jawaban($k['jawaban']).')<br /><b>'.$LANG['hadiah'].':</b> Rp '.strrev(wordwrap(strrev($k['hadiah']),3,".",true)).'<br /><b>'.$LANG['expired'].':</b> ';
if ($k['time'] < time())
echo '<font color="red">'.waktu($k['time']).'</font>';
else
echo waktu($k['time']);
echo '<br /><b>Status:</b> ';
if ($k['status'] == 2)
{
$winner=mysql_fetch_array(mysql_query("select user_id from kuis_jawaban where kuis_id='".$k['id']."' and win='1'"));
echo str_replace('::name::',iwbid($winner['user_id']),$LANG['quiz_winer']);
}
else
{
echo $LANG['not_answer'];
}
echo '<br /><a href="kuis.php?iwb=read&amp;id='.$k['id'].'">'.$LANG['more'].' &raquo;</a>';
++$i;
echo '</li>';
}
echo '</ol>';
echo '<center><a href="kuis.php?iwb=kuisku&amp;delete=yes">'.$LANG['delete_all'].'</a></center><p>'.str_replace('::click here::','<a href="kuis.php?iwb=money_back">'.$LANG['click_here'].'</a>',$LANG['credit_back_info']).'</p>';
}
echo '</div></div>';
require_once('inc/foot.php');
break;

case 'read':
$head_title=$LANG['quiz'];
$head_description='IWB Kuis berhadiah Kreadit';
$id=angka($_GET['id']);

$cek=mysql_query("SELECT * FROM kuis WHERE id='".mysql_real_escape_string($id)."'");
if (mysql_num_rows($cek) == 0)
{
require_once('inc/head.php');
page_not_found();
require_once('inc/foot.php');
}
else
{
$k=mysql_fetch_array($cek);
if (isset($_POST['jawab']))
{
$jawaban=$_POST['jawaban'];
if ($k['time'] < time())
$err=$LANG['quiz_expired'];
if (empty($jawaban))
$err=$LANG['empty_text'];
$cekjw=mysql_query("SELECT * FROM kuis_jawaban WHERE kuis_id='".$k['id']."' AND user_id='".$user_id."'");
if (mysql_num_rows($cekjw) != 0)
$err=$LANG['cannot_answer_againt'];
if ($k['status'] == 2)
$err=$LANG['quiz_was_answered'];
if (empty($err))
{
if (jawaban($k['jawaban']) == jawaban($jawaban))
{
$win = $indowapblog['credit'] + $k['hadiah'];
mysql_query("UPDATE user SET credit='".$win."' WHERE id='".$user_id."'");
mysql_query("UPDATE kuis SET status='2' WHERE id='".$k['id']."'");
mysql_query("INSERT INTO kuis_jawaban SET kuis_id='".$k['id']."', user_id='".$user_id."', text='".mysql_real_escape_string($jawaban)."', win='1', time='".time()."'");
$hasil='<div id="message"><ol id="success"><li>'.bbsm($k['success']).'</li></ol></div>';
}
else
{
mysql_query("INSERT INTO kuis_jawaban SET kuis_id='".$k['id']."', user_id='".$user_id."', text='".mysql_real_escape_string($jawaban)."', win='0', time='".time()."'");
$hasil='<div id="message"><ol id="notice"><li>'.bbsm($k['error']).'</li></ol></div>';
}
}
else
{
$hasil='<div id="message"><ol id="error"><li>'.$err.'</li></ol></div>';
}
}
require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content"><div id="show_bar"><a href="kuis.php">'.$LANG['create_quiz'].'</a> | <a href="kuis.php?iwb=kuisku">'.$LANG['my_quiz'].'</a> | '.$LANG['quiz'].'</div>';
echo '<div class="row1"><a href="'.$site['url'].'/user.php?id='.$k['bandar'].'">';
$usr=mysql_fetch_array(mysql_query("select name, author, admin from user where id='".$k['bandar']."'"));
if (($usr['author'] == '1') && ($usr['admin'] == '0'))
{
echo '<font color="green">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '2') && ($usr['admin'] == '0'))
{
echo '<font color="red">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '3') && ($usr['admin'] == '0'))
{
echo '<font color="blue">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '4') && ($usr['admin'] == '0'))
{
echo '<font color="orange">'.htmlspecialchars($usr['name']).'</font>';
}
elseif ($usr['admin'] == '1')
{
echo '<font color="#731174">'.htmlspecialchars($usr['name']).'</font>';
}
else
{
echo '<font color="black">'.htmlspecialchars($usr['name']).'</font>';
}
echo '</a>: '.bbsm($k['pertanyaan']).'<br />(@Rp '.strrev(wordwrap(strrev($k['hadiah']),3,".",true)).' s/d: '.waktu($k['time']).')';
echo '<br /><b>Info:</b> ';
if ($k['time'] < time())
{
echo $LANG['quiz_expired'];
}
else
{
}
if (($k['time'] < time()) || ($k['status'] == 2))
echo '<br />'.$LANG['answer'].': <b>'.htmlspecialchars($k['jawaban']).'</b> ('.jawaban($k['jawaban']).')';

if ($k['status'] == 2)
{
$winner=mysql_fetch_array(mysql_query("select user_id from kuis_jawaban where kuis_id='".$k['id']."' and win='1'"));
echo '<br />'.str_replace('::name::',iwbid($winner['user_id']),$LANG['quiz_winer']);
}
else
{
echo '<br />'.$LANG['not_answer'];
}
echo '</div>';
echo '<hr></hr><div id="show_bar">'.$LANG['your_answer'].'</div>';

$jwbn=mysql_query("SELECT * FROM kuis_jawaban WHERE kuis_id='".$k['id']."' ORDER BY time ASC");
echo '<ol>';
if (mysql_num_rows($jwbn) != 0)
{
while ($res=mysql_fetch_array($jwbn))
{
echo $i % 2 ? '<li class="row0">' : '<li class="row1">';
$usr=mysql_fetch_array(mysql_query("select name, author, admin from user where id='".$res['user_id']."'"));
echo '<a href="user.php?id='.$res['user_id'].'">';
if (($usr['author'] == '1') && ($usr['admin'] == '0'))
{
echo '<font color="green">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '2') && ($usr['admin'] == '0'))
{
echo '<font color="red">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '3') && ($usr['admin'] == '0'))
{
echo '<font color="blue">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '4') && ($usr['admin'] == '0'))
{
echo '<font color="orange">'.htmlspecialchars($usr['name']).'</font>';
}
elseif ($usr['admin'] == '1')
{
echo '<font color="#731174">'.htmlspecialchars($usr['name']).'</font>';
}
else
{
echo '<font color="black">'.htmlspecialchars($usr['name']).'</font>';
}
echo '</a>: '.bbsm($res['text']).'<br/><span color="#666666">'.waktu($res['time']).'</span>';
++$i;
echo '</li>';
}
}
else
{
echo '<li class="row1">'.$LANG['empty'].'</li>';
}
if (!empty($hasil))
echo $hasil;
if ($k['time'] > time())
{
echo '<h2 id="jawab">'.$LANG['answer_question'].'</h2><form method="post" action="kuis.php?iwb=read&amp;id='.$k['id'].'#jawab"><textarea name="jawaban"></textarea><br /><input class="iwb-button" type="submit" name="jawab" value="'.$LANG['send'].'"/></form>';
}
else
{
}
echo '</ol>';
echo '</div></div>';
require_once('inc/foot.php');
}
break;
default:
$head_title=$LANG['quiz'];
if (isset($_POST['create']))
{
$pertanyaan=$_POST['pertanyaan'];
$jawaban=$_POST['jawaban'];
$hadiah=$_POST['hadiah'];
$durasi=$_POST['durasi'];
$benar=$_POST['success'];
$salah=$_POST['error'];
$kode=$_POST['code'];
if (mb_strlen($pertanyaan) < 3 || mb_strlen($pertanyaan) > 1024)
$err .='<li>Pertanyaan minimal 3 dan maksimal 1024 karakter</li>';
if (mb_strlen($jawaban) < 1 || mb_strlen($jawaban) > 100)
$err .='<li>Jawaban minimal 1 dan maksimal 100 karakter</li>';
if ($indowapblog['credit'] < $hadiah)
$err .='<li>Kredit tidak mencukupi</li>';
if ($indowapblog['credit'] < 2000)
$err .='<li>'.str_replace('::number::','2.000',$LANG['minim_credit']).'</li>';
if (!ctype_digit($hadiah) || !ctype_digit($durasi))
$err .='<li>'.$LANG['incorrect_data'].'</li>';
if (empty($hadiah))
$err .='<li>'.$LANG['incorrect_data'].'</li>';
if ($hadiah < 100)
$err .='<li>Hadiah minimal Rp 100</li>';
if ($durasi < 2 || $durasi > 10)
$err .='<li>Durasi 2 s/d 10 menit</li>';
if ($_SESSION['captcha_code'] != $kode)
$err .='<li>'.$LANG['incorrect_security_code'].'</li>';
if (empty($err))
{
$waktu = time() + ($durasi * 60);
$ambil = $indowapblog['credit'] - $hadiah;
mysql_query("INSERT INTO kuis SET bandar='".$user_id."', pertanyaan='".mysql_real_escape_string($pertanyaan)."', jawaban='".mysql_real_escape_string($jawaban)."', hadiah='".mysql_real_escape_string($hadiah)."', success='".mysql_real_escape_string($benar)."', error='".mysql_real_escape_string($salah)."', status='1', time='".$waktu."'");
mysql_query("UPDATE user SET credit='".$ambil."' WHERE id='".$user_id."'");
header("Location: kuis.php?iwb=kuisku");
exit;
}
else
{
$hasil='<ol id="error">'.$err.'</ol>';
}
}
require_once('inc/head.php');
echo '<div id="message">';
if (!empty($hasil))
echo $hasil;
echo '</div><div id="content"><div id="main-content"><div id="show_bar">'.$LANG['create_quiz'].' | <a href="kuis.php?iwb=kuisku">'.$LANG['my_quiz'].'</a></div>';
echo '<p>IWB Kuis berhadiah Kredit IWB.<br /><b>Syarat Dan Ketentuan Menjadi Bandar</b><br /><ol>
<li>* Sisa Kredit lebih dari Rp 2.000</li>
<li>* Pertanyaan harus disertai jawaban yang benar</li>';
echo "<li>* Jawaban hanya diambil Abjad dan Angka Saja. Misal jawaban yang Anda buat adalah <b>Do'a Ke-2</b> lalu seseorang menjawab dengan kalimat <b>doA&nbsp;&nbsp;&nbsp;&nbsp;ke 2!</b> maka jawaban tersebut dibenarkan karena kedua kalimat tersebut menggunakan huruf d,o,a,k,e dan angka 2. Jadi kedua kalimat tersebut dihilangkan karakter yang bukan abjan atau angka dan karakter abjad akan menjadi menjadi huruf kecil semua. kata di atas setelah disaring menjadi <b>doake2</b>.</li>";
echo '<li>* Hadiah kuis minimal Rp 100</li>
<li>* Durasi kuis 2 s/d 10 menit setelah Anda membuat pertannyaan.</li>
<li>* Kredit langsung dipotong sebesar jumlah hadiah yang Anda tulis. Jika kuis Anda tidak ada yang menjawab atau jawaban salah dalam kurun waktu durasi kuis maka kuis tersebut tidak berlaku lagi dan kredit Anda akan dikembalikan, namun pengembalian kredit dilakukan dengan cara manual. Ada pun cara pengembaliannya silakan <a href="kuis.php?iwb=money_back">KLIK DI SINI</a></li></ol>';
echo '<br />Sisa kredit Anda saat ini adalah <b>Rp '.strrev(wordwrap(strrev($indowapblog['credit']),3,".",true)).'</b><br />';
if ($indowapblog['credit'] < 2000)
{
echo 'Anda tidak bisa menjadi Bandar karena kredit Anda tidak mencukupi.<br />Jika ingin menjadi Bandar silakan <a href="admin.php?iwb=credit">Top Up Kredit</a> Anda terlebih dahulu';
}
else
{
echo '<h1>'.$LANG['create_quiz'].'</h1><form method="post" action="kuis.php?"><b>'.$LANG['question'].'</b><br /><input class="iwb-text" type="text" name="pertanyaan" value=""/><br /><span>Mimimal 3 dan Maksimal 1024 karakter</span><br /><b>'.$LANG['answer'].'</b><br /><input class="iwb-text" type="text" name="jawaban" value=""/><br /><span>Minimal 1 dan Maksimal 100 karakter</span><br /><b>'.$LANG['hadiah'].' (Rp)</b><br /><input class="iwb-text" type="text" name="hadiah" value=""/><span>Hanya angka saja yang diperbolehkan</span><br /><b>Durasi</b><br /><select class="iwb-select" name="durasi"><option value="2">2 Menit</option><option value="3">3 Menit</option><option value="4">4 Menit</option><option value="5">5 Menit</option><option value="6">6 Menit</option><option value="7">7 Menit</option><option value="8">8 Menit</option><option value="9">9 Menit</option><option value="10">10 Menit</option>
</select><br /><b>Pesan Jawaban Benar</b><br /><textarea name="success">:juara1: Selamat Kamu sudah berhasil menjawab pertanyaan dengan benar.</textarea><br /><b>Pesan Jawaban Salah</b><br /><textarea name="error">Sepertinya Kamu belum beruntung. Jawaban Kamu tidak benar.</textarea><br />';
$_SESSION['captcha_code'] = strval(rand(1000, 9999));
echo '<b>'.$LANG['security_code'].':</b><br /><img src="captcha.php" alt=""/><br /><input class="iwb-text" type="text" name="code" value=""/><br /><br /><center><input class="iwb-button" type="submit" name="create" value="'.$LANG['save'].'"/></center></form>';
}
echo '</p></div></div>';
require_once('inc/foot.php');
break;
}
?>