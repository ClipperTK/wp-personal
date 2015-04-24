<?php
if (!empty($settings['images']) && count($settings['images'])) {
	echo '
		<div class="bio-box-gallery"'.(count($settings['images']) > 1 ? ' id="bio-carousel"' : '').'>
			<div class="bio-box-gallery-images">';
	$thumb_size = 'small-img';
	foreach ($settings['images'] as $img_id) {
		echo wp_get_attachment_image($img_id, $thumb_size, false, array());
	}
	echo '
			</div>';
	if (count($settings['images']) > 1) {
		echo '
			<a href="#prev" class="bio-carousel-nav" id="bio-carousel-prev">'.__('prev', 'favepersonal').'</a>
			<a href="#next" class="bio-carousel-nav" id="bio-carousel-next">'.__('next', 'favepersonal').'</a>';
	}
	echo '
		</div>';
}
?>
<div class="bio-box-content">
	<h2 class="bio-box-title"><?php echo $settings['title']; ?></h2>
	<?php echo do_shortcode(wpautop(wptexturize($settings['description']))); ?>
</div>
