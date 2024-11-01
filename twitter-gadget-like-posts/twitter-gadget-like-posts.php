<?php
/*
Plugin Name: Twitter Gadget Like Posts
Plugin URI: http://tubo.jp.net/twitter_gadget_like_posts/
Description: This plug-in is the ability to display a post of a feeling similar to Gadget of Twitter.
Author: Tetsuya Yoshida
Version: 1.0
Author URI: http://tubo.jp.net/
License: GPL2
*/

/*  Copyright 2012 Tetsuya Yoshida (email :info[at]tubo.jp.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define("PLUGIN_DOMAIN_NAME", 'twitter-gadget-like-posts');
load_plugin_textdomain(PLUGIN_DOMAIN_NAME, false, basename(dirname(__FILE__)).DIRECTORY_SEPARATOR."languages");

class TwitterGadgetLikePosts extends WP_Widget {
	
	function TwitterGadgetLikePosts() {
		parent::WP_Widget(false, $name = __("Twitter Gadget Like Posts", PLUGIN_DOMAIN_NAME));
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', htmlspecialchars($instance['title']));
		
		echo $before_widget;
		if ($title) echo $before_title . $title . $after_title;
		
		$params = array();
		$params[] = "category_in=".$instance['category_id'];
		$params[] = "num=".$instance['num'];
		$params[] = "content_len=".$instance['post_len'];
		$params[] = "interval_time=".($instance['interval'] * 1000);
		$params[] = "link_color=".$instance["link_color"];
		$params[] = "color=".$instance["color"];
		$params[] = "bg_color=".$instance["bg_color"];
		$params[] = "border_color=".$instance["border_color"];
		
		$height = $instance['height'];
		echo '<iframe src="' . plugins_url('gadget.php',__FILE__) . "?" . implode("&", $params) . '"' . " style='height:".$height."px;'></iframe>";
	
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		// タイトル
		$instance['title'] = strip_tags($new_instance['title']);
		// 表示記事数
		$number = (int) strip_tags($new_instance['num']);
		if (is_int($number) && $number > 0) {
			$instance['num'] = $number;
		} else {
			$instance['num'] = 0;
		}
		// 記事カテゴリ
		if (is_array($new_instance['category_id'])) {
			$instance['category_id'] = implode(",", $new_instance['category_id']);
		} else {
			$instance['category_id'] = "";
		}
		// 表示間隔
		$interval = (int) strip_tags($new_instance['interval']);
		if (is_int($interval) && $interval > 0 && $interval <= 10) {
			$instance['interval'] = $interval;
		} else {
			$instance['interval'] = 5;
		}
		// 文字数
		$post_len = (int) strip_tags($new_instance['post_len']);
		if (is_int($post_len) && $post_len > 50 && $post_len < 300) {
			$instance['post_len'] = $post_len;
		} else {
			$instance['post_len'] = 50;
		}
		// リンク色
		$col = sprintf("%06s", $new_instance['link_color']);
		if (ctype_xdigit($col)) {
			$instance['link_color'] = $col;
		} else {
			$instance['link_color'] = "1111cc";
		}
		// 文字色
		$col = sprintf("%06s", $new_instance['color']);
		if (ctype_xdigit($col)) {
			$instance['color'] = $col;
		} else {
			$instance['color'] = "000000";
		}
		// 背景色
		$col = sprintf("%06s", $new_instance['bg_color']);
		if (ctype_xdigit($col)) {
			$instance['bg_color'] = $col;
		} else {
			$instance['bg_color'] = "FFFFFF";
		}
		// 枠色
		$col = sprintf("%06s", $new_instance['border_color']);
		if (ctype_xdigit($col)) {
			$instance['border_color'] = $col;
		} else {
			$instance['border_color'] = "EFEFEF";
		}
		// 高さ
		$height = (int) strip_tags($new_instance['height']);
		if (is_int($height) && $height > 100 && $height <= 1000) {
			$instance['height'] = $height;
		} else {
			$instance['height'] = 500;
		}
		return $instance;
	}

	function form($instance) {
		// 初期値
		if ($instance['height'] == "") $instance['height'] = 500;
		if ($instance['num'] == "") $instance['num'] = 10;
		if ($instance['interval'] == "") $instance['interval'] = 5;
		if ($instance['post_len'] == "") $instance['post_len'] = 300;
		if ($instance['link_color'] == "") $instance['link_color'] = "0000FF";
		if ($instance['color'] == "") $instance['color'] = "000000";
		if ($instance['bg_color'] == "") $instance['bg_color'] = "FFFFFF";
		if ($instance['border_color'] == "") $instance['border_color'] = "EFEFEF";	
		// タイトル
		echo '<div style="margin:10px 0px;">' . __('Title', PLUGIN_DOMAIN_NAME) . ':<br /><input name="' . $this->get_field_name('title') . '" type="text" value="' . $instance['title'] . '" /></div>';
		// 高さ
		echo '<div style="margin:10px 0px;">' . __('Height' ,PLUGIN_DOMAIN_NAME) . ' (px) :<br /><input name="' . $this->get_field_name('height') . '" type="text" value="' . $instance['height'] . '" /></div>';	
		// 件数
		echo '<div style="margin:10px 0px;">' . __('Post Number', PLUGIN_DOMAIN_NAME) . ':<br /><input name="' . $this->get_field_name('num') . '" type="text" value="' . $instance['num'] . '" style="ime-mode:disabled;" /></div>';
		// 表示間隔（秒）
		echo '<div style="margin:10px 0px;">' . __('Change Interval (second)', PLUGIN_DOMAIN_NAME) . ':<br /><input name="' . $this->get_field_name('interval') . '" type="text" value="' . $instance['interval'] . '" style="ime-mode:disabled;" /></div>';
		// 記事の表示文字数
		echo '<div style="margin:10px 0px;">' . __('Post String Length', PLUGIN_DOMAIN_NAME) . ':<br /><input name="' . $this->get_field_name('post_len') . '" type="text" value="' . $instance['post_len'] . '" style="ime-mode:disabled;" /></div>';
		// リンク色
		$link_color = $instance["link_color"];
		echo "<div style='margin:10px 0px;'>" . __('Link Color', PLUGIN_DOMAIN_NAME) . ' ex.0000FF :<br /><input name="' . $this->get_field_name('link_color') . '" type="text" value="' . $link_color . '"' . "style='ime-mode:disabled;' maxLength='6' /></div>";
		// 文字色
		$color = $instance["color"];
		echo "<div style='margin:10px 0px;'>" . __('Post String Color', PLUGIN_DOMAIN_NAME) . ' ex.000000 :<br /><input name="' . $this->get_field_name('color') . '" type="text" value="' . $color . '"' . "style='ime-mode:disabled;' maxLength='6' /></div>";
		// 背景色
		$bg_color = $instance["bg_color"];
		echo "<div style='margin:10px 0px;'>" . __('Background Color' ,PLUGIN_DOMAIN_NAME) . ' ex.FFFFFF :<br /><input name="' . $this->get_field_name('bg_color') . '" type="text" value="' . $bg_color . '"' . "style='ime-mode:disabled;' maxLength='6' /></div>";
		// ボーダー色
		$border_color = $instance["border_color"];
		echo "<div style='margin:10px 0px;'>" . __('Border Color', PLUGIN_DOMAIN_NAME) . ' ex.EFEFEF :<br /><input name="' . $this->get_field_name('border_color') . '" type="text" value="' . $border_color . '"' . "style='ime-mode:disabled;' maxLength='6' /></div>";
		// 色確認
		echo "<div style='margin:10px 0px;'>". __('Color Test', PLUGIN_DOMAIN_NAME) .":<br /></div>";
		echo "<div style='width:200px; height:40px; padding:10px; background-color:#$bg_color; border:solid 1px #$border_color;'>";
		echo "<span style='color:#$link_color;'>Link Color TEST</span><br>";
		echo "<span style='color:#$color;'>String Color TEST String Color TEST String Color TEST</span>";
		echo "</div>";
		// カテゴリー
		$terms = get_categories();
		echo '<div style="margin-top:10px;">' . __('Category', PLUGIN_DOMAIN_NAME) . ':</div>';
		$categoryIdList = array();
		if ($instance['category_id'] != "") {
			$categoryIdList = explode(",", $instance['category_id']);
		}
		foreach ($terms as $term) {
			$option1 = "";
			$option1 .= '<input type="checkbox" name="' . $this->get_field_name('category_id') . '[]" value="' . $term->term_id . '"';
			
			if (in_array($term->term_id, $categoryIdList)) {
				$option1 .= " checked ";
			}
			$option1 .= 'id="' . $term->term_id . '"><label for="' . $term->term_id . '">' . $term->name . "<br></label>";
			echo $option1;
		}
		echo '</select>';
	}
}
add_action('widgets_init', create_function('', 'return register_widget("TwitterGadgetLikePosts");'));
?>
