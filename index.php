<?php 
/*
Plugin Name: * Silk AJAX Comments
Plugin URI: http://webanyti.me
Description: The default wordpress comments system is awful when it comes to handling errors, this fixes this issue with some simple AJAX.
Version: 1.0
Author: Callum Silcock
Author URI: http://webanyti.me
Copyright (c) 2012 Callum Silcock (http://webanyti.me)
This is a WordPress plugin (http://wordpress.org).

This is a WordPress plugin (http://wordpress.org).

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

     _______. __   __       __  ___         ___            __       ___      ___   ___ 
    /       ||  | |  |     |  |/  /        /   \          |  |     /   \     \  \ /  / 
   |   (----`|  | |  |     |  '  /        /  ^  \         |  |    /  ^  \     \  V  / 
    \   \    |  | |  |     |    <        /  /_\  \  .--.  |  |   /  /_\  \     >   <  
.----)   |   |  | |  `----.|  .  \      /  _____  \ |  `--'  |  /  _____  \   /  .  \ 
|_______/    |__| |_______||__|\__\    /__/     \__\ \______/  /__/     \__\ /__/ \__\                                                                                               
  ______   ______   .___  ___. .___  ___.  _______ .__   __. .___________.    _______.
 /      | /  __  \  |   \/   | |   \/   | |   ____||  \ |  | |           |   /       |
|  ,----'|  |  |  | |  \  /  | |  \  /  | |  |__   |   \|  | `---|  |----`  |   (----`
|  |     |  |  |  | |  |\/|  | |  |\/|  | |   __|  |  . `  |     |  |        \   \    
|  `----.|  `--'  | |  |  |  | |  |  |  | |  |____ |  |\   |     |  |    .----)   |
 \______| \______/  |__|  |__| |__|  |__| |_______||__| \__|     |__|    |_______/    


   Special thanks to James Bruce who wrote most of this code - http://www.makeuseof.com/tag/author/jbruce/ --------

   Hit The Actions ------------------------------------------------------------------------------------------------ */

add_action('wp_print_scripts', 'google_jquery');
add_action('init', 'ajaxcomments_load_js', 10);


function ajaxcomments_load_js(){
		wp_enqueue_script('ajaxcomments', plugins_url('silk-ajax-comments/ajaxcomments.js'), array(), false, true);
}

function google_jquery() {
	wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"), false);
	wp_enqueue_script('jquery');
} 

/* Create PHP Handler ------------------------------------------------------------------[ajaxify_comments()]------- */

add_action('comment_post', 'ajaxify_comments',20, 2);
function ajaxify_comments($comment_ID, $comment_status){
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		switch($comment_status){
			case "0":
			wp_notify_moderator($comment_ID);
			case "1": //Approved comment
			echo "success";
			$commentdata =& get_comment($comment_ID, ARRAY_A);
			$post =& get_post($commentdata['comment_post_ID']);
			wp_notify_postauthor($comment_ID, $commentdata['comment_type']);
			break;
			default:
			echo "error";
		}
	exit;
	}
}

?>
