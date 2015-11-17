<?php if ( ! defined( 'EV_FW' ) ) die( 'Forbidden' );

/**
 * Display a partial to integrate the link functionality in a framework field.
 *
 * @since 0.4.0
 * @param string $handle The field base handle.
 * @param array $link The link data.
 */
function ev_link_partial( $handle, $link ) {
	if ( ! isset( $link['link'] ) ) {
		$link['link'] = array();
	}

	$link = $link['link'];
	$handle .= '[link]';
	$link_class = 'ev-link-ctrl ev-tooltip';

	if ( isset( $link['url'] ) && ! empty( $link['url'] ) ) {
		$link_class .= ' ev-link-on';
	}

	$url = isset( $link['url'] ) ? $link['url'] : '';
	$target = isset( $link['target'] ) ? $link['target'] : '';
	$rel = isset( $link['rel'] ) ? $link['rel'] : '';
	$title = isset( $link['title'] ) ? $link['title'] : '';

	$link_hidden_inputs = sprintf( '<input data-link-url type="hidden" value="%s" name="%s[url]">', esc_attr( $url ), esc_attr( $handle ) );
	$link_hidden_inputs .= sprintf( '<input data-link-target type="hidden" value="%s" name="%s[target]">', esc_attr( $target ), esc_attr( $handle ) );
	$link_hidden_inputs .= sprintf( '<input data-link-rel type="hidden" value="%s" name="%s[rel]">', esc_attr( $rel ), esc_attr( $handle ) );
	$link_hidden_inputs .= sprintf( '<input data-link-title type="hidden" value="%s" name="%s[title]">', esc_attr( $title ), esc_attr( $handle ) );

	printf( '<span class="%s" data-nonce="%s" data-title="%s"><span class="screen-reader-text">%s</span>%s</span>',
		esc_attr( $link_class ),
		esc_attr( wp_create_nonce( 'ev_link' ) ),
		esc_attr( $url ),
		esc_html( __( 'Link', 'ev_framework' ) ),
		$link_hidden_inputs
	);
}

/**
 * Populate the link editing modal.
 *
 * @since 0.4.0
 */
function ev_link_modal_load() {
	if ( ! ev_is_post_nonce_valid( 'ev_link' ) ) {
		die();
	}

	if ( ! isset( $_POST['data'] ) ) {
		die();
	}

	$data = $_POST['data'];

	$url    = isset( $data['url'] ) ? $data['url'] : '';
	$target = isset( $data['target'] ) ? $data['target'] : '';
	$rel    = isset( $data['rel'] ) ? $data['rel'] : '';
	$title  = isset( $data['title'] ) ? $data['title'] : '';

	$content = '';
	$content .= '<div class="ev-link-url-wrapper">';
	   $content .= sprintf( '<input type="text" name="url" value="%s" placeholder="URL">', esc_attr( $url ) );
		$content .= sprintf( '<span class="ev-link-trigger"><span>%s</span></span>', esc_html( __( 'Tab', 'ev_framework' ) ) );
	$content .= '</div>';

	$content .= '<div class="ev-link-inner-wrapper">';
		$content .= '<div class="ev-link-field-row">';
			$content .= '<div class="ev-link-radio-wrapper">';
				$content .= sprintf( '<p>%s</p>', esc_html( __( 'Open in tab', 'ev_framework' ) ) );
				$content .= ev_radio(
					'target',
					array(
						''       => __( 'Same tab', 'ev_framework' ),
						'_blank' => __( 'New tab', 'ev_framework' ),
					),
					$target,
					'switch',
					false
				);
		$content .= '</div>';

			$content .= sprintf( '<input type="text" name="rel" value="%s" placeholder="rel">', esc_attr( $rel ) );
		$content .= '</div>';

		$content .= '<div class="ev-link-field-row">';
			$content .= sprintf( '<input type="text" name="title" value="%s" placeholder="title">', esc_attr( $title ) );
		$content .= '</div>';
	$content .= '</div>';

	$m = new Ev_SimpleModal( 'ev-link' );
	$m->render( $content );

	die();
}

add_action( 'wp_ajax_ev_link_modal_load', 'ev_link_modal_load' );

/**
 * Print a link.
 *
 * @since 0.4.0
 * @param array $data The link data.
 * @param string $content The link content.
 * @param boolean $echo Set to true to echo the link.
 * @return string
 */
function ev_link( $data, $content, $echo = true ) {
	if ( ! isset( $data['url'] ) || empty( $data['url'] ) ) {
		if ( $echo ) {
			echo $content;
		}
		else {
			return $content;
		}
	}

	$url    = isset( $data['url'] ) ? $data['url'] : '';
	$target = isset( $data['target'] ) ? $data['target'] : '';
	$rel    = isset( $data['rel'] ) ? $data['rel'] : '';
	$title  = isset( $data['title'] ) ? $data['title'] : '';

	$link = sprintf( '<a href="%s"', esc_attr( $url ) );

	if ( $target ) {
		$link .= sprintf( ' target="%s"', esc_attr( $target ) );
	}

	if ( $rel ) {
		$link .= sprintf( ' rel="%s"', esc_attr( $rel ) );
	}

	if ( $title ) {
		$link .= sprintf( ' title="%s"', esc_attr( $title ) );
	}

	$link .= '>';
	$link .= $content;
	$link .= '</a>';

	if ( $echo ) {
		echo $content;
	}

	return $content;
}