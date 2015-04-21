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

?>

		</div><!-- .container -->
	</section><!-- #content -->

	<footer id="footer">
		<div class="container clearfix">
			<p class="credit"><?php _e('Powered by <a href="http://wordpress.org/">WordPress</a> &nbsp;&middot;&nbsp; <a href="http://github.com/alexkingorg/personal/">Get Personal</a>', 'favepersonal'); ?></p>
<?php
$colophon = cfct_get_option('cfcp_copyright', false);
$sep = ($colophon ? ' &nbsp;&middot;&nbsp; ' : '');
$loginout = cfct_get_loginout('', $sep);
if ($colophon || $loginout) {
	echo '<p>'.$colophon.$loginout.'</p>';
}
?>
		</div><!--.container-->
	</footer><!-- #footer -->

	<?php wp_footer() ?>

</body>
</html>
