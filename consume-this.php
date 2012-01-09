<?php
/*
Plugin Name: Consume This
Plugin URI: http://gabrielkoen.com/wordpress-plugins/consume-this/
Description: A Delicious.com-like bookmarklet for your site.  Tell people what you're consuming -- links, music, movies, you name it.
Version: 1.0
Author: Gabriel Koen
Author URI: http://gabrielkoen.com/
License: GPL2
*/

/*  Copyright 2011  Gabriel Koen  (email : gabriel.koen@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Register post type and taxonomy
 *
 * @since 1.0
 * @version 1.0
 */
function mint_consume_init() {
	register_post_type(
		'consumption',
		array(
			'labels' => array(
				'name' => _x('Consumption', 'post type general name'),
				'singular_name' => _x('Consumption', 'post type singular name'),
				'add_new' => _x('Add New', 'book'),
				'add_new_item' => __('Add New Consumption'),
				'edit_item' => __('Edit Consumption'),
				'new_item' => __('New Consumption'),
				'view_item' => __('View Consumption'),
				'search_items' => __('Search Consumptions'),
				'not_found' =>  __('No items found'),
				'not_found_in_trash' => __('No items found in Trash'),
				'parent_item_colon' => '',
				'menu_name' => 'Consumption',
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => 5,
			'supports' => array(
				'title',
				'author',
			),
			'register_meta_box_cb' => 'mint_consume_meta',
		)
	);

	register_taxonomy(
		'consumable',
		'consumption',
		array(
			'hierarchical' => false,
			'labels' => array(
				'name' => _x( 'Consumable', 'taxonomy general name' ),
				'singular_name' => _x( 'Consumable', 'taxonomy singular name' ),
				'search_items' =>  __( 'Search Consumables' ),
				'popular_items' => __( 'Popular Consumables' ),
				'all_items' => __( 'All Consumables' ),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __( 'Edit Consumable' ),
				'update_item' => __( 'Update Consumable' ),
				'add_new_item' => __( 'Add New Consumable' ),
				'new_item_name' => __( 'New Writer Consumable' ),
				'separate_items_with_commas' => __( 'Separate consumables with commas' ),
				'add_or_remove_items' => __( 'Add or remove consumables' ),
				'choose_from_most_used' => __( 'Choose from the most used consumables' ),
				'menu_name' => __( 'Consumables' ),
			),
			'show_ui' => true,
			'show_tagcloud' => true,
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'consumable'
			),
		)
	);
}
add_action('init', 'mint_consume_init');


/**
 * Activation hook
 *
 * @since 1.0
 * @version 1.0
 */
function mint_consume_activation() {
	mint_consume_init();
	flush_rewrite_rules();
}
register_activation_hook( plugin_basename( __FILE__ ), 'mint_consume_activation');


/**
 * Hide permalink on consumption post edit page
 *
 * @since 1.0
 * @version 1.0
 */
function mint_consume_hide_permalink($return) {
	if ( 'consumption' === get_post_type() )
		return '';

	return $return;
}
add_filter('get_sample_permalink_html', 'mint_consume_hide_permalink');


/**
 * Add post meta boxes
 *
 * @since 1.0
 * @version 1.0
 */
function mint_consume_meta() {
	add_meta_box( 'mint_consume_metalink', __('Link'), 'mint_consume_metalink_box', 'consumption', 'normal', 'high' );

	add_meta_box( 'mint_consume_metabookmarklet', __('Consume This'), 'mint_consume_metabookmarklet_box', 'consumption', 'side' );
}


/**
 * Bookmarklet meta box, also forces the post format to "status"
 *
 * @since 1.0
 * @version 1.0
 *
 * @see mint_consume_meta()
 */
