<?php
/*
Plugin Name:	Oxymix Oxygen Enhancement by Webmix
Plugin URI:		https://www.webmix.com.au
Description:	Enhances Oxygen plus removes WP bloat
Version:		0.9.0
Author:			Aaron Whittaker
Author URI:		https://www.aaronwhittaker.com
License:		GPL-2.0+
License URI:	http://www.gnu.org/licenses/gpl-2.0.txt

This plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with This plugin. If not, see {URI to Plugin License}.
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}
// Pre-order Common CSS & JS
function preorder_theme() {
	wp_enqueue_style( 'preorder-style', plugin_dir_url( __FILE__ ) . 'assets/css/preorder.css' );
	wp_enqueue_script( 'timecircles', plugin_dir_url( __FILE__ ) . 'assets/js/timecircles.js', '', '1.0.0', true );
	wp_enqueue_style( 'datatable-style', plugin_dir_url( __FILE__ ) . 'assets/css/datatables.min.css' );
	wp_enqueue_style( 'res-datatable-style', plugin_dir_url( __FILE__ ) . 'assets/css/responsive.dataTables.min.css' );
	wp_enqueue_script( 'datatable-script', plugin_dir_url( __FILE__ ) . 'assets/js/datatables.min.js', '', '1.0.0', true );
	wp_enqueue_script( 'res-datatable-script', plugin_dir_url( __FILE__ ) . 'assets/js/dataTables.responsive.min.js', '', '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'preorder_theme', 99 );

// Move Oxy Fonts to the footer, cheers Max Zimmer
function move_oxygen_font_scripts_to_footer() {
remove_action( 'wp_head', 'add_web_font', 0 );
}
add_action( 'template_redirect', 'move_oxygen_font_scripts_to_footer', 0 );
add_action( 'wp_print_footer_scripts', 'add_web_font', 0 );

//REMOVE UNNEEDED STUFF

	//REMOVE EMOJIS
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
	return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
	return array();
	}}

function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	}
add_action( 'init', 'disable_emojis' );

	//REMOVE ADMIN BAR
add_filter('show_admin_bar', '__return_false');

add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

function oz_remove_styles() {

//wp_dequeue_style( 'rehub_shortcode' );
//wp_deregister_style( 'rehub_shortcode' );

}
add_action( 'wp_print_styles', 'oz_remove_styles', 20 );

function oz_remove_scripts() {

	// Check if WooCommerce plugin is active
	if( function_exists( 'is_woocommerce' ) ){		
			
			## Dequeue WooCommerce styles
			//wp_dequeue_style('woocommerce-layout'); 
			//wp_dequeue_style('woocommerce-general'); 
			//wp_dequeue_style('woocommerce-smallscreen'); 	
 
			## Dequeue WooCommerce scripts
			wp_deregister_script( 'zoom' );
			wp_dequeue_script('zoom');
			wp_deregister_script( 'wc-cart-fragments' );
			wp_dequeue_script('wc-cart-fragments');
			wp_dequeue_script('wc-cart');
			wp_deregister_script( 'wc-cart' );
			wp_dequeue_script('wc-add-to-cart');
			wp_deregister_script( 'wc-add-to-cart' );
			wp_dequeue_script('wc-add-to-cart-variation');
			wp_deregister_script( 'wc-add-to-cart-variation' );
			wp_dequeue_script('wc-checkout');
			wp_deregister_script( 'wc-checkout' );
			wp_dequeue_script( 'woocommerce_photoswipe' );
			wp_deregister_script( 'woocommerce_photoswipe' );
			wp_dequeue_script( 'woocommerce_photoswipe' );
			wp_deregister_script( 'photoswipe' );
			wp_dequeue_script( 'photoswipe' );
			wp_deregister_script( 'flexslider' );
			wp_dequeue_script( 'flexslider' );
			wp_deregister_script( 'js-cookie' );
			wp_dequeue_script( 'js-cookie' );
			wp_deregister_script( 'jquery-blockui' );
			wp_dequeue_script( 'jquery-blockui' );
			wp_deregister_script( 'jquery-payment' );
			wp_dequeue_script( 'jquery-payment' );

	}

}
add_action( 'wp_enqueue_scripts', 'oz_remove_scripts', 20 );

// disable the woocommerce photoswipe
add_action( 'template_redirect', function() {
    remove_theme_support( 'wc-product-gallery-zoom' );
    remove_theme_support( 'wc-product-gallery-lightbox' );
    remove_theme_support( 'wc-product-gallery-slider' );
}, 100 );

//LAZY LOAD - Moved to Autoptimize

//Tables

// Add Post title to custom meta 
add_action( 'transition_post_status', 'duplicate_title', 10, 3 );

function duplicate_title( $new, $old, $post ) {
    if ( $post->post_type == 'product' ) {
        update_post_meta( $post->ID, 'd_title', $post->post_title );
    }
}

function product_datatables_scripts() {
	wp_enqueue_script( 'product_datatables', plugin_dir_url( __FILE__ ) . 'assets/js/producttable.js', '', '1.0.0', true );
	wp_localize_script( 'product_datatables', 'ajax_url', admin_url('admin-ajax.php?action=product_datatables') );
}


function product_datatables() {
     
    product_datatables_scripts(); 
     
    ob_start(); ?>
    <table id="producttable" class="table row-border cell-border hover stripe order-column"> 
        <thead> 
            <tr><th>Product Image</th>
                <th>Product Name</th> 
                <th>Release Date</th>
                <th>Rating</th> 
            </tr> 
        </thead> 
    </table> 
         
    <?php 
    return ob_get_clean(); 
}
 
add_shortcode ('product_datatables', 'product_datatables');

add_action('wp_ajax_product_datatables', 'datatables_server_side_callback');
add_action('wp_ajax_nopriv_product_datatables', 'datatables_server_side_callback');
 
 
function datatables_server_side_callback() {
 
    header("Content-Type: application/json");
 
    $request= $_GET;
 
    $columns = array(
		0 => 'post_image',
        1 => 'post_title',
        2 => 'release_date',
        3 => 'rating'
    );
 
 
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $request['length'],
        'offset' => $request['start'],
        'order' => $request['order'][1]['dir'],
    );
 
    if ($request['order'][1]['column'] == 1) {
 
        $args['orderby'] = $columns[$request['order'][1]['column']];
 
    } elseif ($request['order'][0]['column'] == 1 || $request['order'][0]['column'] == 2) {
 
        $args['orderby'] = 'meta_value_num';
 
        $args['meta_key'] = $columns[$request['order'][0]['column']];
    }
 
    //$request['search']['value'] <= Value from search
 
    if( !empty($request['search']['value']) ) { // When datatables search is used
        $args['meta_query'] = array(
            'relation' => 'OR',
            array(
                'key' => 'd_title',
                'value' => sanitize_text_field($request['search']['value']),
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'release_date',
                'value' => sanitize_text_field($request['search']['value']),
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'rating',
                'value' => sanitize_text_field($request['search']['value']),
                'compare' => 'LIKE'
            )
        );
    }
 
    $product_query = new WP_Query($args);
    $totalData = $product_query->found_posts;
 
    if ( $product_query->have_posts() ) {
         
        while ( $product_query->have_posts() ) {
         
            $product_query->the_post();
 
            $nestedData = array();
			$nestedData[] = get_the_post_thumbnail($page->ID,'16thumbnail-small');
            $nestedData[] = '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            $nestedData[] = get_field('release_date');
            $nestedData[] = get_field('rating');
 
            $data[] = $nestedData;
 
        }
 
        wp_reset_query();
 
        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalData),
            "data" => $data
        );
 
        echo json_encode($json_data);
 
    } else {
 
        $json_data = array(
            "data" => array()
        );
 
        echo json_encode($json_data);
    }
     
    wp_die();
 
}

//Custom Image sizes

// Add featured image sizes
add_image_size( '16thumbnail-small', 192, 108, true );
add_image_size( '16thumbnail-medium', 384, 216, true );

// Countdown Timer - Circle
//woocommerce
/**
 * Filter the cart template path to use our cart.php template instead of the theme's
 */
