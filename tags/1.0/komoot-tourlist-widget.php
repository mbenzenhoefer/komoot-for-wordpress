<?php
/*
	Plugin Name: komoot Tourlisten Widget
	Plugin URI: http://www.komoot.de/
	Description: Mit diesem Widget kannst du deine komoot Touren als Liste in der Seitenleiste deines Blogs anzeigen.
	Author: komoot GmbH
	Version: 1.0
	Author URI: http://www.komoot.de
	License: GPLv2 or later
*/

/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/



class komoot_Tourlist_Widget extends WP_Widget {
    function komoot_Tourlist_Widget() {
        parent::WP_Widget(false, $name = 'komoot Tourliste');
    }
	
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$username = apply_filters('widget_title', $instance['username']);
		$height = apply_filters('widget_title', $instance['height']);
		$width = apply_filters('widget_title', $instance['width']);
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
						<iframe scrolling="auto" width="<?php echo $width; ?>"  height="<?php echo $height; ?>" style="border:none; overflow:hidden; width:<?php echo $width; ?>px; height:<?php echo $height; ?>px;" allowTransparency="true" frameborder="0" marginheight="0" marginwidth="0" src="http://www.komoot.de/c/<?php echo $username; ?>/?embed&listType=list-s&target=_blank&header=false">
							<a href="http://www.komoot.de/c/<?php echo $username; ?>/" title="<?php echo $username; ?> bei komoot"><?php echo $username; ?> auf www.komoot.de</a>
						</iframe>
              <?php echo $after_widget; ?>
        <?php
    }
	
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['height'] = strip_tags($new_instance['height']);
		$instance['width'] = strip_tags($new_instance['width']);
        return $instance;
    }
	
	// Setting Form
    function form($instance) {
        $title = esc_attr($instance['title']);
		$username = esc_attr($instance['username']);
		$height = esc_attr($instance['height']);
		$width = esc_attr($instance['width']);

        ?>
        <p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titel:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Benutzername: (<a href="http://www.komoot.de/c/" target="_blank">Registrieren</a>)'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('H&ouml;he des Widget: (z.B. 300)'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Breite des Widget: (z.B. 200)'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" />
        </p>
        <?php 
    }

} // class komoot_Tourlist_Widget

// register
add_action('widgets_init', create_function('', 'return register_widget("komoot_Tourlist_Widget");'));