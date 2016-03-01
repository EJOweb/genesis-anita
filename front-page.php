<?php

add_action( 'genesis_meta', 'anita_front_page_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function anita_front_page_genesis_meta() {

	if ( ! is_page() ) {
	
		//* Add faux anchors
		add_action( 'wp_footer', 'anita_script_clickable' );

		//* Add body class
		add_filter( 'body_class', 'anita_front_page_body_class' );

	}
}

//* Enqueue scripts for backstretch
add_action( 'wp_enqueue_scripts', 'anita_front_page_enqueue_scripts' );
function anita_front_page_enqueue_scripts() {
	
	$image = get_option( 'anita-home-image', sprintf( '%s/images/welcome.jpg', get_stylesheet_directory_uri() ) );
	
	//* Load scripts only if custom backstretch image is being used
	if ( ! empty( $image ) && is_active_sidebar( 'welcome' ) ) {

		//* Enqueue Backstretch scripts
		wp_enqueue_script( 'anita-backstretch', get_bloginfo( 'stylesheet_directory' ) . '/js/backstretch.js', array( 'jquery' ), '1.0.0' );
		wp_enqueue_script( 'anita-backstretch-set', get_bloginfo('stylesheet_directory').'/js/backstretch-set.js' , array( 'jquery', 'anita-backstretch' ), '1.0.0' );

		wp_localize_script( 'anita-backstretch-set', 'BackStretchImg', array( 'src' => str_replace( 'http:', '', $image ) ) );
	
	}

}

//* Add JS to allow elements to be faux anchors
function anita_script_clickable() {

	echo '<script type="text/javascript">jQuery(document).ready(function($){$(".content .entry").click(function(){window.location = $(this).find(".entry-title a").attr("href");});});</script>';

}

//* Add archive body class to the head
function anita_front_page_body_class( $classes ) {

	$classes[] = 'archive';
	return $classes;

}

//* Hook welcome widget area after site header
add_action( 'genesis_after_header', 'anita_welcome_widget_area' );
function anita_welcome_widget_area() {
 
	genesis_widget_area( 'welcome', array(
		'before' => '<div class="welcome">',
		'after'  => '</div>',
	) );
 
}

//* Relocate entry image
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_header', 'genesis_do_post_image', 1 );

//* Run the default Genesis loop
genesis();
