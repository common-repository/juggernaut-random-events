<?php
	/*
	Plugin Name: Random Events Pro by JuggernautPlugins.com
	Plugin URI: http://JuggernautPlugins.com
	Description: Create random events anywhere on your website to get your visitors excited while increasing your page views, time on site, and customer loyalty. You can give away anything you want to reward visitors with a free report, product, software license or coupon code with Juggernaut Random Events. You also get to choose the how often your random events are triggered.
	Version: 2.1
	Author: JuggernautPlugins, kaser
	Author URI: http://www.JuggernautPlugins.com/
	Text-Domain: jre
	*/

	/*
		our widget is awesome, and we seriously need to make sure it's included.
	*/
	include('juggernaut_random_event_pro_widget.php');
	
	/*
		We use jQuery to close our pop up windows, so we need to make sure it's included incase the active theme doesn't have it
	*/
	function jre_scripts()
	{
		wp_enqueue_script( 'jquery' );
	}
	add_action( 'wp_enqueue_scripts', 'jre_scripts' );

	/*
		Determine if the random event is triggered or not
	*/
	function jre_roll_the_dice( $chance, $location )
	{
		$rando = 0;
		$bait = 0;
		$limit = 100 / $chance;
		$rando = mt_rand( 1, $limit );
		$bait = '1';

		if ( $rando == $bait ) { return true; } else { return false; }
	}

	/*
		The widget uses slightly different math for determining if the random event is triggered.
		Although I'm not sure why at the time of writing this comment.
	*/
	function jre_roll_the_dice_widget( $chance )
	{
		$limit = 100 / $chance;
		$rando = mt_rand( 1, $limit );
		$bait = '1';

		if ( $rando == $bait ) { return true; } else { return false; }
	}

	/*
		at wp_footer() we run this function to collect all data for enabled random events
		and roll_the_dice() with them. 
	*/
	function get_jre_lists()
	{
		$jre_lists_args = array(
			'post_type'		=> 'random_event',
			'orderby'		=> 'meta_value',
			'order'			=> 'ASC',
			'meta_query' => array(
				array(
					'key'		=> 'jre_location',
					'value'		=> array( 'widget' ),
					'compare'	=> 'NOT IN',
				),
			)
		);

		$jre_windows = get_posts( $jre_lists_args );
		$modal_active = 0; // set to 0, if a modal pop up displays, we will set it to 1.
		$jre_container = '';

		foreach ( $jre_windows as $jre_event ) : setup_postdata( $jre_event );

			$jre_window_meta = get_post_meta( $jre_event->ID );

			$jre_window_chance = isset( $jre_window_meta['jre_chance'][0] ) ? $jre_window_meta['jre_chance'][0] : '';
			$jre_window_location = isset( $jre_window_meta['jre_location'][0] ) ? $jre_window_meta['jre_location'][0] : '';
			$jre_title_background_color = isset( $jre_window_meta['jre_title_background_color'] ) ? $jre_window_meta['jre_title_background_color'] : '';
			$jre_title_font_color = isset( $jre_window_meta['jre_title_font_color'] ) ? $jre_window_meta['jre_title_font_color'] : '';
			$jre_description_background_color = isset( $jre_window_meta['jre_description_background_color'] ) ? $jre_window_meta['jre_description_background_color'] : '';
			$jre_description_font_color = isset( $jre_window_meta['jre_description_font_color'] ) ? $jre_window_meta['jre_description_font_color'] : '';
			$jre_cta_font_color = isset( $jre_window_meta['jre_cta_font_color'] ) ? $jre_window_meta['jre_cta_font_color'] : '';
			$jre_nty_font_color = isset( $jre_window_meta['jre_nty_font_color'] ) ? $jre_window_meta['jre_nty_font_color'] : '';
			$jre_image_position = isset( $jre_window_meta['jre_image_position'] ) ? $jre_window_meta['jre_image_position'] : 'right';
			$jre_views = isset( $jre_window_meta['jre_views'][0] ) ? $jre_window_meta['jre_views'][0] : 0;
			$jre_overlay_color = isset( $jre_window_meta['jre_overlay_color'] ) ? $jre_window_meta['jre_overlay_color'] : '#000000';
			$jre_overlay_opacity = isset( $jre_window_meta['jre_overlay_opacity'] ) ? $jre_window_meta['jre_overlay_opacity'] : '6';

			// we don't want multiple pop ups, so if we've already set $modal_active to 1 we need to stop running the loop.
			if ( $modal_active == 0 )
			{
				if ( isset( $jre_window_meta['jre_link_url'][0] ) ) {
					$jre_landing_page = $jre_window_meta['jre_link_url'][0];
				} else {
					$jre_landing_page = '';
				}
				$jre_cta = $jre_window_meta['jre_cta'][0];
				$jre_nty = $jre_window_meta['jre_nty'];

				$thumbnail = get_the_post_thumbnail_url( $jre_event->ID );

				// Give each Random Event a chance at running
				if ( jre_roll_the_dice( $jre_window_chance, $jre_window_location ) )
				{
					// Determine where the Random Event is to be displayed, or set classes for specific locations &
					// set $modal_active to 1 so that we don't create any more pop ups during the next iteration of the foreach loop
					$jre_popup_random_id = rand( 1, 1000 );

					if ( $jre_window_location == "random" )
					{
						$jre_window_location = rand( 1, 7 );
					}

					$jre_modal_id = '';
					$jre_container_class = 'jre_container';

					switch( $jre_window_location )
					{
						case '1': case 'tl':
							$location = 'jre_top_left';
							$modal_active++;
						break;

						case '2': case 'tc':
							$location = 'jre_top_center';
							$modal_active++;
						break;

						case '3': case 'tr':
							$location = 'jre_top_right';
							$modal_active++;
						break;

						case '4': case 'bl':
							$location = 'jre_bottom_left';
							$modal_active++;
						break;

						case '5': case 'bc':
							$location = 'jre_bottom_center';
							$modal_active++;
						break;

						case '6': case 'br':
							$location = 'jre_bottom_right';
							$modal_active++;
						break;

						case '7': case 'modal': 
							$location = 'jre_modal';
							if ( $jre_overlay_opacity[0] == '10' ) { $opacity = '1'; } else { $opacity = '0.'.$jre_overlay_opacity[0]; }
							$jre_container .= '<div class="jre_modal_overlay" id="jre_modal_overlay" style="background-color:'.$jre_overlay_color[0].'; opacity:'.$opacity.'"></div>';
							$jre_container_class = 'jre_modal_container modal_'.$modal_active;
							$jre_modal_id = 'jre_modal_container';
							$modal_active++; 
						break;
					}
					$jre_container_class = $jre_container_class .' '. $location;
					$jre_container .= '<div class="'.$jre_container_class.'" id="'.$jre_modal_id .' '.$location.'_'.$jre_window_chance.'_'.$jre_popup_random_id.'" style="background-color: '.$jre_description_background_color[0].'; color: '.$jre_description_font_color[0].';">
						<h3 class="jre_modal_title" style="background-color: '.$jre_title_background_color[0].'; color: '.$jre_title_font_color[0].';">'.get_the_title($jre_event->ID).'</h3>
						<img src="'.$thumbnail.'" style="float:'.$jre_image_position[0].'">
						<p class="jre_modal_description" style="color:'.$jre_description_font_color[0].';"">'.get_the_content().'
							<a href="'.$jre_landing_page.'" class="jre_modal_cta" style="color:'.$jre_cta_font_color[0].';">'.$jre_cta.'</a>
							<span id="jre_modal_close" class="jre_close_modal" style="color:'.$jre_nty_font_color[0].'">'.$jre_nty[0].'</span>
						</p>
						<span style="clear:both; display:block;"></span>
					</div>';

					$jre_new_views = $jre_views + 1; // view count + 1
					update_post_meta( $jre_event->ID, 'jre_views', $jre_new_views );
				}
				echo $jre_container;
			}
		endforeach;
	}

	/*
		Make sure our CSS/JS gets loaded!
	*/
	function jre_show_your_style()
	{
		wp_enqueue_style( 'jreswag', plugins_url( 'resources/juggernaut_random_event_pro.css' , __FILE__ ) );
		wp_enqueue_script( 'jre_js', plugins_url( 'resources/juggernaut_random_event_pro.js', __FILE__ ), array(), true );
	}
	add_action( 'wp_footer', 'get_jre_lists' );
	add_action( 'wp_enqueue_scripts', 'jre_show_your_style' );
	add_action( 'admin_menu', 'jre_build_your_settings_page' );

	/*
		We have an admin page, this creates the menu item under Settings
	*/
	function jre_build_your_settings_page()
	{
		add_options_page( 'jre - Thats Random', 'Random Event', 'edit_theme_options', 'jre', 'jre_admin_settings' );
	}

	/*
		The beautiful admin settings page in /wp-admin/
	*/
	function jre_admin_settings()
	{
		?>
			<div class="wrap">
				<h2>Juggernaut Random Events Pro Configuration</h2>
				<p>Thank you for using JREP! Please <a href="http://juggernautplugins.com/contact" target="_blank">let us know</a> if you have any questions or comments, we're always looking for ways to improve our product!</p>
			</div>
		<?
	}

	/*
		The random event custom post type page requires some CSS/JS to function properly, this loads our scripts on our random events CPT
	*/
	function jre_admin_scripts($hook)
	{
		global $post_type;
		if ( $post_type == 'random_event' )
		{
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'jre_admin_css', plugins_url('resources/juggernaut_random_event_pro_admin.css', __FILE__ ) );
			wp_enqueue_script( 'my-script-handle', plugins_url('resources/juggernaut_random_event_pro_admin.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
		}

	}
	add_action( 'admin_enqueue_scripts', 'jre_admin_scripts' );

	/*
		This function is for a template tag, allowing developers to put it anywhere in a WordPress template
	*/
	function jre_go( $chance, $location )
	{
		jre_roll_the_dice( $chance, $location );
	}

	/*
		Random Events is built ontop of WordPress' custom post types. This registers all that goodness.
	*/
	function jre_create_posttype()
	{
		$jre_post_type_labels = array(
			'name' => 'Random Events',
			'singular_name' => 'Random Event',
			'menu_name' => 'Random Events',
			'parent_item_colon' => 'Parent Event',
			'all_items' => 'All Events',
			'view_item' => 'View Random Event',
			'add_new_item' => 'Add New Random Event',
			'add_new' => 'Add New',
			'edit_item' => 'Edit Random Event',
			'update_item' => 'Update Random Event',
			'search_items' => 'Search Random Event',
			'not_found' => 'Not Found',
			'not_found_in_trash' => 'Not found in Trash'
		);

		$jre_post_type_args = array(
			'labels' => $jre_post_type_labels,
			'public' => true,
			'label' => 'Random Events',
			'menu_name' => 'Random Events',
			'add_new' => 'New Random Event',
			'add_new_item' => 'Create A New Random Event',
			'supports' => array( 'title', 'editor', 'thumbnail' )
		);
		register_post_type( 'random_event', $jre_post_type_args );
	}

	/*
		We have a lot of metadata to collect for each random event,
		these are the different boxes that appear when creating / editing a random event
	*/
	function add_random_events_metaboxes()
	{
		add_meta_box('jre_cta', 'Call to action', 'jre_cta', 'random_event', 'side', 'default');
		add_meta_box('jre_customize', 'Random Event Customization', 'jre_customize', 'random_event', 'normal', 'default');
		add_meta_box('jre_preview', 'Random Event Preview', 'jre_preview', 'random_event', 'normal', 'default');
	}
	
	/*
		We need to be able to customize each Random Event's looks.
		This function defines defaults and outputs all the customization options.
	*/
	function jre_customize()
	{
		global $post;
		$jre_title_background_color = get_post_meta( $post->ID, 'jre_title_background_color', true );
		$jre_title_font_color = get_post_meta( $post->ID, 'jre_title_font_color', true );
		$jre_description_background_color = get_post_meta( $post->ID, 'jre_description_background_color', true );
		$jre_description_font_color = get_post_meta( $post->ID, 'jre_description_font_color', true );
		$jre_cta_font_color = get_post_meta( $post->ID, 'jre_cta_font_color', true );
		$jre_nty_font_color = get_post_meta( $post->ID, 'jre_nty_font_color', true );
		$jre_image_position = get_post_meta( $post->ID, 'jre_image_position', true );
		$jre_overlay_color = get_post_meta( $post->ID, 'jre_overlay_color', true );
		$jre_overlay_opacity = get_post_meta( $post->ID, 'jre_overlay_opacity', true );
		$jre_chance = get_post_meta( $post->ID, 'jre_chance', true );
		$jre_location = get_post_meta($post->ID, 'jre_location', true);

		if ( $jre_title_background_color == '' ) { $jre_title_background_color = '#ce2f2e'; }
		if ( $jre_title_font_color == '' ) { $jre_title_font_color = '#f9f9f9'; }
		if ( $jre_description_background_color == '' ) { $jre_description_background_color = '#f9f9f9'; }
		if ( $jre_description_font_color == '' ) { $jre_description_font_color = '#000000'; }
		if ( $jre_cta_font_color == '' ) { $jre_cta_font_color = '#0000ff'; }
		if ( $jre_nty_font_color == '' ) { $jre_nty_font_color = '#ce2f2e'; }
		if ( $jre_image_position == '' ) { $jre_image_position = 'right'; }
		if ( $jre_overlay_color == '' ) { $jre_overlay_color = '#000000'; }
		if ( $jre_overlay_opacity == '' ) { $jre_overlay_opacity = '6'; }
		if ( $jre_chance == '' ) { $jre_chance = '1'; }
		if ( $jre_location == '' ) { $jre_location = 'modal'; }
		?>
			<div class="jre_custom_options_panel">
			<h1>Chance Of Accurance</h1>
			<ul>
				<li><input type="radio" id="1" value="1" name="jre_chance" <?php if ( $jre_chance == 1 ) { echo "checked"; } ?> ><label for="1">1%</label></li>
				<li><input type="radio" id="5" value="5" name="jre_chance" <?php if ( $jre_chance == 5 ) { echo "checked"; } ?> ><label for="5">5%</label></li>
				<li><input type="radio" id="10" value="10" name="jre_chance" <?php if ( $jre_chance == 10 ) { echo "checked"; } ?> ><label for="10">10%</label></li>
				<li><input type="radio" id="20" value="20" name="jre_chance" <?php if ( $jre_chance == 20 ) { echo "checked"; } ?> ><label for="20">20%</label></li>
				<li><input type="radio" id="25" value="25" name="jre_chance" <?php if ( $jre_chance == 25 ) { echo "checked"; } ?> ><label for="25">25%</label></li>
				<li><input type="radio" id="100" value="100" name="jre_chance" <?php if ( $jre_chance == 100 ) { echo "checked"; } ?> ><label for="100">100%</label><br /></li>
			</ul>
			</div>
			
			<div class="jre_custom_options_panel">
			<h1>Event Location</h1>
			<ul>
				<li><input type="radio" id="location_widget" value="widget" name="jre_location" <?php if ( $jre_location == 'widget' ) { echo "checked"; } ?> /><label for="location_widget">Widget</label></li>
				<li><input type="radio" id="location_shortcode" value="shortcode" name="jre_location" <?php if ( $jre_location == 'shortcode' ) { echo "checked"; } ?>/><label for="location_shortcode">ShortCode</label></li>
				<li><input type="radio" id="location_modal" value="modal" name="jre_location" <?php if ( $jre_location == 'modal' ) { echo "checked"; } ?> /><label for="location_modal">Modal</label></li>
				<li><input type="radio" id="location_random" value="random" name="jre_location" <?php if ( $jre_location == 'random' ) { echo "checked"; } ?> /><label for="location_random">Random</label></li>
				<li><input type="radio" id="location_tl" value="tl" name="jre_location" <?php if ( $jre_location == 'tl' ) { echo "checked"; } ?> /><label for="location_tl">Top Left</label></li>
				<li><input type="radio" id="location_tc" value="tc" name="jre_location" <?php if ( $jre_location == 'tc' ) { echo "checked"; } ?> /><label for="location_tc">Top Center</label></li>
				<li><input type="radio" id="location_tr" value="tr" name="jre_location" <?php if ( $jre_location == 'tr' ) { echo "checked"; } ?> /><label for="location_tr">Top Right</label></li>
				<li><input type="radio" id="location_bl" value="bl" name="jre_location" <?php if ( $jre_location == 'bl' ) { echo "checked"; } ?> /><label for="location_bl">Bottom Left</label></li>
				<li><input type="radio" id="location_bc" value="bc" name="jre_location" <?php if ( $jre_location == 'bc' ) { echo "checked"; } ?> /><label for="location_bc">Bottom Center</label></li>
				<li><input type="radio" id="location_br" value="br" name="jre_location" <?php if ( $jre_location == 'br' ) { echo "checked"; } ?> /><label for="location_br">Bottom Right</label></li>
			</ul>
			</div>

			<div class="jre_custom_options_panel">
				<h1>Featured Image</h1>
				<ul>
					<li>Position Left : <input type="radio" name="jre_image_position" id="jre_image_position_left" value="left" <?php if ( $jre_image_position == 'left' ) { echo "checked"; } ?> /></li>
					<li>Position right : <input type="radio" name="jre_image_position" id="jre_image_position_right" value="right" <?php if ( $jre_image_position == 'right' ) { echo "checked"; } ?> /></li>
				</ul>
			</div>
			
			<div style="clear:both;"></div>
			
			<div class="jre_custom_options_panel">
				<h1>Title Section</h1>
				<ul>
					<li>Background-color: <input id="jre_title_background_color" name="jre_title_background_color" type="text" value="<?php echo $jre_title_background_color; ?>" class="my-color-field" data-default-color="#ce2f2e" /></li>
					<li>Font Color: <input id="jre_title_font_color" name="jre_title_font_color" type="text" value="<?php echo $jre_title_font_color; ?>" class="my-color-field" data-default-color="#f9f9f9" /></li>
				</ul>
			</div>

			<div class="jre_custom_options_panel">
				<h1>Content Section</h1>
				<ul>
					<li>Background-color: <input id="jre_description_background_color" name="jre_description_background_color" type="text" value="<?php echo $jre_description_background_color; ?>" class="my-color-field" data-default-color="#f9f9f9" /></li>
					<li>Font Color: <input id="jre_description_font_color" name="jre_description_font_color" type="text" value="<?php echo $jre_description_font_color; ?>" class="my-color-field" data-default-color="#000000" /></li>
				</ul>
			</div>

			<div class="jre_custom_options_panel">
				<h1>Call To Action</h1>
				<ul>
					<li>Font Color: <input id="jre_cta_font_color" name="jre_cta_font_color" type="text" value="<?php echo $jre_cta_font_color; ?>" class="my-color-field" data-default-color="#000000" /></li>
				</ul>
			</div>

			<div style="clear:both;"></div>

			<div class="jre_custom_options_panel">
				<h1>No Thank You / Close</h1>
				<ul>
					<li>Font Color: <input id="jre_nty_font_color" name="jre_nty_font_color" type="text" value="<?php echo $jre_nty_font_color; ?>" class="my-color-field" data-default-color="#ce2f2e" /></li>
				</ul>
			</div>
			<div class="jre_custom_options_panel">
				<h1>Modal Overlay</h1>
				<ul>
					<li>Background-color: <input id="jre_overlay_color" name="jre_overlay_color" type="text" value="<?php echo $jre_overlay_color; ?>" class="my-color-field" data-default-color="#000000" /></li>
					<li>Background Opacity: ( Scale from 1-10, 10 being solid ) <input id="jre_overlay_opacity" name="jre_overlay_opacity" type="text" value="<?php echo $jre_overlay_opacity; ?>" /></li>
				</ul>
			</div>
			<div style="clear: both;"></div>
		<?php
	}

	/*
		Here we create a live preview of the Random Event you're creating, allowing you to see it before ever hitting save!
	*/
	function jre_preview()
	{
		global $post;
		$jre_cta = get_post_meta( $post->ID, 'jre_cta', true );
		$thumbnail = get_the_post_thumbnail_url( $post->ID );
		$jre_title_background_color = get_post_meta( $post->ID, 'jre_title_background_color', true );
		$jre_title_font_color = get_post_meta( $post->ID, 'jre_title_font_color', true );
		$jre_description_background_color = get_post_meta( $post->ID, 'jre_description_background_color', true );
		$jre_description_font_color = get_post_meta( $post->ID, 'jre_description_font_color', true );
		$jre_cta_font_color = get_post_meta( $post->ID, 'jre_cta_font_color', true );
		$jre_nty_font_color = get_post_meta( $post->ID, 'jre_nty_font_color', true );
		$jre_nty = get_post_meta( $post->ID, 'jre_nty', true );
		$jre_image_position = get_post_meta( $post->ID, 'jre_image_position', true );

		// these are default values incase they haven't been set yet by the user
		if ( $jre_title_background_color == '' ) { $jre_title_background_color = '#ce2f2e'; }
		if ( $jre_title_font_color == '' ) { $jre_title_font_color = '#f9f9f9'; }
		if ( $jre_description_background_color == '' ) { $jre_description_background_color = '#f9f9f9'; }
		if ( $jre_description_font_color == '' ) { $jre_description_font_color = '#000000'; }
		if ( $jre_cta_font_color == '' ) { $jre_cta_font_color = '#0000ff'; }
		if ( $jre_nty_font_color == '' ) { $jre_nty_font_color = '#ce2f2e'; }
		if ( $jre_image_position == '' ) { $jre_image_position = 'right'; }
		?>
			<div class="jre_container" id="jre_modal_container" style="color:<?php echo $jre_description_font_color; ?>; background-color:<?php echo $jre_description_background_color; ?>; border:1px solid #c4c4c4;">
				<h3 class="jre_title" style="color:<?php echo $jre_title_font_color; ?>; background-color:<?php echo $jre_title_background_color; ?>;padding: 15px 20px 14px 10px; margin: 0;"> <span id="jre_preview_title"> <?php echo get_the_title(); ?></span></h3>
				<img class="preview_thumbnail" style="float:<?php echo $jre_image_position; ?>;" src="<?php echo $thumbnail; ?>" />
				<p class="jre_description" id="jre_preview_content" style="padding: 30px;"><?php echo $post->post_content; ?></p>
				<a href="" class="jre_cta" style="color:#0000ff;display: block;text-align:center;" id="jre_preview_cta"><?php echo $jre_cta; ?></a>
				<p style="text-align:center;"><span id="jre_preview_nty" class="jre_close_modal" style="color:<?php echo $jre_nty_font_color; ?>;"><?php echo $jre_nty; ?></span></p>
				<span style="clear:both; display:block;"></span>
			</div>
		<?php
	}

	/*
		Display the number of views a Random Event has
	*/
	add_action( 'post_submitbox_misc_actions', 'jre_views' );
	function jre_views()
	{
		global $post;
		$jre_views = get_post_meta( $post->ID, 'jre_views', true );
		if ( get_post_type( $post ) == 'random_event' ) {
			if ( ! empty( $jre_views ) )
				echo '<div class="misc-pub-section">'.$jre_views.' Views</div>';
		}
	}

	/*
		Allow user's to define a Call To Action for their Random Event 
	*/
	function jre_cta()
	{
		global $post;
		$jre_cta = get_post_meta( $post->ID, 'jre_cta', true );
		$jre_nty = get_post_meta( $post->ID, 'jre_nty', true );
		$jre_link_url = get_post_meta( $post->ID, 'jre_link_url', true );
		echo '<input type="hidden" name="jre_cta_noncename" id="jre_cta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		echo '<h4>Call To Action :</h4> <input type="text" name="jre_cta" value="' . $jre_cta . '" class="widefat" id="jre_cta" />';
		echo '<h4>Call To Action URL:</h4> <input type="text" name="jre_link_url" value="' . $jre_link_url . '" class="widefat" />';
		echo '<h4>No Thanks / Close Text :</h4> <input type="text" name="jre_nty" value="' . $jre_nty . '" class="widefat" id="jre_nty" />';
	}

	/*
		All that stuff we've been collecting about the Random Event...
		we need to make sure to save that. 
	*/
	function jre_save_random_events_meta( $post_id, $post )
	{
		if ( !current_user_can( 'edit_post', $post->ID ) )
			return $post->ID;

		// set default values incase user didn't set them.
		$events_meta['jre_cta']								= isset( $_POST['jre_cta'] ) ? $_POST['jre_cta'] : 'Get It Now!';
		$events_meta['jre_nty']								= isset( $_POST['jre_nty'] ) ? $_POST['jre_nty'] : 'No Thanks';
		$events_meta['jre_chance']							= isset( $_POST['jre_chance'] ) ? $_POST['jre_chance'] : '5';
		$events_meta['jre_location']						= isset( $_POST['jre_location'] ) ? $_POST['jre_location'] : 'modal';
		$events_meta['jre_link_url']						= isset( $_POST['jre_link_url'] ) ? $_POST['jre_link_url'] : '';
		$events_meta['jre_title_background_color']			= isset( $_POST['jre_title_background_color'] ) ? $_POST['jre_title_background_color'] : '#ce2f2e';
		$events_meta['jre_title_font_color']				= isset( $_POST['jre_title_font_color'] ) ? $_POST['jre_title_font_color'] : '#f9f9f9';
		$events_meta['jre_description_background_color']	= isset( $_POST['jre_description_background_color'] ) ? $_POST['jre_description_background_color'] : '#f9f9f9';
		$events_meta['jre_description_font_color']			= isset( $_POST['jre_description_font_color'] ) ? $_POST['jre_description_font_color'] : '#000000';
		$events_meta['jre_cta_font_color']					= isset( $_POST['jre_cta_font_color'] ) ? $_POST['jre_cta_font_color'] : '#0000ff';
		$events_meta['jre_nty_font_color']					= isset( $_POST['jre_nty_font_color'] ) ? $_POST['jre_nty_font_color'] : '#ce2f2e';
		$events_meta['jre_image_position']					= isset( $_POST['jre_image_position'] ) ? $_POST['jre_image_position'] : 'right';
		$events_meta['jre_overlay_color']					= isset( $_POST['jre_overlay_color'] ) ? $_POST['jre_overlay_color'] : '#000000';
		$events_meta['jre_overlay_opacity']					= isset( $_POST['jre_overlay_opacity'] ) ? $_POST['jre_overlay_opacity'] : '6';

		foreach ( $events_meta as $key => $value )
		{
			if ( $post->post_type == 'revision' ) return;
			$value = implode( ',', (array)$value );
			if ( get_post_meta( $post->ID, $key, FALSE ) ) {
				update_post_meta( $post->ID, $key, $value );
			} else {
				add_post_meta( $post->ID, $key, $value );
			}
			if ( !$value ) delete_post_meta( $post->ID, $key );
		}
	}

	/*
		When setting up the widget, it allows you to select a Random Event from a drop down list.
		This grabs a list of Random Events that have Widget as their location.
	*/
	function jre_get_random_events_widgets()
	{
		$jre_widgets_args = array(
			'post_type'		=> 'random_event',
			'orderby'		=> 'meta_value',
			'order'			=> 'ASC',
			'meta_query'	=> array(
				array(
					'key'	=> 'jre_location',
					'value'	=> 'widget'
				)
			)
		);
		$jre_widgets = new WP_Query( $jre_widgets_args );
		return $jre_widgets;
	}
	add_action( 'save_post', 'jre_save_random_events_meta', 1, 2 );
	add_action( 'init', 'jre_create_posttype' );
	add_action( 'add_meta_boxes', 'add_random_events_metaboxes' );

	/*
		On the All Events overview page, we're going to display some of the important metadata. 
		This function creates the columns we want 
	*/
	function jre_change_columns( $cols )
	{
		$cols = array(
			'cb'			=> '<input type="checkbox" />',
			'title'			=> __( 'Title', 'trans' ),
			'views_clicks'	=> __( 'Views', 'trans' ),
			'Position'		=> __( 'Position', 'trans' ),
			'Chance'		=> __( 'Chance', 'trans' ),
		);
		return $cols;
	}
	add_filter( "manage_edit-random_event_columns", "jre_change_columns" );

	/*
		This function displays the meta data from the Random Event that we are displaying in the new columns.
	*/
	function jre_custom_columns( $column, $post_id )
	{
		switch ( $column )
		{
			case "views_clicks":
				$jre_views = get_post_meta( $post_id, 'jre_views', true );
				//$jre_clicks = get_post_meta( $post_id, 'jre_clickcount', true );
				// click tracking coming soon
				echo $jre_views;
			break;

			case "Position":
				$jre_location = get_post_meta( $post_id, 'jre_location', true );
				echo $jre_location;
			break;

			case "Chance":
				$jre_chance = get_post_meta( $post_id, 'jre_chance', true );
				echo $jre_chance;
			break;
		}
	}
	add_action( "manage_random_event_posts_custom_column", "jre_custom_columns", 10, 2 );
?>