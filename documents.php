<?php

error_reporting(0);
ini_set('display_errors','Off');

global $server,$user,$password,$db,$sock,$id,$spage,$pg,$ajax,$com,$site_path;

if ($ajax == 0) {
     $partAA = "<div id=\"container_l_a\">";
     $partAAZ = "</div>";
     $partA = "<div id =\"container_l_b\">";
     $partAZ = "</div>";
     $partB = "<div id=\"opt_bar\">";
     $partBZ = "</div>";
}
else {
     $partAA = "[container_l_a]";
     $partAAZ = "[/container_l_a]";
     $partA = "[container_l_b]";
     $partAZ = "[/container_l_b]";
     $partB = "[opt_bar]";
     $partBZ = "[/opt_bar]";
}

$s_r = 0;

if (preg_match('/^s-(.+)/',$spage,$m)) {
	$spage = $m[1];
	$spage_ = "s-".$m[1];
	$s_r = 1;
}
elseif (preg_match('/([^-]+)/',$spage,$m)){
	$spage_ = $m[1];
}

if ((preg_match("/^articles$|^videos$|^s-articles$|^s-videos$/",$spage))&&($id != "null")) {
     $idd = 1;
     increment($spage,$id);
     if ($ajax == 0) {
          show_header("documents",$spage,1,$id);
     }
     $wh = $spage_;
}
elseif ((preg_match("/^articles$|^videos$|^s-articles$|^s-videos$/",$spage))&&($id == "null")) {
     $idd = 1;
     if ($ajax == 0) {
          show_header("documents",$spage,0,$id);
     }
     $wh = $spage_;
}
else {
     $idd = 0;
     if ($ajax == 0) {
          show_header("documents","documents");
     }
     $wh = "documents";
}

if ($ajax == 0) {
     echo "<div id = \"container_l\">";
}
echo $partAA."<div id=\"container_l_menu\"><ul>";
$arrz = array("Documents" => "documents", "Articles" => "articles", "Videos" => "videos", "Sec Articles" => "s-articles", "Sec Videos" => "s-videos");


foreach ($arrz as $k => $v) {
     if ($wh == "$v") {
          $st = "class =\"bgh\"";
          if ($wh == "documents") {
               $st2 = "href=\"".$site_path."/documents/\"";   
          }
          else {
               $st2 = "href=\"".$site_path."/documents/".$v."/\" onclick =\"loadPage('page=documents,subsec=".$v.",meta=off','get');return false;\"";
          }
     }
     else {
          $st = "class =\"nn\" onmouseover=\"this.style.backgroundColor='#D8D5ED'; this.style.color='white';\" onmouseout=\"this.style.backgroundColor='white';
                    this.style.color='black';\"";
          if ($v == "documents") {
               $st2 = "href=\"".$site_path."/documents/\"";
          }
          else {
               $st2 = "href=\"".$site_path."/documents/".$v."/\" onclick =\"loadPage('page=documents,subsec=".$v.",meta=off','get');return false;\"";
          }
     }
     if ($v == "documents") {
          $cl = "space11";
          $pic = "home1.png";
     }
     else {
          $cl = "space";
          $pic = "w.png";
     }
     $str = "<li ".$st."><a ".$st2."><img alt=\"$k\" class=\"".$cl."\" align=\"left\" src= \"".$site_path."/graphic/".$pic."\"/>".$k."</a></li>";
     echo $str;
}

echo "</ul></div><div id=\"container_l_statiscs_a\"><div class=\"nav_title_cont\">
	<img alt=\"stat\" class=\"space45\" src= \"".$site_path."/graphic/stat.png\"/><span class=\"vc\"><b>Statistics</b></span></div>";

$numbers = array("articles","videos", "articles","videos");
$c = 0;
echo "<table class=\"stable\">";
foreach ($numbers as $k => $v) {
     $c++;
     if ($c > 2) {
          $query = "SELECT id FROM $v WHERE section_tag = 'security'";
     }
     else {
          $query = "SELECT id FROM $v";
     }
     $res = mysql_query($query,$sock);
     $num = mysql_num_rows($res);
          if ($c == 1) {
               $str = "articles";
          }
          elseif ($c == 2) {
               $str = "videos";
          }
          elseif ($c == 3) {
               $str = "security/hacking articles";
          }
          elseif ($c == 4) {
               $str = "security/hacking videos";
          }
     echo "<tr><td class=\"statnumb\"><b>$num</b></td><td class=\"statname\">$str</td></tr>";
}

echo "</table>";
$ap = $spage;
$ap = preg_replace('/_/', ' ', $ap);

