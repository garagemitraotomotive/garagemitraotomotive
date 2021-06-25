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

if ($defined != 'on')
{
define('_IWB_', 1);
include('inc/indowapblog.php');
}
else
{
defined('_IWB_') or die('ERROR'); // Opsional
}
if (!$user_id)
relogin();
$dashboard='on';
$head_title=$LANG['dashboard'];
require_once('inc/head.php');

echo '<div id="message">';
echo '</div>
<div id="content">
<div id="title">';

echo '<h2 class="title">'.$LANG['add_new'].'</h2><ul class="menu"><li><a href="post.php">'.$LANG['post'].'</li><li><a href="admin.php?iwb=category">'.$LANG['category'].'</li><li><a href="file.php">'.$LANG['file'].'</a></li>';

echo '</ul><h2 class="title">'.$LANG['edit'].'</h2><ul class="menu"><li><a href="post.php?iwb=manage">'.$LANG['post'].'</li><li><a href="file.php?">'.$LANG['file'].'</li><li><a href="manage_comment.php?">'.$LANG['comments'].'</a></li><li><a href="manage_guestbook.php?">'.$LANG['guestbook'].'</a></li><li><a href="admin.php?iwb=following">'.$LANG['following'].'</li>
<li><a href="admin.php?iwb=navigation">'.$LANG['navigation'].'</li><li><a href="admin.php?iwb=css_editor">'.$LANG['theme'].'</a></li>';
echo '</ul><h2 class="title">'.$LANG['settings'].'</h2><ul class="menu"><li><a href="admin.php?iwb=settings">'.$LANG['blog'].'</li><li><a href="iklan.php">'.$LANG['ads'].'</li><li><a href="admin.php?iwb=domain_parking">'.$LANG['domain_parking'].'</li><li><a href="admin.php?iwb=google">'.$LANG['google_verification'].'</a></li>';

echo '</ul><h2 class="title">'.$LANG['community'].'</h2><ul class="menu"><li><a href="chat.php">'.$LANG['chatroom'].'</a></li><li><a href="forum.php">'.$LANG['forum'].'</a></li><li><a href="user.php?iwb=list">'.$LANG['users_list'].'</a></li>';

echo '</ul><h2 class="title">'.$LANG['other'].'</h2><ul class="menu"><li><a href="kb.php">'.$LANG['knowledge_base'].'</a></li><li><a href="admin.php?iwb=stats">'.$LANG['statistics'].'</a></li><li><a href="kuis.php?">'.$LANG['quiz'].'</a></li><li><a href="new.php">'.$LANG['latest_post'].'</a></li>';

echo '</ul>            </div>
</div><br />';
require_once('inc/foot.php');

?>