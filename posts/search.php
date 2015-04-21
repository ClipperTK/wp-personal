<?php

/**
 * @package favepersonal
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

get_header();

?>

<div id="primary">

	<h1 class="search-title h2"><?php printf(__('Search Results for: <em>%s</em>', 'favepersonal'), esc_html(urldecode(get_search_query()))); ?></h1>
	
<?php
cfct_loop();
cfct_misc('nav-posts');
?>

</div>

<div id="secondary">
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>