if (!preg_match("/^articles$|^videos$|^s-articles$|^s-videos$/",$spage)) {
     echo "</div>".$partAAZ.$partA."<div id=\"container_l_statiscs_b\"><div class=\"nav_title_cont\"><img alt=\"asd\" class=\"space45\" src= \"".$site_path."/graphic/asd.png\"/><span class=\"vc\"><b>Top views > Documents</b></span></div><p>";
     $cc = 0;
     foreach ($numbers as $k => $v) {
          $cc++;
          if ($c < 3) {
               $query = "SELECT * FROM ".$v." ORDER BY views DESC LIMIT 0,1";//echo $query."<br />";
               get_populars($query,"documents",$v,1);
          }
     }
}
else {
     echo "</div>".$partAAZ.$partA."<div id=\"container_l_statiscs_b\"><div class=\"nav_title_cont\"><img alt=\"asd\"class=\"space45\" src= \"".$site_path."/graphic/asd.png\"/><span class=\"vc\"><b>Top views > $ap</b></span></div><p>";
     if ($s_r == 1) {
          $aa = "WHERE section_tag = 'security'";
     }
     else {
          $aa = "";
     }
     $query = "SELECT * FROM ".$spage." ".$aa." ORDER BY views DESC LIMIT 0,5";//echo $query."<br />";
     get_populars($query,"documents",$spage_,0);
}
echo "</p></div>".$partAZ;
if ($ajax == 0) {
     echo "</div>";
}
                         
if (($spage == "null")&&($id == "null")) {
     documents_home();

}
elseif ((preg_match("/^articles$|^videos$|^s-articles$|^s-videos$/",$spage))&&($id == "null")) {
     if ($ajax == 0) {
          echo "<div id=\"container_r\">";
     }
     content_db($page,$spage_);
     if ($ajax == 0) {
          echo "</div>";
     }
}
elseif ($idd == 1) {
     if ($ajax == 0) {
          echo "<div id=\"container_r\">";
     }
     content_db($page,$spage_,$id);
     if ($ajax == 0) {
          echo "</div>";
     }
}

function documents_home() {
     global $sock,$site_path;
	$query = "SELECT content FROM texts WHERE title = 'documents_welcome'";
	$res = mysql_query($query, $sock);
	if ($res) {
		$re = mysql_fetch_array($res);
	}
	else {
		$re['content'] = "";
	}
     echo "<div id=\"container_r\"><div id=\"container_r_content\"><div id=\"welcome\">".$re['content']."</div>
		<div id=\"last_added\"><p><b>Recently added</b><br /><br />";


	$arr   = array();
	$query = array("SELECT * FROM articles ORDER BY id DESC LIMIT 0,7",
				"SELECT * FROM videos ORDER BY id DESC LIMIT 0,7");

	foreach ($query as $k => $v) {
		$res = mysql_query($v, $sock);
		if ($res) {
			while ($re = mysql_fetch_array($res)) {
				if ($k == 0) {
					$spage = "articles";
				}
				elseif ($k == 1) {
					$spage = "videos";
				}
				$var = "!_".$spage."_!_".$re['id']."_!_".$re['title']."_!";
				$arr[$var] = strtotime($re['date']);
			}
		}
	}

	arsort($arr);
	array_splice($arr, 5);
	foreach ($arr as $k => $v) {
		if (preg_match('/!_(.+)_!_([0-9]+)_!_(.+)_!/',$k,$m)) {
			$spage = $m[1];
			$id = $m[2];
			$title = $m[3];
		}
		$b = date ("Y-m-d", $v);
		$ap = $spage;
		$ap = preg_replace('/_/', ' ', $ap);
		$meta_tags = get_meta_tag($spage,1,$id,1);
		$str = "<img alt=\"az\" class=\"space\" align=\"left\" src=\"".$site_path."/graphic/az.png\"/> Added <a href=\"".$site_path."/documents/$spage/id".$id."\"
				onclick =\"loadPage('page=documents,subsec=$spage,id=$id,".
				"meta=".$meta_tags."','get');return false;\"><b>$title</b></a> in
				<a href=\"".$site_path."/documents/$spage/\" onclick =\"loadPage('page=documents,".
				"subsec=$spage,meta=off','get');return false;\"><b>$ap</b></a> on <i>$b</i><br /><br />";
		echo $str;
	}






     echo "</p></div></div>";
//echo "<script type=\"text/javascript\">
//		document.write('<div id=\"opt_bar\"></div>');
//		document.write('<div id=\"jwplayer\"></div>');
//	</script>
//	<noscript>
echo "		<div id=\"opt_bar\" style=\"display: none;background-color: #272727;color: white;margin: 10px 0px 0px 15px;height: 20px;width: 800px;\"></div>
		<div id=\"jwplayer\" style=\"display: none;margin-left: 15px;padding-bottom: 15px;border-bottom: 1px solid #B3B3B3;margin-top: 10px;height: auto;width: 800px;\"></div>";
//	</noscript>";


//echo "<div id=\"comments_show\"></div><div id=\"insert_comment\"></div></div>";
echo "<div id=\"comments_show\" style=\"display:none;height: auto;width: 800px;margin: 0px 15px 0px 15px;\"></div>
<div id=\"insert_comment\" style=\"display:none;height: auto;width: 620px;margin: 5px 15px 15px 15px;overflow: auto;padding-bottom: 5px;border: 1px solid #B3B3B3;\"></div></div>";

}


?>