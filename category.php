<?

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
$live_chat='off';
if (isset($_GET['id']))
{
$id=$_GET['id'];
if (!ctype_digit($id) || empty($id) || $id > 10 || $id < 1)
$id = 10;
switch ($id)
{
case '1':
$name=$LANG['general'];
break;
case '2':
$name=$LANG['art_and_cultur'];
break;
case '3':
$name=$LANG['education'];
break;
case '4':
$name=$LANG['hobbies_and_lifestyle'];
break;
case '5':
$name=$LANG['sport'];
break;
case '6':
$name=$LANG['science_and_technology'];
break;
case '7':
$name=$LANG['business_and_finance'];
break;
case '8':
$name=$LANG['health'];
break;
case '9':
$name=$LANG['personal'];
break;
case '10':
$name=$LANG['other'];
break;
default:
$name=$LANG['other'];
break;
}
$head_title=''.$LANG['blog_category'].' &raquo; '.$name;
$head_description='Kategori '.$name.' pada IndoWapBlog';
require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content"><div id="show_bar"><a href="category.php">'.$LANG['category'].'</a> &raquo; '.$name.'</div>';
$total=mysql_result(mysql_query("SELECT COUNT(*) FROM site WHERE category='".mysql_real_escape_string(htmlentities($id))."'"),0);
$page=$_GET['page'];
if (!ctype_digit($page) || empty($page) || $page == 0 || $page > (ceil($total / $site['num_post_main'])))
$page='1';
$page--;
$max_view=$site['num_post_main'];
$limit=$page*$max_view;
$page++;
if ($total > 0)
{
$cek=mysql_query("SELECT id, name, url, description FROM site WHERE category='".mysql_real_escape_string(htmlentities($id))."' ORDER BY id DESC LIMIT $limit,$max_view");
while ($list=mysql_fetch_array($cek))
{
echo $i % 2 ? '<div class="row0">' : '<div class="row1">';
echo '<a href="'.$list['url'].'">'.htmlspecialchars($list['name']).'</a><br /><span>'.htmlspecialchars($list['description']).'</span>';
++$i;
echo '</div>';
}
$link='category.php?id='.htmlentities($id).'&amp;page=';
$q='';
pagination($page,$max_view,$total,$link,$q);
}
else
{
echo $LANG['empty'];
}
echo '</div></div>';
require_once('inc/foot.php');
}
else
{
$head_title=$LANG['blog_category'];
$head_description='Kategori blog pada IndoWapBlog';
require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content">';
$no=1;
while ($no<=10)
{
$n=isset($no) ? $no : '';
switch ($n)
{
case '1':
$name=$LANG['general'];
break;
case '2':
$name=$LANG['art_and_cultur'];
break;
case '3':
$name=$LANG['education'];
break;
case '4':
$name=$LANG['hobbies_and_lifestyle'];
break;
case '5':
$name=$LANG['sport'];
break;
case '6':
$name=$LANG['science_and_technology'];
break;
case '7':
$name=$LANG['business_and_finance'];
break;
case '8':
$name=$LANG['health'];
break;
case '9':
$name=$LANG['personal'];
break;
case '10':
$name=$LANG['other'];
break;
default:
break;
}
$total_blog=mysql_result(mysql_query("SELECT COUNT(*) FROM site WHERE category='".$no."'"),0);
echo $i % 2 ? '<div class="row0">' : '<div class="row1">';
echo '<a href="category.php?id='.$no.'">'.$name.'&nbsp;&nbsp;<font color="#666666">('.$total_blog.')</font></a>';
++$i;
echo '</div>';
$no++;
}
echo '</div></div>';
require_once('inc/foot.php');
}
?>