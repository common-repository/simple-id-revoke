<?php
/*
Plugin Name: Simple ID Revoke 

Description: Simple ID Revoke is a light  plugin that enables wordpress admin to see the Page ID, Post ID, User ID, and etc. This plugin was originally developed for to show IDs on our own websites. But we found that it can help many other of wordpress users and admins. it is the simplest way to get what you want, Just install and activate the plugin.
Version: 1.0.1
License: GNU General Public License, version 3 (GPLv3)
License URI: http://www.gnu.org/licenses/gpl-3.0.txt
Author: Dooman
Author URI: http://doomansoltani.com

Tags: post ids, easiest, users id, page id, simple, wp-admin, show,category id, media id, tag id, 
*/

/*


you can freely modify and enhance the plugin under the terms of the GNU General Public License as published by
the Free Software Foundation;

hope that it will be useful and helpful
*/


function idrevoke_load_textdomain() {
	load_plugin_textdomain( 'id-revoke', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'idrevoke_load_textdomain' );




if ( ! function_exists( 'idrevoke_column' ) ):

function idrevoke_column($cols) {
	$column_id = array( 'idrevoke' => __( 'ID', 'id-revoke' ) );
	$cols = array_slice( $cols, 0, 1, true ) + $column_id + array_slice( $cols, 1, NULL, true );
	return $cols;
}

endif; 

if ( ! function_exists( 'idrevoke_value' ) ) :

function idrevoke_value( $column_name, $id ) {
	if ( 'idrevoke' == $column_name ) {
		echo $id;
	}
}
endif; 


if ( ! function_exists( 'idrevoke_return_value' ) ) :
function idrevoke_return_value( $value, $column_name, $id ) {
	if ( 'idrevoke' == $column_name ) {
		$value .= $id;
	}
	return $value;
}
endif; 


if ( ! function_exists( 'idrevoke_css' ) ) :

function idrevoke_css() {
?>
<style type="text/css">
    #idrevoke { width: 80px; }
    @media screen and (max-width: 782px) {
        .wp-list-table #idrevoke, .wp-list-table #the-list .idrevoke { display: none; }
        .wp-list-table #the-list .is-expanded .idrevoke {
            padding-left: 30px;
        }
    }
</style>
<?php
}
endif; 
add_action( 'admin_head', 'idrevoke_css');


if ( ! function_exists( 'idrevoke_add' ) ) :
function idrevoke_add() {
	add_action( "manage_media_columns" ,        'idrevoke_column' );
	add_filter( "manage_media_custom_column" , 'idrevoke_value' , 10 , 3 );

	add_action( 'manage_link_custom_column', 'idrevoke_value', 10, 2 );
	add_filter( 'manage_link-manager_columns', 'idrevoke_column' );

	add_action( 'manage_edit-link-categories_columns', 'idrevoke_column' );
	add_filter( 'manage_link_categories_custom_column', 'idrevoke_return_value', 10, 3 );

	foreach( get_taxonomies() as $taxonomy ) {
		add_action( "manage_edit-${taxonomy}_columns" ,  'idrevoke_column' );
		add_filter( "manage_${taxonomy}_custom_column" , 'idrevoke_return_value' , 10 , 3 );
		if( version_compare($GLOBALS['wp_version'], '3.0.999', '>') ) {
			add_filter( "manage_edit-${taxonomy}_sortable_columns" , 'idrevoke_column' );
		}
	}

	foreach( get_post_types() as $ptype ) {
		add_action( "manage_edit-${ptype}_columns" , 'idrevoke_column' );
		add_filter( "manage_${ptype}_posts_custom_column" , 'idrevoke_value' , 10 , 3 );
		if( version_compare($GLOBALS['wp_version'], '3.0.999', '>') ) {
			add_filter( "manage_edit-${ptype}_sortable_columns" , 'idrevoke_column' );
		}
	}

	add_action( 'manage_users_columns', 'idrevoke_column' );
	add_filter( 'manage_users_custom_column', 'idrevoke_return_value', 10, 3 );
	if( version_compare($GLOBALS['wp_version'], '3.0.999', '>') ) {
		add_filter( "manage_users_sortable_columns" , 'idrevoke_column' );
	}

	add_action( 'manage_edit-comments_columns', 'idrevoke_column' );
	add_action( 'manage_comments_custom_column', 'idrevoke_value', 10, 2 );
	if( version_compare($GLOBALS['wp_version'], '3.0.999', '>') ) {
		add_filter( "manage_edit-comments_sortable_columns" , 'idrevoke_column' );
	}
}
endif; 
add_action( 'admin_init', 'idrevoke_add' );