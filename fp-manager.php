<?php
  /*
   Plugin Name: Frontpage Manager
   Plugin URI: http://kirilisa.com/projects/frontpage-manager/
   Description: Frontpage manager lets you customize how frontpage posts appear in a number 
   of ways: limiting by category, number of posts, number of words/characters/paragraphs.   
   Version: 0.9 beta
   Author: Elise Bosse
   Author URI: http://kirilisa.com

   Copyright 2009  Elise Bosse  (email : kirilisa@gmail.com)   
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
  */

if (!class_exists("FPManager")) {
  class FPManager {
  
    function FPManager() {
    
    }

    function activate() {
      // add options to database
      add_option("fpm_post_category", 'all');	
      add_option("fpm_post_numposts", 1);
      add_option("fpm_post_cuttype", 'paragraph');	
      add_option("fpm_post_number", 1);
      add_option("fpm_post_linktext", 'view full post &raquo;');
      add_option("fpm_post_ending", '');
      add_option("fpm_striptags", '');      
    }

    function deactivate() {
      // remove options from database
      delete_option("fpm_post_category");	
      delete_option("fpm_post_numposts");
      delete_option("fpm_post_cuttype");	
      delete_option("fpm_post_number");
      delete_option("fpm_post_linktext");
      delete_option("fpm_post_ending");
      delete_option("fpm_striptags");      
    }

    function get_all_categories() {
      global $wpdb;
      return $wpdb->get_results("SELECT t.* from $wpdb->terms t left join $wpdb->term_taxonomy tt on t.term_id = tt.term_id where tt.taxonomy= 'category'");
    }

    function add_admin_page() {
      add_submenu_page('options-general.php', 'Frontpage Manager', 'Frontpage Manager', 10, __FILE__, array('FPManager', 'admin_page'));
    } 

    function add_js() {
      echo file_get_contents(ABSPATH.'wp-content/plugins/frontpage-manager/functions.js');
    }

    function admin_page() {
      if(isset($_POST['fpm_submit'])) {

	// posted data
	$category = $_POST['fpm_post_category'];
	$cuttype = $_POST['fpm_post_cuttype'];
	$numposts = intval(trim($_POST['fpm_post_numposts']));
	$number = intval(trim($_POST['fpm_post_number']));
	$ending = utf8_encode(trim($_POST['fpm_post_ending']));
	$linktext = utf8_encode(trim($_POST['fpm_post_linktext']));	
	$striptags = utf8_encode(trim($_POST['fpm_striptags']));
        
	// default number
	if ($number == '') {
	  if ($cuttype == 'paragraph' || $cuttype == 'none') $number = '1';
	  else if ($cuttype == 'letter') $number = '600';
	  else if ($cuttype == 'word') $number = '200';
	}

	// default readmore
	if ($linktext == '') {
	  $linktext = utf8_encode("view full post &raquo;");
	}

	// default numposts
	$numposts = $numposts < 1 ? 1 : $numposts;

	// update data in database
	update_option("fpm_post_category", $category);	
	update_option("fpm_post_cuttype", $cuttype);	
	update_option("fpm_post_numposts", $numposts);
	update_option("fpm_post_number", $number);
	update_option("fpm_post_linktext", $linktext);
	update_option("fpm_post_ending", $ending);
	update_option("fpm_striptags", $striptags);
	
	// updated message
	echo "<div id=\"message\" class=\"updated fade\"><p><strong>Frontpage Manager options updated.</strong></p></div>";
      }
      
      $cats = FPManager::get_all_categories();

      require_once('admin_page.php');
    }


    function display($content) {
      if (!is_front_page() || get_option('show_on_front') != 'posts') return $content;

      $striptags = get_option('fpm_striptags');
      $cuttype = get_option('fpm_post_cuttype');
      $linktext = get_option('fpm_post_linktext');
      $ending = get_option('fpm_post_ending');
      $truncate = get_option('fpm_post_number');

      if ($cuttype == 'none') return $content;

      if ($striptags != '') {
	// make sure tags to strip are clean
	$striptags = str_replace(array('<',' ','>'), '', $striptags);
	$tags = explode(',', $striptags);

	// strip the tags
	foreach ($tags as $tag) {	  
	  $content = preg_replace('/<\/?'.$tag.'( [^>]+)?>/', '', $content, -1, $cnt);
	}
      }      

      switch($cuttype) {
      case "word":
	$tmp = explode(' ', $content);       	
	$final = implode(' ', array_slice($tmp, 0, $truncate));
	$final = FPManager::fix_html($final, $ending);
	break;

      case "letter":
	$final = FPManager::fix_html(substr($content, 0, $truncate), $ending);
	break;

      case "paragraph":
	$final = "";
	$idx = 0;

	$tmp = explode('</p>', $content);
	while ($idx < $truncate) {
	  $final .= $tmp[$idx]."</p>";
	  $idx++;
	}
	break;
      }
            
      $final .= "\r\n".'<div class="fpm_readon"><a href="' . get_permalink() . '" rel="nofollow">' . utf8_encode($linktext) . "</a></div>\r\n";

      return $final;
    }

    function fix_html($str, $ending) {
      $missing = array();

      // fix any closing tag whitespace
      $str = preg_replace('/<(\/[a-zA-Z]+)\s?>/', '<\1>', $str);
      
      // fix any truncated tags first
      $str = preg_replace('/<\/?[a-zA-Z0-9_= :;"-]*$/', '', $str);

      // add nominated ending, if any
      $str .= $ending;

      // fetch all open tags
      preg_match_all('/<[a-zA-Z]+/', $str, $opentags, PREG_OFFSET_CAPTURE);

      // see if the open tags are closed in the excerpt
      while ($opentags[0]) {
	$info = array_shift($opentags[0]); 
	$tag = $info[0];
	$offset = $info[1];
	
	// ignore tags that don't need to be closed
	if (in_array($tag, array('<img', '<hr', '<br', '<input'))) continue;

	// check for closing tag
	$closetag = str_replace('<', '</', $tag) . '>';
	if (!strpos($str, $closetag, $offset)) $missing[] = $closetag;
      }
      
      // close any remaining open tags
      for ($i = count($missing) - 1; $i >= 0; $i--) {
	$str .= $missing[$i];
      }

      return $str;
    }

    function alter_query() {
      global $wp_query;

      $type = get_option('show_on_front');
      $category = get_option('fpm_post_category');
      $numposts = intval(get_option('fpm_post_numposts'));

      if (is_front_page() && $type == 'posts') {
	if (preg_match('/^[1-9]{1}[0-9]*$/', $category)) $wp_query->query_vars['cat'] = $category;
	$wp_query->query_vars['showposts'] = $numposts;
      }
    }

  }
}

  // instantiate class
if (class_exists("FPManager")) {
  $fpmanager = new FPManager();
}

// actions/filters
if (isset($fpmanager)) {
  add_filter('pre_get_posts', array('FPManager', 'alter_query'));
  add_filter('the_content', array('FPManager', 'display'));

  // administrative options
  add_action('admin_menu', array('FPManager', 'add_admin_page'));
  add_action('admin_head', array('FPManager', 'add_js')); 

  // activate/deactivate
  register_activation_hook(__FILE__, array('FPManager', 'activate'));
  register_deactivation_hook(__FILE__, array('FPManager', 'deactivate'));
}
?>