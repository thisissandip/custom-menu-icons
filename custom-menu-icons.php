<?php

/**
 * Plugin Name: Custom Menu Icons
 * Description: Add icons from Font Awesome, Material Design and Dashicons to your menu items EASILY!
 * Version: 1.0.0
 * Author: Sandip Mondal
 *
 * @author Sandip Mondal
 * @version 1.0.0
 */

if(!defined("ABSPATH")) : exit(); endif;

// Define Constants

define("SANDIP_CUSTOM_MI_PATH", trailingslashit( plugin_dir_path(__FILE__) ));
define("SANDIP_CUSTOM_MI_URL", trailingslashit( plugins_url("/", __FILE__) ));

// Enqueue Admin Scripts and Styles

add_action( "admin_enqueue_scripts", "sandip_custom_menu_icons_enqueue_admin_menu_icons_scripts" );

function sandip_custom_menu_icons_enqueue_admin_menu_icons_scripts($hook){

    if($hook !== "nav-menus.php"){
        return;
    }
    wp_enqueue_style( "admin-menuicon-materialicons", "https://fonts.googleapis.com/icon?family=Material+Icons" );
    wp_enqueue_style( "admin-menuicon-fontawesome", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" );
    wp_enqueue_style( "menuiconadminstyles", SANDIP_CUSTOM_MI_URL."css/menustyles.css", array("admin-menuicon-materialicons","admin-menuicon-fontawesome"), 1.0);

    wp_enqueue_script("jquery");
    wp_enqueue_script( "menuiconsalliconscript", SANDIP_CUSTOM_MI_URL."js/allicons.js", array(), 1.0, true );
    wp_enqueue_script( "menuiconsadminscript", SANDIP_CUSTOM_MI_URL."js/menuiconscript.js", array("menuiconsalliconscript", "jquery"), 1.0, true );

}


// Enqueue Front End Scripts and Styles

add_action( "wp_enqueue_scripts", "sandip_custom_menu_icons_enqueue_frontend_menu_icons_scripts" );

function sandip_custom_menu_icons_enqueue_frontend_menu_icons_scripts(){
    wp_enqueue_style( "admin-menuicon-materialicons", "https://fonts.googleapis.com/icon?family=Material+Icons" );
    wp_enqueue_style( "front-menuicon-fontawesome", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" );
    wp_enqueue_style( "menuiconfrontendstyles", SANDIP_CUSTOM_MI_URL."css/menufrontendstyles.css", array(), 1.0);
}


// Adding Custom Menu Fields

add_action( 'wp_nav_menu_item_custom_fields', 'sandip_custom_menu_icons_menu_icons_fields', 10, 2 );

function sandip_custom_menu_icons_menu_icons_fields($item_id, $item){
    ?>
   <?php
        wp_nonce_field( "mi_nonce", "_mi_meta_nonce_$item_id" );
        // meta key for the custom field
        // All the inputs [icon_library, icon_name,font_size,vertical_alignment] are saved in one meta key as an array
        
        $menuiconInfo = get_post_meta( $item_id, "_menu_icon_icon_name", true );

        $icon_library = $menuiconInfo[0];
        $icon_name = $menuiconInfo[1];
        $icon_font_size = $menuiconInfo[2];
        $icon_ver_align = $menuiconInfo[3];

    ?>

    <!-- Custom Menu Fields Start -->
        
    <div class="menu-icon-field-wrapper">
            <div class="menu-icon-field-conatiner menu-icon-field-container-<?php echo esc_attr(  $item_id  )?>">
                    <input type="hidden" name="_menu_icon_icon_name[<?php echo esc_attr(  $item_id  ) ?>]"
                    id="_menu_icon_icon_name-<?php  echo esc_attr(  $item_id  ) ?>" value= "<?php echo isset($icon_name) ?  esc_attr( $icon_name )  :  ""; ?>" >

                    <input type="hidden" name="_menu_icon_icon_library[<?php echo esc_attr(  $item_id  ) ?>]"
                    id="_menu_icon_icon_library-<?php  echo esc_attr(  $item_id  ) ?>" value= "<?php echo isset($icon_library) ? esc_attr( $icon_library )  : ""; ?>" >

                    <button class="menu-icon-start-btn" 
                    data-itemid = "<?php  echo esc_attr(  $item_id  ) ?>"
                    >
                        <?php echo _e("Select Icon", 'menu-icons') ?> 
                    </button> 

                    <div class="icon-preview icon-preview-<?php  echo esc_attr(  $item_id  ) ?>"> 
                        <?php         
                        switch($icon_library){
                            case 'font-awesome-icon':
                                echo '<i class="font-awesome-icon '. esc_attr( $icon_name ) .'"></i>';
                                break;
                            case 'material-icons':
                                echo '<span class="material-icons material-icon-size" >'. esc_attr( $icon_name ) .'</span> ';
                                break;
                            case 'dashicons':
                                echo '<i class="dashicons '. esc_attr( $icon_name ).'"></i>';
                                break;
                            default:
                                break;
                        }  ?>
                    </div>


            </div>

            <div class="menu-icons-details-input-container">

                        <div class="font-input-container">
                            <label for="_menu_icon_font_size-<?php  echo esc_attr(  $item_id  ) ;?>">
                                <?php _e( 'Icon Size (pixels)', 'menu-icons'); ?>
                            </label>

                            <input type="number" id="_menu_icon_font_size-<?php  echo esc_attr(  $item_id  ) ;?>" class="menu-icon-size-input" step=".1" 
                            name="_menu_icon_icon_name[font_size][<?php  echo esc_attr(  $item_id  ) ;?>]" value="<?php echo isset($icon_font_size) && $icon_font_size !=="" ? esc_attr( $icon_font_size )  : 22; ?>" />
                        </div>

                        <div class="font-input-container">
                            <label for="_menu_icon_font_size-<?php  echo esc_attr(  $item_id  ) ;?>">
                                <?php _e( 'Icon Vertical Alignment', 'menu-icons'); ?>
                            </label>

                            <?php echo isset($icon_ver_align) && $icon_ver_align == "top" ? "selected" : "" ;?>

                            <select class="menu-icon-select-field" name="_menu_icon_icon_name[vertical_align][<?php  echo esc_attr(  $item_id  ) ?>]" id="_menu_icon_vertical_align-<?php echo esc_attr(  $item_id  ) ;?>">
                                
                                <option value="top" 
                                    <?php echo isset($icon_ver_align) && $icon_ver_align == "top" ? "selected" : "" ;?> 
                                >Top</option>

                                <option value="bottom"
                                    <?php echo isset($icon_ver_align) && $icon_ver_align == "bottom" ? "selected" : "" ;?>
                                >Bottom</option>

                                <option value="middle"
                                    <?php echo isset($icon_ver_align) && $icon_ver_align == "middle" ? "selected" : "" ;?>
                                >Middle</option>

                                <option value="sub"
                                    <?php echo isset($icon_ver_align) && $icon_ver_align == "sub" ? "selected" : "" ;?>
                                >Sub</option>

                                <option value="text-top"
                                    <?php echo isset($icon_ver_align) && $icon_ver_align == "text-top" ? "selected" : "" ;?>
                                >Text-Top</option>

                                <option value="text-bottom"
                                    <?php echo isset($icon_ver_align) && $icon_ver_align == "text-bottom" ? "selected" : "" ;?>
                                >Text-Bottom</option>

                                <option value="super"
                                    <?php echo isset($icon_ver_align) && $icon_ver_align == "super" ? "selected" : "" ;?>
                                >Super</option>

                            </select>
                        </div>

            </div>
    </div>
  <!-- Custom Menu Fields Ends Here-->

    <?php
}



// Adding Menu Icon Modal in the Footer

add_action("admin_footer-nav-menus.php", "sandip_custom_menu_icons_insert_menu_icons_modal",10,2);

function sandip_custom_menu_icons_insert_menu_icons_modal(){
    ?>
    <!-- Modal Starts Here-->
    <div class="menu-icons-modal-wrapper">
        <div class="menu-icons-modal-container">

            <!-- Left Container Starts Here-->
            <div class="left-menu-icon-container">
                    <div class="filter-library-container">
                        <div class="menu-icon-logo"> MENU ICONS</div>
                        <button id="filter-tab-btn" class="is-active" data-library="all-icons" ><i class="fas fa-hashtag button-icon"></i> All Icons</button>
                        <button id="filter-tab-btn" data-library="dash-icon" ><i class="fab fa-wordpress-simple button-icon"></i> Dashicons</button>
                        <button id="filter-tab-btn" data-library="font-awesome" ><i class="fab fa-font-awesome-flag button-icon"></i>  Font Awesome</button>
                        <button id="filter-tab-btn" data-library="material-icon" ><i class="fab fa-google button-icon"></i> Material Icons</button>
                        
                    </div>
            </div>
            <!-- Left Container Ends Here-->

            <!-- Right Container Starts Here-->
            <div class="right-menu-icon-container">
                <div class="search-container">
                    
                    <div class="search-bar-input-container">
                        <input id="search-bar-input-field" type="text" placeholder="Search Icons" >
                        <i class="fas fa-search search-icon-input"></i>
                    </div>
                            
                    <button class="close-modal-btn"><i class="fas fa-times"></i></button>
                </div>

                <div class="all-menu-icons-wrapper">
                    <div class="all-menu-icons-container font-awesome-container"></div>
                    <div class="all-menu-icons-container dashicon-container"></div>
                    <div class="all-menu-icons-container material-icon-container"></div>
                    <div class="all-menu-icons-container search-result-icon-container"></div>
                </div>
            </div>
             <!-- Right Container Ends Here-->
        </div>
    </div>

  <!-- Modal Ends Here-->
    <?php
}


// Update the Custom Field on Save Menu

add_action( 'wp_update_nav_menu_item', 'sandip_custom_menu_icons_update_menu_icons', 10, 2 );

function sandip_custom_menu_icons_update_menu_icons($menu_id, $menu_item_db_id){

     // Verify this came from our screen and with proper authorization.
	if ( ! isset( $_POST["_mi_meta_nonce_$menu_item_db_id"] ) || ! wp_verify_nonce( $_POST["_mi_meta_nonce_$menu_item_db_id"], 'mi_nonce' ) ) {
        return $menu_id;
	} 

    // Check if the fields are set, If they are sanitize the inputs and update the meta of that nav menu item
    if ( isset( $_POST['_menu_icon_icon_name'][$menu_item_db_id]) &&
        isset($_POST['_menu_icon_icon_name']['vertical_align'][$menu_item_db_id]) &&
        isset($_POST['_menu_icon_icon_name']['font_size'][$menu_item_db_id]) &&
        isset($_POST['_menu_icon_icon_library'][$menu_item_db_id])
    )  {
        $icon_lib = sanitize_text_field($_POST['_menu_icon_icon_library'][$menu_item_db_id]) ;
        $icon_name = sanitize_text_field($_POST['_menu_icon_icon_name'][$menu_item_db_id]) ;
        $font_size = sanitize_text_field($_POST['_menu_icon_icon_name']['font_size'][$menu_item_db_id]);
        $vertical_align = sanitize_text_field($_POST['_menu_icon_icon_name']['vertical_align'][$menu_item_db_id]);
        
        // save data in array [ icon_library, icon_name, icon_font_size_in_px, icon_vertical_align ]
		$sanitized_data = array($icon_lib,$icon_name,$font_size,$vertical_align);
		update_post_meta( $menu_item_db_id, '_menu_icon_icon_name', $sanitized_data );
	} 

}


// Show the Menu Icon In Title (Website)

add_filter("nav_menu_item_title", "sandip_custom_menu_icons_admin_menu_icon_display", 10,2); 

function sandip_custom_menu_icons_admin_menu_icon_display($title, $item){

    // get the meta data of the nav_menu_item 
    if( is_object( $item ) && isset( $item->ID ) ) {
        $menuiconInfo = get_post_meta( $item->ID, "_menu_icon_icon_name", true );

        $icon_library = $menuiconInfo[0];
        $icon_name = $menuiconInfo[1];
        $icon_font_size = $menuiconInfo[2];
        $icon_ver_align = $menuiconInfo[3];

        // according to the library filter the Output HTML
        if($icon_library){
            switch($icon_library){
                case 'font-awesome-icon':
                    $menuIconHTML = '<i class="menu-icon '.$icon_name.'" 
                                    style=" font-size: '.$icon_font_size.'px; 
                                    vertical-align: '.$icon_ver_align.'" ></i>';
                    break;
                case 'material-icons':
                    $menuIconHTML = ' <i class="material-icons material-icon-size menu-icon"
                                    style=" font-size: '.$icon_font_size.'px; 
                                    vertical-align: '.$icon_ver_align.'"
                                    >'.$icon_name.'</i> ';
                    break;
                case 'dashicons':
                    $menuIconHTML = '<i class="dashicons '.$icon_name.' menu-icon" 
                                    style=" font-size: '.$icon_font_size.'px; 
                                    vertical-align: '.$icon_ver_align.'"
                                    ></i>';
                    break;
                default:
                    break;
            } 
        }

        // if Icon is already selected then return new Title (which is title with icon)
        // else return the old title
        if(! empty($menuIconHTML)){
            $newtitle = ''.$menuIconHTML.' '.$title.'';
            return $newtitle;
        }else{
            return $title;
        }
    }

}


