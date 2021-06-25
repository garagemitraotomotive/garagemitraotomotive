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
case 'bbcode':
$head_title='BBCode';
require_once('inc/head.php');
echo '<div id="message">';
echo '</div>
<div id="content">
<div id="main-content">';
echo '<div id="show_bar"><a href="bbsm.php?iwb=smiley">Smiley</a> | BBCode</div><ol>';

echo '<li class="row0"><b>Kode:</b> [color=blue]Warna Biru[/color]<br/><b>Hasil:</b> '.bbsm('[color=blue]Warna Biru[/color]').'</li>';

echo '<li class="row1"><b>Kode:</b> [b]Teks Tebal[/b]<br/><b>Hasil:</b> '.bbsm('[b]Teks Tebal[/b]').'</li>';

echo '<li class="row0"><b>Kode:</b> [i]Teks Miring[/i]<br/><b>Hasil:</b> '.bbsm('[i]Teks Miring[/i]').'</li>';

echo '<li class="row1"><b>Kode:</b> [u]Teks Garis Bawah[/u]<br/><b>Hasil:</b> '.bbsm('[u]Teks Garis Bawah[/u]').'</li>';
echo '</ol></div></div>';
require_once('inc/foot.php');
break;
case 'delete':
if (!$user_id)
relogin();
if ($is_admin) {
$file = $_GET['file'];
if (file_exists("images/smiley/".$file)) {
unlink("images/smiley/".$file);
header("Location: bbsm.php");
}
else {
include('inc/head.php');
page_not_found();
include('inc/foot.php');
}
}
else {
include('inc/head.php');
forbidden();
include('inc/foot.php');
}
break;
case 'smiley':
default:

$maxsize='5000';
$filename=strtolower($_FILES['file']['name']);

if (isset($_POST['upload']))
{
if (!$is_admin)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
$types = array("image/jpeg", "image/jpg","image/gif","image/x-png");
if (!in_array(mime_content_type($_FILES['file']['tmp_name']), $types))
$hsl='<ol id="error"><li>Jenis file tidak diijinkan</li></ol>';
if ($_FILES['file']['size'] > (1024*$maxsize))
$hsl='<ol id="error"><li>File maksimal berukuran 5Mb</li></ol>';
$newName=substr($filename,0,-4);
if (empty($filename))
$hsl='<ol id="error"><li>Silakan pilih file</li></ol>';

if (empty($hsl))
{
copy($_FILES['file']['tmp_name'], "images/smiley/$newName.gif");
$hsl='<ol id="success"><li><b>'.$newName.'</b> berhasil diupload</li></ol>';
}
}
$head_title='Smileys';
require_once('inc/head.php');
echo '<div id="message">';
if (!empty($hsl))
echo $hsl;
echo '</div>
<div id="content">
<div id="main-content">';
echo '<div id="show_bar">Smiley | <a href="bbsm.php?iwb=bbcode">BBCode</a></div>';

if ($is_admin)
{
echo '<form action="bbsm.php?iwb=smiley" method="post" enctype="multipart/form-data">
    <input name="MAX_FILE_SIZE" value="5242880" type="hidden"/>
    <h4>Pilih File</h4>
<input type="file" name="file"/>
    <input class="iwb-button" name="upload" type="submit" value="Upload"/></form>';
}
if (($is_admin) && ($_GET['action'] == 'update'))
{
$arr=array(".gif",".jpg",".png");
foreach (glob("images/smiley/*") as $file)
{
$files=basename($file);
$fl[] =$files;
$nm=substr($files,-4);
$name=strtolower(substr($files,0,-4));
if (in_array($nm, $arr))
{
$item='\':'.$name.':\' => \'<img src="'.$site_url.'/images/smiley/'.$files.'" alt="'.$name.'"/>\',';
$arrey .= "".$item."\r\n";
$key=':'.$name.':';
$val='<img src="'.$site_url.'/images/smiley/'.$files.'" alt="'.$name.'"/>';
}
}
$arrey .='\':iwb:\' => \'<b>IndoWapBlog</b>\'';
$arreys = "<?php\r\n\r\ndefined('_IWB_') or die('Akses Terlarang');\r\n".'$sm_code'." = array($arrey);\r\n?>";
if (file_put_contents("inc/smileys.php", $arreys)) {
$total=count($fl);
echo '<p>Telah diperbarui sebanyak '.$total.' smileys.<br/><a href="bbsm.php?iwb=smiley">Kembali</a></p>';
}
else {

echo '<p>Gagal mengupdate Smileys!.<br/><a href="bbsm.php?iwb=smiley">Kembali</a></p>';
}
}
else
{
$page = $_GET['page'];
$files = glob("images/smiley/*");
$total = count($files);
if (!ctype_digit($page) || empty($page) || $page == 0 || $page > (ceil($total / 10)))
$page = 1;
$page--;
$min = ($page * 10) + 1;
$page++;
$max = $min + 9;
$arr = array(".gif",".jpg",".png");
$i = 1;
foreach ($files as $file)
{
$ext = substr(basename($file),-4);
if ($i >= $min && $i <= $max && in_array($ext, $arr)) {
echo $div % 2 ? '<div class="row1">' : '<div class="row0">';
echo '<img src="'.$file.'" alt="'.substr(basename($file), 0, -4).'"/><br/><b>:'.substr(basename($file),0,-4).':</b>';
if ($is_admin)
echo ' <a href="bbsm.php?iwb=delete&file='.basename($file).'">'.$LANG['delete'].'</a>';
++$div;
echo '</div>';
}
else {
}
$i++;
}

if ($is_admin)
echo '<p><form method="get" action="bbsm.php"><input type="hidden" name="iwb" value="smiley"/><input type="hidden" name="action" value="update"/><input class="iwb-button" type="submit" value="Perbarui Smiley"/></form></p>';
$link='bbsm.php?iwb=smiley&amp;page=';
$q='';
pagination($page,'10',$total,$link,$q);
}
echo '</div></div>';
require_once('inc/foot.php');
}
?>