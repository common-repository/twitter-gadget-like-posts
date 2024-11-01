<html>
<head>
<?php
	require('../../../wp-blog-header.php');
	function addScripts1() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery.easing',plugins_url('jquery.easing.js', __FILE__),array('jquery'), "20120925");
		wp_enqueue_script('jquery.tmpl.min',plugins_url('jquery.tmpl.min.js', __FILE__),array('jquery'), "20120925");
		wp_enqueue_script('jquery.tw.gadget.like.posts',plugins_url('jquery.tw.gadget.like.posts.js', __FILE__),array('jquery'), "20120925");
		
		wp_print_scripts('jquery');
		wp_print_scripts('jquery.easing');
		wp_print_scripts('jquery.tmpl.min');
		wp_print_scripts('jquery.tw.gadget.like.posts');
	}
	add_action('load_my_scripts', 'addScripts1');
	do_action("load_my_scripts");
?>
<script type="text/javascript">
<?php
echo 'jQuery(document).ready(function(){';
	$categoryIdList = array();
	$categoryIdList = explode(",", $_GET["category_in"]);
	
	$postList = query_posts( array(
			'category__in' => $categoryIdList,
			'posts_per_page' => $_GET["num"],
			'order' => 'DESC'
			)
		);
	echo "var postList = [];";
	for ($i=0;$i < count($postList);$i ++) {
		$title = '"' . TwitterGadgetLikePostsClearSpace($postList[$i]->post_title) . '"';
		$tuzuki = "";
		if (mb_strlen($postList[$i]->post_content) > $_GET["content_len"]) {
			$tuzuki = "…";
		}
		$content = '"' .  mb_substr(TwitterGadgetLikePostsClearSpace($postList[$i]->post_content), 0, $_GET["content_len"]) . $tuzuki . '"';
		$url = '"' . ($postList[$i]->guid) . '"';
		$time = '"' . $postList[$i]->post_date_gmt . '"';
		echo "postList.push({title:$title, content:$content, url:$url, time:$time});";
	}
	
	$intervalTime = $_GET["interval_time"];
	echo "TWLIKEGADGETPOSTS_JAPAN.INTERVAL_TIME = $intervalTime;";
	echo 'var twlike = new TWLIKEGADGETPOSTS_JAPAN(postList, jQuery("#box")); ';
echo "});";


function TwitterGadgetLikePostsClearSpace($str) {
	if ($str == "") {
		return "";
	}
	$str = trim(strip_tags($str));
	$str = str_replace(array("\r\n","\r","\n"), '', $str);
	return $str;
}

?>
</script>
<style>
body {
	overflow:hidden;
	color: #<?php echo $_GET["color"]?>;
}

a { }
a:link, a:visited, a:active { color: #<?php echo $_GET["link_color"]?>; }
a:hover { color: #<?php echo $_GET["link_color"]?>;}

ul {
	border:solid 1px #<?php echo $_GET["border_color"]?>;
	background-color:#<?php echo $_GET["bg_color"]?>;
	list-style-type:none;
	margin:20px 0px;
	padding:10px;
}

li {
	font-size:12px;
}

.title {
	font-size:15px;
	font-weight:bold;
}

.time {
	text-align:right;
}

</style>
<script id="temp1" type="text/x-jquery-tmpl">
<div>
<ul>
	<li class='title'><a href='javascript:void(0);' onclick="parent.location.href='${post_url}';">${post_title}</a></li>
	<li>${post_content}</li>
	<li class='time'>${post_time}</li>
</ul>
</div>
</script>
</head>
<body id="box">
</body>
</html>