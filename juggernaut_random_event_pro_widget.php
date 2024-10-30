<?php
	/*
		Random Events Pro v2.0
		by Andrew Kaser & Tom Lambert
	*/
	class juggernaut_random_event_pro_widget extends WP_Widget
	{
		/*
			When setting up the widget, this is the function that displays the drop down menu and instructions for picking your Random Event.
		*/
		public function form( $instance )
		{
			if ( isset( $instance['jre_widget_event'] ) ) { $jre_widget_event = $instance['jre_widget_event']; } else { $jre_widget_event = 'none'; }
			?>
			<p>Please select which event you would like to configure here</p>
				<select name="<?php echo $this->get_field_name( 'jre_widget_event' ); ?>" >
					<option value="none" <?php selected( $jre_widget_event, 'none' ); ?>>None...</option>
					<?php
						$jre_titles = jre_get_random_events_widgets();
						echo var_dump($jre_titles);
						while ( $jre_titles->have_posts() ) : $jre_titles->the_post();
						$jre_id = get_the_ID(); ?>
						<option value="<?php the_ID(); ?>" <?php selected( $jre_widget_event, $jre_id ); ?> > <?php the_title(); ?> </option>
						<?php
					endwhile; ?>
				</select>
			<?php
		}

		/*
			Display the widget and all the customization options that are set for that Random Event
		*/
		public function widget( $args, $instance )
		{
			extract( $args );
			$jre_widgets_args = array(
				'post_type'	=> 'random_event',
				'post__in'	=> array( $instance['jre_widget_event'] ),
				'orderby'	=> 'meta_value',
				'order'		=> 'ASC'
			);

			$jre_widgets = new WP_Query( $jre_widgets_args );
			$jre_widget_meta = get_post_meta($instance['jre_widget_event']);
			$jre_instance_chance = isset($jre_widget_meta['jre_chance']) ? $jre_widget_meta['jre_chance'] : '0';
			$jre_title_background_color = isset($jre_widget_meta['jre_title_background_color']) ? $jre_widget_meta['jre_title_background_color'] : '';
			$jre_title_font_color = isset($jre_widget_meta['jre_title_font_color']) ? $jre_widget_meta['jre_title_font_color'] : '';
			$jre_description_background_color = isset($jre_widget_meta['jre_description_background_color']) ? $jre_widget_meta['jre_description_background_color'] : '';
			$jre_description_font_color = isset($jre_widget_meta['jre_description_font_color']) ? $jre_widget_meta['jre_description_font_color'] : '';
			$jre_cta_font_color = isset($jre_widget_meta['jre_cta_font_color']) ? $jre_widget_meta['jre_cta_font_color'] : '';
			$jre_views = $jre_widget_meta['jre_views'][0];

			if ( $jre_views == '' ) { $jre_views = 0; }
			if ( $jre_widget_meta['jre_enabled'][0] === 'enabled' )
			{
				if ( jre_roll_the_dice_widget( $jre_instance_chance[0] ) == TRUE )
				{
					echo $before_widget;
					while ( $jre_widgets->have_posts() ) : $jre_widgets->the_post();
						if ( isset( $jre_widget_meta['_thumbnail_id'][0] ) )
							{ $instance_image = '<img class="jre_image" src="'.wp_get_attachment_url( $jre_widget_meta['_thumbnail_id'][0]).'" />'; } else { $instance_image = ''; }

						if ( isset( $jre_widgets->post_content ) )
							{ $instance_description = $jre_widgets->post_content; } else { $instance_description = ''; }

						if ( isset( $jre_widget_meta['jre_cta'] ) )
							{ $instance_cta = $jre_widget_meta['jre_cta']; } else { $instance_cta = 'Get It Now!'; }

						if ( isset( $jre_widgets->jre_landing_page ) )
							{ $instance_landing_page = $jre_widgets->jre_landing_page; } else { $instance_landing_page = 'http://juggernautplugins.com'; }

						$jre_container = '<div class="jre_widget" id="jre_container" style="color:'.$jre_description_font_color[0].'; background-color:'.$jre_description_background_color[0].';">
							<h3 class="jre_title" style="color:'.$jre_title_font_color[0].'; background-color:'.$jre_title_background_color[0].';">'.get_the_title().'</h3>
							 '.$instance_image.'
							<p class="jre_description" style="color:'.$jre_description_font_color[0].'; background-color:'.$jre_description_background_color[0].';">'.get_the_content().'</p>
							<a href="'.$instance_landing_page[0].'" class="jre_cta" style="color:'.$jre_cta_font_color[0].';">'. $instance_cta[0] .'</a>
						</div>';
						echo $jre_container;
						$jre_new_views = $jre_views + 1;
						update_post_meta( $instance['jre_widget_event'], 'jre_views', $jre_new_views );
					endwhile;
					echo $after_widget;
				}
			}
		}

		// PARTYS OVER GUYS no more fun stuff. 
		public function __construct()
		{
			parent::__construct(
	 			'juggernaut_random_event_pro',
				'Juggernaut Random Event Pro',
				array( 'description' => 'Select random event to display' )
			);
		}

		public function juggernaut_random_event_pro()
		{
			$widget_ops = array(
				'classname' => 'juggernaut_random_event_pro',
				'description' => 'Display post from category'
			);
			$control_ops = array(
				'width' => 200,
				'height' => 250,
				'id_base' => 'juggernaut_random_event_pro'
			);
			$this->WP_Widget( 'juggernaut_random_event_pro', 'Juggernaut Random Events Pro', $widget_ops, $control_ops );
		}

		public function update( $new_instance, $old_instance ) 
		{
			$instance = array();
			$instance['jre_widget_event'] = $new_instance['jre_widget_event'] ;
			return $instance;
		}
	}

	add_action( 'widgets_init', create_function( '', 'register_widget( "juggernaut_random_event_pro_widget" );' ) );
?>