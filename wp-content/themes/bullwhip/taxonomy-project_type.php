<?php
remove_action('genesis_post_content', 'genesis_do_post_content');
add_action('genesis_before_loop','msd_taxonomy_description');
add_action('genesis_before_loop','msd_before_loop');
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'msd_standard_loop' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_post_title', 'genesis_do_post_title' );
add_action( 'genesis_entry_header', 'msd_do_post_title' );
add_action( 'genesis_post_title', 'msd_do_post_title' );
add_action('genesis_after_loop','msd_after_loop');
//add_action('genesis_before_loop','msd_taxonomy_children');
add_filter('genesis_post_title_text','msd_add_arrow_to_title');
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_action('genesis_sidebar','msd_sidebar_taxonomy_menu');
genesis();

function msd_add_arrow_to_title($title){
    $title = $title . ' <i class="icon-circle-arrow-right"></i>';
    return $title;
}
function msd_before_loop(){
    print '<ul class="archive-list">';
}
function msd_after_loop(){
    print '</ul>';
}
function msd_do_post_title() {

    $title = apply_filters( 'genesis_post_title_text', get_the_title() );

    if ( 0 === mb_strlen( $title ) )
        return;

    //* Link it, if necessary
    if ( ! is_singular() && apply_filters( 'genesis_link_post_title', true ) )
        $title = sprintf( '<a href="%s" title="%s" rel="bookmark">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), $title );

    //* Wrap in H1 on singular pages
    $wrap = 'h4';

    //* Also, if HTML5 with semantic headings, wrap in H1
    $wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h4' : $wrap;

    //* Build the output
    $output = genesis_markup( array(
        'html5'   => "<{$wrap} %s>",
        'xhtml'   => sprintf( '<%s class="entry-title">%s</%s>', $wrap, $title, $wrap ),
        'context' => 'entry-title',
        'echo'    => false,
    ) );

    $output .= genesis_html5() ? "{$title}</{$wrap}>" : '';

    echo apply_filters( 'genesis_post_title_output', "$output \n" );

}
function msd_standard_loop() {
    //* Use old loop hook structure if not supporting HTML5
    if ( ! genesis_html5() ) {
        msd_legacy_loop();
        return;
    }

    if ( have_posts() ) : while ( have_posts() ) : the_post();

            do_action( 'genesis_before_entry' );

            printf( '<article %s>', genesis_attr( 'entry' ) );

                do_action( 'genesis_entry_header' );

                do_action( 'genesis_before_entry_content' );
                printf( '<div %s>', genesis_attr( 'entry-content' ) );
                    do_action( 'genesis_entry_content' );
                echo '</div>'; //* end .entry-content
                do_action( 'genesis_after_entry_content' );

                do_action( 'genesis_entry_footer' );

            echo '</article>';

            do_action( 'genesis_after_entry' );

        endwhile; //* end of one post
        do_action( 'genesis_after_endwhile' );

    else : //* if no posts exist
        do_action( 'genesis_loop_else' );
    endif; //* end loop

}
function msd_legacy_loop() {

    global $loop_counter,$project_info;

    $loop_counter = 0;

    if ( have_posts() ) : while ( have_posts() ) : the_post();
        do_action( 'genesis_before_post' );

        printf( '<li class="%s">', join( ' ', get_post_class() ) );

            do_action( 'genesis_before_post_title' );
            do_action( 'genesis_post_title' );
            do_action( 'genesis_after_post_title' );

            do_action( 'genesis_before_post_content' );
            echo '<div class="entry-content">';
                do_action( 'genesis_post_content' );
            echo '</div>'; //* end .entry-content
            do_action( 'genesis_after_post_content' );

        echo '</li>'; //* end .entry

        do_action( 'genesis_after_post' );
        $loop_counter++;

    endwhile; //* end of one post
        do_action( 'genesis_after_endwhile' );

    else : //* if no posts exist
        do_action( 'genesis_loop_else' );
    endif; //* end loop

}

?>
