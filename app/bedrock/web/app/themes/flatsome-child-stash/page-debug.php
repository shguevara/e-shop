<?php
/*
Template name: Page - Debug
*/
get_header(); ?>

<?php do_action('flatsome_before_page'); ?>

<div id="content" role="main" class="content-area">
	<h1>DEBUG:</h1>
		<?php
			print_r(get_theme_mods());
		?>
</div>

<?php do_action('flatsome_after_page'); ?>

<?php get_footer(); ?>