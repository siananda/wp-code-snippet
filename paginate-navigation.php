if ( ! function_exists( 'tj_paginate_nav' ) ) :
	function tj_paginate_nav( $tjQuery = null ) {

		if( empty( $tjQuery ) ) :
			$tjQuery = $GLOBALS['wp_query'];
		endif;

		// Don't print empty markup if there's only one page.
		if ( $tjQuery->max_num_pages < 2 ) {
			return;
		}

		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

		// Set up paginated links.
		$links = paginate_links( array(
			'base'     => $pagenum_link,
			'format'   => $format,
			'total'    => $tjQuery->max_num_pages,
			'current'  => $paged,
			'add_args' => array_map( 'urlencode', $query_args ),
			'prev_text' => '<i class="fa fa-angle-left" aria-hidden="true"></i>',
			'next_text' => '<i class="fa fa-angle-right" aria-hidden="true"></i>',
			'type'      => 'array',
		) );

		if ( $links ) :

		?>
		<div class="pagination-area">
			<ul class="">
				<?php foreach ( $links as $key => $page_link ) : ?>
					<li class="<?php if ( strpos( $page_link, 'current' ) !== false ) { echo ' active'; } ?>"><?php echo str_replace('span', 'a', $page_link)?></li>
				<?php endforeach ?>
			</ul>
		</div><!-- .navigation -->
		<?php
		endif;
	}
endif;
