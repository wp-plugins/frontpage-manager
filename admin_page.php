<div class="wrap">
<h2>Frontpage Manager</h2>

<?php
$postcat = get_option("fpm_post_category");
$limitpostby = get_option("fpm_post_cuttype");
$input_numposts = get_option("fpm_post_numposts");
$input_number = get_option("fpm_post_number");
$input_linktext = htmlspecialchars(get_option("fpm_post_linktext"));
$input_ending = htmlspecialchars(get_option("fpm_post_ending"));
$striptags = htmlspecialchars(get_option("fpm_striptags"));

$letter_sel = $limitpostby == "letter" ? 'selected' : '';
$word_sel = $limitpostby == "word" ? 'selected' : '';
$para_sel = $limitpostby == "paragraph" ? 'selected' : '';
$none_sel = $limitpostby == "none" ? 'selected' : '';

switch($limitpostby) {
 case "paragraph":
   $postnum_blurb = "<strong># paragraphs before cutoff</strong> (default <em>1</em>)";;
   break;
 case "letter":
   $postnum_blurb = "<strong># characters before cutoff</strong> (default <em>600</em>)";;
   break;
 case "word":
   $postnum_blurb = "<strong># words before cutoff</strong> (default <em>200</em>)";;
   break;
 case "none":
 default:
   $input_number = '';
   $input_linktext = '';
   $input_ending = '';
   $postnum_blurb = "<strong># before cutoff</strong> (default <em>1</em>)";;   
   break;
}

$option_display = $limitpostby == 'none' ? 'style="display:none;"' : '';
?>
	
<br />
<form method="post" name="options" target="_self">

<table>
<tr>
<td width="160"><strong>Select category</strong></td>

<td>
<select name="fpm_post_category">
  <option value="all">all</option>
<?php
foreach ($cats as $cat) {
  $id = $cat->term_id;
  $name = $cat->name;
  $cat_sel = $id == $postcat ? 'selected' : '';
  echo "<option value=$id $cat_sel>$name</option>\r\n";
}
?>
</select>
</td>
</tr>

<tr>
<td><strong>Max posts to display</strong></td>
<td><input name="fpm_post_numposts" type="text" size="3" value="<?php echo $input_numposts; ?>" /> (default <em>1</em>)</td>
</tr>

<tr>
<td><strong>Limit post(s) by</strong></td> 

<td>
<select name="fpm_post_cuttype" onchange="change_num(this);">
  <option value="none" <?php echo $none_sel; ?>>Do not limit</option>
  <option value="paragraph" <?php echo $para_sel; ?>>Number of paragraphs</option>
  <option value="letter" <?php echo $letter_sel; ?>>Number of characters</option>
  <option value="word" <?php echo $word_sel; ?>>Number of words</option>
  </select>
</td>
</tr>
</table>

<table id="truncate" <?php echo $option_display; ?>>
<tr>
<td width="160" valign="top"><strong>Limitation options</strong></td>

<td>
<input name="fpm_post_number" type="text" value="<?php echo $input_number; ?>" /> 
  <span id="fpm_num"><?php echo $postnum_blurb; ?></span><br />

  <input name="fpm_post_linktext" type="text" value="<?php echo $input_linktext; ?>" /> 
  <strong>Read more linktext</strong> (default <em>view full post &raquo;</em>)<br />
  
  <input name="fpm_post_ending" type="text" value="<?php echo $input_ending; ?>" /> 
  <strong>Text ending</strong> (for word/character limit only)<br />
    
  <input type="text" name="fpm_striptags" value="<?php echo $striptags; ?>" /> 
  <strong>Tags to strip </strong> (comma-separated, e.g. img, div, hr)
</td>
</tr>
</table>

  <p class="submit">
  <input name="fpm_submit" type="hidden" value="true" />
  <input type="submit" name="Submit" class="button-primary" value="Update Options &raquo;" />
  </p>
  </form>

  </div>