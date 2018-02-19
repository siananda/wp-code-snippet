<?php

	if ( ! function_exists( 'tj_get_related_items' ) ) :
		/*---------------------------------------------
		* TJ Any Post type Related Post Data - Started
		* @return array $TJitems
		----------------------------------------------*/
		function tj_get_related_items( $not_in = 0, $related_key ='category', $key_type ='default', $item_num=3, $order= 'DESC', $orderby= 'date' ){
			
			$cat_trms = wp_get_object_terms( $not_in, $related_key );

			$cat =array();
			foreach ($cat_trms as $trms) :
				$cat[] = $trms->term_id;
			endforeach;

			$post_type = get_post_type($not_in);

			$tj_query = array (
			    'post_type'              => $post_type,
			    'post_status'            => array( 'publish' ),
			    'posts_per_page'         => $item_num,
		        'orderby' => $orderby,
		        'order' => $order,
                'post__not_in' => array($not_in),
			);
			switch ($key_type) :
				case 'tax_query':
					$tj_query['tax_query'] = array(
							array(
								'taxonomy' 			=> $related_key,
								'field' 		=> 'term_id',
								'terms' 			=> $cat,
							)
						);
					break;
				case 'meta_query':
					$meta_value = get_post_meta($not_in, $related_key , true);
					$tj_query['meta_key'] = $related_key;
					$tj_query['meta_query'] = array(
							array(
								'key' 			=> $related_key,
								'value' 		=> $meta_value,
							),

						);
					break;
				default:
					$tj_query['cat'] = $cat;
					break;
			endswitch;

			// The Query 
			$tj_query = new WP_Query( $tj_query );
			$format = "M d, Y";

			$TJtj_query = array();

			if($tj_query->have_posts()):
			    while ($tj_query->have_posts()):
			        $tj_query->the_post();
			        $TJid = get_the_id();
			        $TJitems[] = array(
			        		'title'  	=> get_the_title(),
			                'image'  => wp_get_attachment_url( get_post_thumbnail_id($TJid)),
			                'url'  => get_the_permalink($TJid),
			                'date'			=> get_the_date($format),
			            );
			    endwhile;
			endif;
			// Restore original Post Data
			wp_reset_postdata();

			//echo "<pre>"; print_r($TJitems);
			
			return $TJitems;
		}
		/*-----------------------------------------
		* TJ Any Post type Related Post Data - End
		-------------------------------------------*/
	endif;