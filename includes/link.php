<?php if ( ! defined( 'EV_FW' ) ) die( 'Forbidden' );

// TODO:
function ev_link_partial( $handle, $link ) {
    if ( $link === false ) {
        return;
    }

    $link_class = 'ev-link-ctrl';

    if ( isset( $link['url'] ) && ! empty( $link['url'] ) ) {
        $link_class .= ' ev-link-on';
    }

    $nonce = ''; // TODO:

    $url = isset( $link['url'] ) ? $link['url'] : '';
    $target = isset( $link['target'] ) ? $link['target'] : '';
    $rel = isset( $link['rel'] ) ? $link['rel'] : '';
    $title = isset( $link['title'] ) ? $link['title'] : '';

    $link_hidden_inputs = sprintf( '<input data-link-url type="hidden" value="%s" name="%s[url]">', esc_attr( $url ), esc_attr( $handle ) );
    $link_hidden_inputs .= sprintf( '<input data-link-target type="hidden" value="%s" name="%s[target]">', esc_attr( $target ), esc_attr( $handle ) );
    $link_hidden_inputs .= sprintf( '<input data-link-rel type="hidden" value="%s" name="%s[rel]">', esc_attr( $rel ), esc_attr( $handle ) );
    $link_hidden_inputs .= sprintf( '<input data-link-title type="hidden" value="%s" name="%s[title]">', esc_attr( $title ), esc_attr( $handle ) );

    printf( '<span class="%s" data-nonce="%s"><span class="screen-reader-texta">%s</span>%s</span>',
        esc_attr( $link_class ),
        esc_attr( $nonce ),
        esc_html( __( 'Link', 'ev_framework' ) ),
        $link_hidden_inputs
    );
}

function ev_link_modal_load() {
    if ( ! isset( $_POST['data'] ) ) {
        die();
    }

    $data = $_POST['data'];

    $url = isset( $data['url'] ) ? $data['url'] : '';
    $target = isset( $data['target'] ) ? $data['target'] : '';
    $rel = isset( $data['rel'] ) ? $data['rel'] : '';
    $title = isset( $data['title'] ) ? $data['title'] : '';

    $content = '';

    $content .= sprintf( '<input type="text" name="url" value="%s" placeholder="URL">', esc_attr( $url ) );

    $content .= ev_select(
        'target',
        array(
            '' => __( 'Same tab', 'ev_framework' ),
            '_blank' => __( 'New tab', 'ev_framework' ),
        ),
        $target,
        false
    );

    $content .= sprintf( '<input type="text" name="rel" value="%s" placeholder="rel">', esc_attr( $rel ) );
    $content .= sprintf( '<input type="text" name="title" value="%s" placeholder="title">', esc_attr( $title ) );

    $m = new Ev_SimpleModal( 'ev-link' );
    $m->render( $content );

    die();
}

add_action( 'wp_ajax_ev_link_modal_load', 'ev_link_modal_load' );
