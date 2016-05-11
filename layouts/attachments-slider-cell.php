<?php

/**
 *
 * Creating a custom Cell Layouts with its API
 * It fetch all attachments from the current post
 * and display it like a BS carousel
 *
 **/

add_action( 'init', 'register_attachments_slider_cell_init' );
        
function register_attachments_slider_cell_init() {
    if (function_exists('register_dd_layout_cell_type')) {
        register_dd_layout_cell_type('attachments-slider', 
            array(
                'name' => __('Attachments Slider', 'ddl-layouts'),
                'cell-image-url' => DDL_ICONS_SVG_REL_PATH . 'layouts-slider-cell.svg',
                'description' => __('Display an image slider, built using the Bootstrap Carousel component.', 'ddl-layouts'),
                'category' => __('Fields, text and media', 'ddl-layouts'),
                'button-text' => __('Assign slider cell', 'ddl-layouts'),
                'dialog-title-create' => __('Create a new slider cell', 'ddl-layouts'),
                'dialog-title-edit' => __('Edit slider cell', 'ddl-layouts'),
                'dialog-template-callback' => 'slider_attachment_cell_dialog_template_callback',
                'cell-content-callback' => 'slider_attachment_cell_content_callback',
                'cell-template-callback' => 'slider_attachment_cell_template_callback',
                'cell-class' => '',
                'has_settings' => true,
                'preview-image-url' => DDL_ICONS_PNG_REL_PATH . 'slider_expand-image.png',
                'translatable_fields' => array(
                    'slider[slide_url]' => array('title' => 'Slide URL', 'type' => 'LINE'),
                    'slider[slide_title]' => array('title' => 'Slide title', 'type' => 'LINE'),
                    'slider[slide_text]' => array('title' => 'Slide description', 'type' => 'AREA')
                ),
                'register-scripts' => array(
                    array('ddl-slider-cell-script', WPDDL_GUI_RELPATH . 'editor/js/ddl-slider-cell-script.js', array('jquery'), WPDDL_VERSION, true),
                ),
            )
        );
    }
}


function slider_attachment_cell_dialog_template_callback() {
    ob_start();

    return ob_get_clean();
}


// Callback function for displaying the cell in the editor.
function slider_attachment_cell_template_callback() {
    ob_start();
    ?>
    <div class="cell-content">
        <p class="cell-name"><?php _e('Attachments Slider', 'ddl-layouts'); ?></p>
        <div class="cell-preview">
            <div class="ddl-slider-preview">
                <img src="<?php echo WPDDL_RES_RELPATH . '/images/cell-icons/slider.svg'; ?>" height="130px">
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Callback function for display the cell in the front end.
function slider_attachment_cell_content_callback() {

	global $post;

    $attachments = get_children(
	  array(
	    'post_type' => 'attachment',
	    'post_parent' => $post->ID
	  )
	);
    $items_count = 0;
    ob_start();
    ?>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

	  <!-- Wrapper for slides -->
	  <div class="carousel-inner" role="listbox">
	  	<?php foreach ($attachments as $attachment) { ?>
	    <div class="item <?php if($items_count == 0) echo 'active' ?>">
	      <img src="<?php echo $attachment->guid ?>">
	    </div>
	    <?php
	    	$items_count++;
	    ?>
	    <?php } ?>
	  </div>

	  <!-- Controls -->
	  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
	    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	    <span class="sr-only">Previous</span>
	  </a>
	  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
	    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	    <span class="sr-only">Next</span>
	  </a>
	</div>

    <?php
    return ob_get_clean();
}