function po_relocate_template( $template, $template_name, $template_path ) {
 $basename = basename( $template );
 if( $basename == 'rating.php' ) {
 $template = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'templates/rating.php';
 }
 return $template;
}
add_filter( 'woocommerce_locate_template', 'po_relocate_template', 10, 3 );


//////////////////////////////////////////////////////////////////
// Countdown
//////////////////////////////////////////////////////////////////
if (! function_exists( 'po_countdown' ) ) :
	function po_countdown($atts, $content = null) {	
		extract(shortcode_atts(array(
				"year" => '',
				"month" => '',
				"day" => '',
				"hour" => '23',
				"minute" => '59',
		), $atts));
		
		// load scripts
		$rand_id = rand(1, 100);
		ob_start(); 		
		?>
		<?php
		$countdownscript = '
			jQuery(document).ready(function($) {
				$("#countdown_dashboard'.$rand_id.'").show();
			  	$("#countdown_dashboard'.$rand_id.'").countDown({
				  	targetDate: {
					    "day":  	'.$day.',
					    "month": 	'.$month.',
					    "year": 	'.$year.',
					  	"hour": 	'.$hour.',
					  	"min": 		'.$minute.',
					  	"sec": 		0
				  	},
				  	omitWeeks: true,
				  	onComplete: function() { $("#countdown_dashboard'.$rand_id.'").hide() }
											$("#released_txt").slideDown() }
			  	});
			});';
			wp_add_inline_script('rehub', $countdownscript);
		?>
		<div class="released" id="released_txt" style="display: none;">
			Released
		</div>
		<div id="countdown_dashboard<?php echo ''.$rand_id;?>" class="countdown_dashboard" data-day="<?php echo ''.$day;?>" data-month="<?php echo ''.$month;?>" data-year="<?php echo ''.$year;?>" data-hour="<?php echo ''.$hour;?>" data-min="<?php echo ''.$minute;?>"> 			  
			<div class="dash days_dash"> <span class="dash_title">days</span>
				<div class="digit">0</div>
				<div class="digit">0</div>
			</div>
			<div class="dash hours_dash"> <span class="dash_title">hours</span>
				<div class="digit">0</div>
				<div class="digit">0</div>
			</div>
			<div class="dash minutes_dash"> <span class="dash_title">minutes</span>
				<div class="digit">0</div>
				<div class="digit">0</div>
			</div>
			<div class="dash seconds_dash"> <span class="dash_title">seconds</span>
				<div class="digit">0</div>
				<div class="digit">0</div>
			</div>
		</div>
		<!-- Countdown dashboard end -->
		<div class="clearfix"></div>		

		<?php		
		$output = ob_get_contents();
		ob_end_clean();
		return $output;	
	   
	}
	add_shortcode("po_countdown", "po_countdown");
