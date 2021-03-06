<?php

/**
 * @package personal
 *
 * This file is part of the Personal Theme for WordPress
 * http://github.com/alexkingorg/wp-personal
 * (Forked from http://crowdfavorite.com/favepersonal/)
 *
 * Copyright (c) 2008-2013 Crowd Favorite, Ltd. All rights reserved.
 * http://crowdfavorite.com
 *
 * Copyright (c) 2015 Alex King.
 *
 * **********************************************************************
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * **********************************************************************
 */

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

if (has_post_thumbnail()) {
?>
<div style="float:left; margin: 0 1.5em 1.5em 0;">
	<?php the_post_thumbnail('thumb-img'); ?>
</div>
<?php 
}

cfct_the_content_feed();

?>
<p style="clear: both;"><a href="<?php echo get_permalink($post->ID); ?>">#</a></p>