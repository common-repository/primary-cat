<?php
/*
Plugin Name: Primary Cat
Description: Set a primary category for your posts... and then do stuff with it. [primary-cat]
Version: 0.2
Requires at least: 5.0
Author: Bryan Hadaway
Author URI: https://calmestghost.com/
License: GPL
License URI: https://www.gnu.org/licenses/gpl.html
Text Domain: primarycat
*/

// deny direct access to file
if ( !defined( 'ABSPATH' ) ) {
	http_response_code( 404 );
	die();
}

// add primary category option to categories section on the post editor
if ( is_admin() ) {
	add_action( 'edit_form_after_editor', 'pcat_primary_cat' );
	add_action( 'render_block', 'pcat_primary_cat' );
	function pcat_primary_cat( $post ) {
		$values = get_post_custom( $post->ID );
		if ( isset( $values['pcat_primary'] ) ) {
			$pcat_primary_cat = esc_html( $values['pcat_primary'][0] );
		}
		wp_nonce_field( 'pcat_primary_cat_nonce', 'primary_cat_nonce' );
		?>
		<style>.set-as-primary:after{content:'⇪';color:#0073aa;margin-left:5px}</style>
		<script>
		jQuery(document).ready(function($) {
			$('#categorydiv .inside,.editor-post-taxonomies__hierarchical-terms-list').prepend('<p><input type="text" id="primary-cat" name="pcat_primary" placeholder="Primary Category" value="<?php if ( $pcat_primary = get_post_meta( $post->ID, "pcat_primary", true ) ) { echo esc_html( $pcat_primary_cat ); } ?>"></p>');
			$('#categorydiv .selectit').append('<span title="Set as primary category" class="set-as-primary"></span>');
			$('#categorydiv .set-as-primary').click(function() {
				var value = $(this).closest('label').text().trim();
				var input = $('#primary-cat');
				input.val(value);
				return false;
			});
		});
		</script>
		<?php
	}
	add_action( 'save_post', 'pcat_primary_cat_save' );
	function pcat_primary_cat_save( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( !isset( $_POST['primary_cat_nonce'] ) || !wp_verify_nonce( $_POST['primary_cat_nonce'], 'pcat_primary_cat_nonce' ) ) return;
		if ( !current_user_can( 'edit_post', $post_id ) ) return;
		if ( isset( $_POST['pcat_primary'] ) ) {
			$pcat_primary = sanitize_text_field( $_POST['pcat_primary'] );
			update_post_meta( $post_id, 'pcat_primary', $pcat_primary );
		}
	}
}

// replace the default category slug with the primary one when using /%category%/%postname%/
add_filter( 'post_link_category', 'pcat_link_category', 10, 3 );
function pcat_link_category( $cat, $cats, $post ) {
	$pcat_primary_cat = get_post_meta( $post->ID, 'pcat_primary', true );
	if ( $term = get_term_by( 'name', $pcat_primary_cat, 'category' ) ) {
		$cat = $term;
	}
	return $cat;
}

// allow shortcodes in text widgets
add_filter( 'widget_text', 'do_shortcode' );

// shortcode to display the primary category
add_shortcode( 'primary-cat', 'pcat_cat_shortcode' );
function pcat_cat_shortcode() {
	if ( in_the_loop() ) {
		ob_start();
		global $post;
		if ( $pcat_primary_cat = get_post_meta( get_the_ID(), 'pcat_primary', true ) ) {
			echo '<span class="primary-cat">' . esc_html( $pcat_primary_cat ) . '</span>';
		} elseif ( has_category( 'uncategorized' ) ) {
		} else {
			$category = get_the_category();
			echo '<span class="primary-cat">' . esc_attr( $category[0]->cat_name ) . '</span>';
		}
		$output = ob_get_clean();
		return $output;
	}
}

// shortcode to display breadcrumbs
add_shortcode( 'primary-bread', 'pcat_bread_shortcode' );
function pcat_bread_shortcode() {
	ob_start();
	global $post;
	if ( !is_home() ) {
		echo '<style>ul#breadcrumbs, ul#breadcrumbs li, ul#breadcrumbs li:before, ul#breadcrumbs li:after{display:inline;content:"";list-style:none;padding:0;margin:0}</style>';
		echo '<ul id="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList"><li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( home_url() ) . '/" itemprop="item"><span itemprop="name">' . esc_html__( 'Home', 'primary-cat' ) . '</span></a><meta itemprop="position" content="1"></li> &rarr; ';
		if ( $pcat_primary_cat = get_post_meta( get_the_ID(), "pcat_primary", true ) ) {
			echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( home_url() ) . '/' . esc_html( str_replace( ' ', '-', strtolower( $pcat_primary_cat ) ) ) . '/" itemprop="item"><span itemprop="name">' . esc_html( $pcat_primary_cat ) . '</span></a><meta itemprop="position" content="2"></li>';
		} elseif ( is_single() ) {
			$categories = get_the_category();
			$separator = ', ';
			$output = '';
			if ( ! empty( $categories ) ) {
				foreach( $categories as $category ) {
					$output .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" itemprop="item"><span itemprop="name">' . esc_attr( $category->name ) . '</span></a><meta itemprop="position" content="2"></li>' . $separator;
				}
				echo trim( $output, $separator );
			}
		}
		if ( is_single() ) {
			echo ' &rarr; ';
		}
		echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="" itemprop="item"><span itemprop="name">';
		remove_all_filters( 'wp_title' );
		wp_title( '' );
		echo '</span></a><meta itemprop="position" content="3"></li>';
		echo '</ul>';
	}
	$output = ob_get_clean();
	return $output;
}