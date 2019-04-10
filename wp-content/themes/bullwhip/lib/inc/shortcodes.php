<?php
add_shortcode('button','msd_child_button_function');
function msd_child_button_function($atts, $content = null){	
	extract( shortcode_atts( array(
      'url' => null,
	  'target' => '_self'
      ), $atts ) );
	$ret = '<div class="button-wrapper">
<a class="button" href="'.$url.'" target="'.$target.'">'.remove_wpautop($content).'</a>
</div>';
	return $ret;
}
add_shortcode('hero','msd_child_landing_page_hero');
function msd_child_landing_page_hero($atts, $content = null){
	$ret = '<div class="hero">'.remove_wpautop($content).'</div>';
	return $ret;
}
add_shortcode('callout','msd_child_landing_page_callout');
function msd_child_landing_page_callout($atts, $content = null){
	$ret = '<div class="callout">'.remove_wpautop($content).'</div>';
	return $ret;
}
function column_shortcode($atts, $content = null){
	extract( shortcode_atts( array(
	'cols' => '3',
	'position' => '',
	), $atts ) );
	switch($cols){
		case 5:
			$classes[] = 'one-fifth';
			break;
		case 4:
			$classes[] = 'one-fouth';
			break;
		case 3:
			$classes[] = 'one-third';
			break;
		case 2:
			$classes[] = 'one-half';
			break;
	}
	switch($position){
		case 'first':
		case '1':
			$classes[] = 'first';
		case 'last':
			$classes[] = 'last';
	}
	return '<div class="'.implode(' ',$classes).'">'.$content.'</div>';
}

add_shortcode('columns','column_shortcode');

add_shortcode('carousel','msd_bootstrap_carousel');
function msd_bootstrap_carousel($atts){
	$slidedeck = new SlideDeck();
	extract( shortcode_atts( array(
	'id' => NULL,
	), $atts ) );
	$sd = $slidedeck->get($id);
	$slides = $slidedeck->fetch_and_sort_slides( $sd );
	$i = 0;
	foreach($slides AS $slide){
		$active = $i==0?' active':'';
		$items .= '
		<div style="background: url('.$slide['image'].') center top no-repeat #000000;background-size: cover;" class="item'.$active.'">
			<div class="carousel-caption">
				'.$slide['content'].'
			</div>
		</div>';
		$i++;
	}
	return msd_carousel_wrapper($items,array('id' => $id));
}

function msd_carousel_wrapper($slides,$params = array()){
	extract( array_merge( array(
	'id' => NULL,
	'navleft' => '‹',
	'navright' => '›'
			), $params ) );
			return '
<div class="carousel slide" id="myCarousel_'.$id.'">
	<div class="carousel-inner">'.($slides).'</div>
	<a data-slide="prev" href="#myCarousel_'.$id.'" class="left carousel-control">'.$navleft.'</a>
	<a data-slide="next" href="#myCarousel_'.$id.'" class="right carousel-control">'.$navright.'</a>
</div>';
}