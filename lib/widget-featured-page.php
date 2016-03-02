<?php

final class Anita_Featured_Page_Widget extends WP_Widget
{
	//* Constructor. Set the default widget options and create widget.
	function __construct() 
	{
		$widget_title = 'Featured Page';

		$widget_info = array(
			'classname'   => 'featured-page-widget',
			'description' => 'Titel, tekst en een verwijzing naar een pagina',
		);

		parent::__construct( 'anita-featured-page-widget', $widget_title, $widget_info );
	}

	public function widget( $args, $instance ) 
	{
		/** 
		 * Combine $instance data with defaults
		 * Then extract variables of this array
		 */
        extract( wp_parse_args( $instance, array( 
            'title' => '',
            'text' => '',
            'post_id' => '',
        )));

        /* Abort if no valid post_id */
        if ( empty($post_id) || !($url = get_permalink($post_id)) )
       		return;

        /* Run $text through filter */
		$text = apply_filters( 'widget_text', $text, $instance, $this );
		?>

		<?php echo $args['before_widget']; ?>

		<header class="entry-header">
			<?php echo $args['before_title'] . '<a href="'.esc_url($url).'">' . $title . '</a>' . $args['after_title']; ?>
		</header>

		<div class="entry-content"><?php echo wpautop($text); ?></div>

		<?php echo $args['after_widget']; ?>

		<?php
	}

 	public function form( $instance ) 
 	{
		/** 
		 * Combine $instance data with defaults
		 * Then extract variables of this array
		 */
        extract( wp_parse_args( $instance, array( 
            'title' => '',
            'text' => '',
            'post_id' => '',
        )));

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:') ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" rows="5"><?php echo $text; ?></textarea>
		</p>
		<?php

		//* Get all pages
		$all_pages = get_pages();

		?>
		<p>
			<label for="<?php echo $this->get_field_id('post_id'); ?>">Linken naar pagina:</label>
			<select name="<?php echo $this->get_field_name('post_id'); ?>" id="<?php echo $this->get_field_id('post_id'); ?>" class="widefat">
				<?php
				//* Show all pages as an option
				foreach ($all_pages as $page) {
					printf( 
						'<option value="%s" %s>%s</option>',
						$page->ID,
						selected($post_id, $page->ID, false),
						$page->post_title
					);
				} 
				?>
			</select>
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) 
	{
		/* Store old instance as defaults */
		$instance = $old_instance;

		/* Store new title */
		$instance['title'] = strip_tags( $new_instance['title'] );

		/* Store text */
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = wp_kses_post( stripslashes( $new_instance['text'] ) );

		/* Store post_id */
		$instance['post_id'] = $new_instance['post_id'];

		/* Return updated instance */
		return $instance;
	}
}