<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( !class_exists( 'Mona_Youtube_Downloader_Widget' ) ){
	class Mona_Youtube_Downloader_Widget extends WP_Widget {
		function __construct(){
			parent::__construct(
				'Mona_Youtube_Downloader_Widget',
				'[Mona] Youtube Downloader Widget',
				array( 'description'  =>  __( 'Show Youtube Downloader form.' ) )
			);
		} 
		function form( $instance ){ 
			$default = array(
				'title' => __( 'Youtube Downloader' ),
				'text_search' => __( 'Get' ),
			);
			$instance = wp_parse_args( (array) $instance, $default );
			$title = esc_attr($instance['title']);
			$text_search = esc_attr($instance['text_search']);
	 
			echo '<p>Title: <input type="text" class="widefat" name="'.$this->get_field_name('title').'" value="'.$title.'" /></p>';
			echo '<p>Search text: <input type="text" class="widefat" name="'.$this->get_field_name('text_search').'" value="'.$text_search.'" /></p>';
		} 
		function update( $new_instance, $old_instance ){
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['text_search'] = strip_tags($new_instance['text_search']);
			return $instance;
		}
		function widget( $args, $instance ){	
			$title = apply_filters( 'widget_title', $instance['title'] ); 
			$text_search = @$instance['text_search'];
			
			echo $args['before_widget']; 
			
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			
			echo '<div class="mona-youtube-downloader-widget">'.do_shortcode( '[mona_youtube_downloader text="'.$text_search.'"]' ).'</div>';
			
			echo $args['after_widget'];		
		}
	}	
}