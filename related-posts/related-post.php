<?php

/*
 * Plugin Name: Related posts
 * Description: Shows related posts for a post
 * Version: 1.0
 * Author: Aqib Gatoo
 * Author URI: http://aqibgatoo.com
 * License: GPLv2
*/

//filter to filter content :D
add_filter( 'the_content', 'my_related_posts_cb' );

function my_related_posts_cb( $content ) {

	if ( !is_singular( 'post' ) ) {
		return $content;
	}
	$id = get_the_ID();

	$terms = get_the_terms( $id, 'category' );

	$categories = array();
	foreach ( $terms as $term ) {
		$categories[] = $term->cat_ID;
	}


//	print_r( $terms );
//	wp_die( 'die' );


	$loop = new WP_Query( array(
		'posts_per_page' => 2,
		'category__in' => $categories,
		'orderby' => 'rand',
		'post__not_in' => array( $id )
	) );


	if ( $loop > have_posts() ) {

		$content .= "<div><h3>Related posts</h3><ul>";

		while ( $loop->have_posts() ) {

			$loop->the_post();
			$content .= '<li><a href=" ' . get_permalink() . '">' . get_the_title() . '</a></li>';


		}
		wp_reset_query();
		$content .= '</ul></div>';
	}

	return $content;
}