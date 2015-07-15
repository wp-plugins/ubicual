<?php
/**
* Plugin Name: Ubicual
* Plugin URI: https://ubicual.com
* Description: Este Plugin permite incrustar formularios de landing pages en su web.
* Version: 1.1.1
* Author: Ubicual
* Author URI: http://kinetica.mobi
* License: GPL2
*/

class wp_ubicual extends WP_Widget {
	



	// constructor
	function wp_ubicual() {
		  parent::WP_Widget(false, $name = __('Ubicual', 'wp_widget_plugin') );
	}

	// widget form creation
		function form($instance) {
		
		// Check values
		if( $instance) {
		     $title = esc_attr($instance['title']);
		     $text = esc_attr($instance['text']);
		     $url = esc_attr($instance['url']);
		     $legalurl = esc_attr($instance['legalurl']);
		} else {
		     $title = '';
		     $text = '';
		     $url = '';
		     $legalurl = '';
		}
		?>
		
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Título', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Texto:', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo $text; ?>" />
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('URL Landing:', 'wp_widget_plugin'); ?></label>
		<input class="widefat" placeholder="https://ubicual.com/l/XXXXX" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Aviso Legal:', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('legalurl'); ?>" name="<?php echo $this->get_field_name('legalurl'); ?>" type="text" value="<?php echo $legalurl; ?>" />
		</p>
		

		<?php
		}

	// update widget
function update($new_instance, $old_instance) {
      $instance = $old_instance;
      // Fields
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['text'] = strip_tags($new_instance['text']);
      $instance['url'] = strip_tags($new_instance['url']);
      $instance['legalurl'] = strip_tags($new_instance['legalurl']);
     return $instance;
}

	// display widget
	
	function widget($args, $instance) {
	extract( $args );
	
	/** Proper way to enqueue scripts and styles */
	wp_enqueue_style('styleub', plugins_url( 'css/style.css', __FILE__));
	wp_enqueue_script( 'scriptub', plugins_url( 'js/script.js', __FILE__), array( 'jquery' ));
	
	// these are the widget options
	$title = apply_filters('widget_title', $instance['title']);
	$text = $instance['text'];
	$url = $instance['url'];
	$legalurl = $instance['legalurl'];
   
	echo $before_widget;
	// Display the widget
	echo '<div class="widget-text wp_widget_plugin_box">';

   // Check if title is set
   if ( $title ) {
      echo $before_title . $title . $after_title;
   }
   // Check if text is set
   if( $text ) {
      echo '<p class="wp_widget_plugin_text">'.$text.'</p>';    
      
   }
   if( $url ) {

	echo '<form action="'.$url.'" id="ubicual-form" class="ubicual-form" method="post" accept-charset="utf-8">';
	
	echo '<input type="hidden" name="_method" value="POST" />';
	echo '<input type="hidden" name="data[Contacto][redirect]" id="ContactoRedirect" />';
	
	$json = wp_remote_get($url."/embed/"); 
	//d($json);
	$data = json_decode($json['body'],true);
	
    foreach ($data as $key => $value){
	    $force = "";
	    if($value['type'] == 2){$force=" *";}
        if($value['type'] !=0){
        echo "<label>". $value['title'].$force."</label>";
        echo "<input type='text' name='data[Contacto][".$key."]'>";
        }
    };
	
	if( $legalurl ) {
	   	echo '<input type="checkbox" name="data[Contacto][confirm]" checked value="1" id="ContactoConfirm" />Acepto las <a href="'.$legalurl.'" target="_blank">condiciones legales</a></label>';
   	} 
   	else{
	   	echo '<input type="checkbox" name="data[Contacto][confirm]" checked value="1" id="ContactoConfirm" />Acepto las <a href="https://ubicual.com/pages/legal" target="_blank">condiciones legales</a></label>';
   	}
	
	echo '<br><br>';
	echo '<input class="btn btn-default" type="submit" value="'.$title.'" />';
	echo '</form>';
	echo '<div id="ubmsg"></div>';
   echo '</div>';
   }

 
   
   echo $after_widget;
   
   
}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("wp_ubicual");'));

?>