<?php
/*
Plugin Name: TestPlug
Plugin URI: Nettuts
Description: just for learning purposes
Author: Shaad
Author URI: 
version: 1.0
*/

/*add_filter('the_title', function($content) {
	return ucwords($content);
});


add_filter('the_content', function($content1){
	return $content1. ' '. time();
});


add_action('wp-footer', function(){
	echo 'hello from footer';
});

add_action('comment_post', function(){
	$email = get_bloginfo('admin_email');
	wp_mail(
		$email,
		'New comment posted',
		'A new comment has been left on ur blog'
		);
}); */

add_filter('the_content', function($content) {

	$id = get_the_id();


	if( !is_singular('post')){
		return $content;
	}

	$terms = get_the_terms($id, 'category');
	//print_r($terms);

	$cats =  array();

	foreach ($terms as $term) {
		$cats[] = $term->cat_ID;
	}

	$loop = new WP_Query(
		array(
			'posts_per_page'	=> 3,
			'category__in' 		=> $cats,
			'orderby' 			=> 'rand',
			'post__not_in' 		=> array($id)
			)
		);

if($loop -> have_posts()){
	$content .= '
	<h2>You may also like.. </h2>
	<ul class = "related-category-posts">';

	while ($loop -> have_posts()) {
		$loop -> the_post();

		$content .= '
		<li>
			<a href = "'.get_permalink().'">'.get_the_title().'</a>
		</li>';
	}

	$content .= '</ul>';

	wp_reset_query();
}

return $content;

});


