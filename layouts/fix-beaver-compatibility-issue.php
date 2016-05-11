<?php
/*
 *
 * A fix for a compatibility issue between Layouts
 * and Beaver page builder
 * YT: tssupp-1417
 *
 **/

add_action( 'init', 'fix_beaver' );

function fix_beaver(){
    $has_been_removed = false;
    add_action( 'ddl_before_frontend_render_cell', function ( $cell, $renderer ){
            if( $cell->get_cell_type() === 'cell-content-template' || 
                strpos($cell->get_content()['content'], 'wpv-post-body') === false ){
                    if( class_exists('FLBuilder') ){
                        remove_filter( 'the_content', 'FLBuilder::render_content' );
                        $has_been_removed = true;
                    }
            }
    } );

    add_action( 'ddl_after_frontend_render_cell', function( $cell, $renderer ){
        if( $has_been_removed ){
            add_filter( 'the_content', 'FLBuilder::render_content' );
            $has_been_removed = false;
        }
    });
}
?>