endif;

//Rank Math
if ( ! defined( 'WPINC' ) ) {
	die;
}
add_action( 'admin_enqueue_scripts', 'orm_load_rank_math_integration' );
/**
 * Loads rank-math-integration.js on post.php admin pages.
 */
function orm_load_rank_math_integration() {
	// if Oxygen is not active, abort.
	if ( ! function_exists( 'oxygen_vsb_current_user_can_access' ) ) {
		return;
	}
	// if Rank Math is not active, abort.
	if ( ! class_exists( 'RankMath' ) ) {
		return;
	}
	global $pagenow;
	global $post;
	// save global $post to restore later.
	$saved_post = $post;
	// exclude templates.
	if ( is_object( $post ) && 'ct_template' === $post->post_type ) {
		return;
	}
	if ( 'post.php' === $pagenow && ! is_null( $post ) ) {
		wp_enqueue_script( 'rank-math-integration', plugin_dir_url( __FILE__ ) . 'assets/js/rank-math-integration.js', array( 'rank-math-post-metabox' ), false, true );
		wp_localize_script( 'rank-math-integration', 'rm_data', array(
			'oxygen_markup' => do_shortcode( get_post_meta( $post->ID, 'ct_builder_shortcodes', true ) )
		) );
	}
	// restore original global post
	$post = $saved_post;
}