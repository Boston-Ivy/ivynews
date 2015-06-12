<?php
	class WPStockerWidgetPro extends WP_Widget {
	
		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'wp-stock-ticker-widget-pro', // Base ID
				'WP Stock Ticker Pro', // Name
				array( 'description' => __( 'Use this widget to place the WP Stock Ticker into one of your widget areas', 'text_domain' ), ) // Args
			);
		}
	
		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			echo $args['before_widget'];
			//show css, content, jQuery scriot one by one
			global $wp_stock_ticker;
			
			$scrollStr = $wp_stock_ticker->s_ticker_show_widget( $args['widget_id'] );
			echo $scrollStr;
						
			echo $args['after_widget'];
		}
	
		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			
		}
	
		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
		
		}
	}
	
	class WPStockerStaticWidgetPro extends WP_Widget {
	
		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'wp-stock-static-widget-pro', // Base ID
				'WP Stock Ticker Pro Static', // Name
				array( 'description' => __( 'Use this widget to place the WP Stock Ticker as static into one of your widget areas', 'text_domain' ), ) // Args
			);
		}
	
		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			echo $args['before_widget'];
			//show css, content, jQuery scriot one by one
			global $wp_stock_ticker;
			
			$scrollStr = $wp_stock_ticker->s_static_show_widget( $args['widget_id'] );
			echo $scrollStr;
						
			echo $args['after_widget'];
		}
	
		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			
		}
	
		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
		
		}
	}
?>