function mint_consume_metabookmarklet_box($post) {
	// Force the post format to "status"
	?><input type="radio" name="post_format" class="post-format" id="post-format-status" value="status" checked="checked" style="display: none;"><?php

	// Show the bookmarklet
	add_filter('shortcut_link', 'mint_consume_bookmarklet', 11);
	?>
	<p><?php _e('Consume This is a bookmarklet: a little app that runs in your browser and lets you grab bits of the web.');?></p>
	<p><?php _e('Use Consume This to clip text, images and videos from any web page. Then edit and add more straight from Consume This before you save or publish it in a post on your site.'); ?></p>
	<p><?php _e('Drag-and-drop the following link to your bookmarks bar or right click it and add it to your favorites for a posting shortcut.') ?></p>
	<p class="pressthis"><a onclick="return false;" oncontextmenu="if(window.navigator.userAgent.indexOf('WebKit')!=-1)jQuery('.pressthis-code').show().find('textarea').focus().select();return false;" href="<?php echo htmlspecialchars( get_shortcut_link() ); ?>"><span><?php _e('Consume This') ?></span></a></p>
	<div class="pressthis-code" style="display:none;">
		<p class="description"><?php _e('If your bookmarks toolbar is hidden: copy the code below, open your Bookmarks manager, create new bookmark, type Consume This into the name field and paste the code into the URL field.') ?></p>
		<p><textarea rows="5" cols="120" readonly="readonly"><?php echo htmlspecialchars( get_shortcut_link() ); ?></textarea></p>
	</div>
	<?php
}


/**
 * Bookmarklet filter for creating the Consume This button
 *
 * @since 1.0
 * @version 1.0
 *
 * @see mint_consume_metabookmarklet_box()
 */
function mint_consume_bookmarklet($link) {
	$post_type = 'consumption';

	$link = str_replace('press-this.php', 'post-new.php', $link);
	$link = str_replace('?u=', '?post_type=' . $post_type . '&u=', $link);
	$link = str_replace('720', '789', $link);
	$link = str_replace('570', '414', $link);

	return $link;
}


/**
 * Link meta box
 *
 * @since 1.0
 * @version 1.0
 *
 * @see mint_consume_meta()
 */
function mint_consume_metalink_box($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'mint_consume_metanonce' );

	if ( isset($_GET['u']) && isset($_GET['t']) ) {
		$url = isset($_GET['u']) ? esc_url($_GET['u']) : '';
	} else {
		$url = get_post_meta($post->ID, '_mint_consume_link', true);
	}

	?>
	<input type="text" name="mint_consume_link" class="mint-consume-link" id="mint-consume-link" value="<?php echo $url; ?>" style="width: 100%;" />
	<?php
}


/**
 * Save link postmeta
 *
 * @since 1.0
 * @version 1.0
 *
 * @see mint_consume_metalink_box()
 */
function mint_consume_save_postdata( $post_id ) {
	if ( 'consumption' !== get_post_type() )
		return;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if ( !current_user_can( 'edit_post', $post_id ) )
		return;

	if ( !wp_verify_nonce( $_POST['mint_consume_metanonce'], plugin_basename( __FILE__ ) ) )
		return;

	$link = esc_url_raw( $_POST['mint_consume_link'] );

	update_post_meta($post_id, '_mint_consume_link', $link);

	return $link;
}
add_action( 'save_post', 'mint_consume_save_postdata' );


/**
 * Consume This formatting
 *
 * @since 1.0
 * @version 1.0
 */
function mint_consume_admin_head() {

	if ( 'consumption' === get_post_type() ) {
		$css = '#minor-publishing { display: none; }' . "\n";
		if ( isset($_GET['u']) && isset($_GET['t']) ) {
			$css .= '#wphead, #adminmenuback, #adminmenuwrap { display: none; }' . "\n";
			$css .= '#wpcontent { margin-left: 0; }' . "\n";

			global $post;
			$post->post_title = isset( $_GET['t'] ) ? trim( strip_tags( html_entity_decode( stripslashes( $_GET['t'] ) , ENT_QUOTES) ) ) : '';

			$url = isset($_GET['u']) ? esc_url($_GET['u']) : '';
		}

		?><style type="text/css"><?php echo $css; ?></style><?php
	}
}
add_action('admin_head', 'mint_consume_admin_head');


/**
 * Default helper text for Consumption post title
 *
 * @since 1.0
 * @version 1.0
 */
function mint_consume_enter_title_here($text) {
	if ( 'consumption' === get_post_type() ) {
		return __('What are you consuming?');
	}

	return $text;
}
add_filter('enter_title_here', 'mint_consume_enter_title_here');