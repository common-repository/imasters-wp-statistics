<?php
/*
Plugin Name: iMasters WP Statistics
Plugin URI: http://code.imasters.com.br/wordpress/plugins/imasters-wp-statistics/
Description: iMasters WP Statistics offer informations about your posts, pages, attachments, comments and users. Get informations and make important decisions.
Author: Apiki
Version: 0.2
Author URI: http://apiki.com/
*/

/*  Copyright 2009  Apiki (email : leandro@apiki.com)

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class IMASTERS_WP_Statistics {

    /**
     * Construct Function
     */
    function IMASTERS_WP_Statistics()
    {
        //Call Function to Create Admin Menu
        add_action( 'admin_menu', array( &$this, 'menu' ) );
        //Call Function to textdomain for translation language
        add_action( 'init', array( &$this, 'textdomain' ) );
        //Call the function to build the plugin database
        add_action( 'activate_imasters-wp-statistics/imasters-wp-statistics.php', array( &$this, 'install' ) );
        //Call the function to insert the JavaScript for admin
        add_action( 'admin_print_scripts', array( &$this, 'admin_header' ) );
    }

    /**
     * Create menu in Wordpress admin sidebar
     */
    function menu()
    {
        add_menu_page( 'iMasters WP Statistics', 'iMasters WP Statistics', 'manage_statistics', 'imasters-wp-statistics/imasters-wp-statistics-default.php', '' , plugins_url( 'imasters-wp-statistics/assets/images/imasters.png' ) );
        add_submenu_page( 'imasters-wp-statistics/imasters-wp-statistics-default.php', __('Statistics','iwps'), __('Statistics','iwps'), 'manage_statistics', 'imasters-wp-statistics/imasters-wp-statistics-default.php' );
        add_submenu_page( 'imasters-wp-statistics/imasters-wp-statistics-default.php', __('Uninstall','iwps'), __('Uninstall','iwps'), 'manage_statistics', 'imasters-wp-statistics/imasters-wp-statistics-uninstall.php' );
    }
    
    /**
     *Create the textdomain for translation language
     */
    function textdomain()
    {
        load_plugin_textdomain('iwps',false,'wp-content/plugins/imasters-wp-statistics/assets/languages');
    }

    /**
     * Show total Attachments in blog database
     */
     function get_attachment_total()
     {
         global $wpdb;

         $total = $wpdb->get_var("SELECT COUNT( * ) AS 'num_posts' FROM $wpdb->posts WHERE post_type = 'attachment'");

         return $total;
     }

     /**
      *
      * @global native var wordpress for database support $wpdb
      * @return string return consult from database
      */
     function get_users_author()
     {
         global $wpdb;

        $users_author = $wpdb->get_results("SELECT display_name AS user FROM $wpdb->users LIMIT 6", ARRAY_A);

        return $users_author;
     }

     /**
      *
      * @global native var wordpress for database support $wpdb
      * @return string return consult from database
      */
     function get_users_post()
     {
        global $wpdb;

        $users_post = $wpdb->get_results(
              "SELECT COUNT( * ) AS posts, $wpdb->users.display_name
               FROM $wpdb->posts
               INNER JOIN $wpdb->users ON $wpdb->posts.post_author = $wpdb->users.ID
               WHERE $wpdb->posts.post_type = 'post'
               GROUP BY $wpdb->users.ID", ARRAY_A);

        return $users_post;
     }

     /**
      *
      * @global native var wordpress for database support $wpdb
      * @return string return consult from database
      */
     function get_category_post()
     {
        global $wpdb;

        $category_post = $wpdb->get_results( "
            SELECT $wpdb->terms.name, COUNT( * ) AS caregory_posts
            FROM $wpdb->term_relationships
            INNER JOIN $wpdb->posts ON $wpdb->term_relationships.object_id = $wpdb->posts.ID
            INNER JOIN $wpdb->terms ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->terms.term_id
            WHERE $wpdb->posts.post_type = 'post'
            GROUP BY $wpdb->terms.name
            ORDER BY caregory_posts DESC",
        ARRAY_A );

        return $category_post;
     }

     /**
      *
      * @global native var wordpress for database support $wpdb
      * @return string that search in DB Comments Type like Pingback
      */
     function get_pingback()
     {
        global $wpdb;

        $pingback =  $wpdb->get_var( "
            SELECT count(*)
            FROM $wpdb->comments
            WHERE 
            comment_type = 'pingback'"
        );

         return $pingback;
     }

     /**
      *
      * @param int $month_number
      * @return string for the parameter of the function the abbreviation of the month is returned
      */
     function get_month_name( $month_number )
     {
        $month_names = array(
            1 => __( 'Jan', 'iwps' ),
            2 => __( 'Feb', 'iwps' ),
            3 => __( 'Mar', 'iwps' ),
            4 => __( 'Apr', 'iwps' ),
            5 => __( 'May', 'iwps' ),
            6 => __( 'Jun', 'iwps' ),
            7 => __( 'Jul', 'iwps' ),
            8 => __( 'Aug', 'iwps' ),
            9 => __( 'Sep', 'iwps' ),
           10 => __( 'Oct', 'iwps' ),
           11 => __( 'Nov', 'iwps' ),
           12 => __( 'Dec', 'iwps' )
        );

        return $month_names[$month_number];
     }

     /**
      *
      * @global native var wordpress for database support $wpdb
      * @return string that search in DB users registered monthly
      */
     function get_users_monthly()
     {
        global $wpdb;

        $data = $wpdb->get_results(
            "SELECT MONTH( user_registered ) AS month, YEAR( user_registered ) AS year, COUNT( * ) AS total
             FROM $wpdb->users
             GROUP BY MONTH( user_registered ) , YEAR( user_registered )
             ORDER BY YEAR( user_registered ) DESC , MONTH( user_registered ) DESC",ARRAY_A);

        return $data;
     }

     /**
      * Function that concatenate in a array the counters(Posts, pages, attachments) from blog
      * @return string
      */
     function get_counters_usage()
     {
         return array(
            'count_posts'    => wp_count_posts(),
            'count_comments' => wp_count_comments(),
            'count_attach'   => wp_count_attachments()
         );

     }

     /**
      * Function that create the XML tag for construct to Post Publishers by Users
      * @return string
      */
     function get_graph_post_user()
     {
        $data_post_user = $this->get_users_post();

        $xml_post_user = "<graph xAxisName='".__('Users', 'iwps')."' yAxisName='Posts' showNames='1' decimalPrecision='0' formatNumberScale='0' rotateNames='1'>";
           foreach( $data_post_user as $key => $value ) :
           $xml_post_user = $xml_post_user . "<set name='".$value['display_name']."' value='".$value['posts']."' />";
           endforeach;
        $xml_post_user = $xml_post_user ."</graph>";

        return $xml_post_user;
     }

     /**
      * Function that create the XML tag for crontruct chart to Month User
      * @return string
      */
     function get_graph_month_user()
     {
        $data_month_user = $this->get_users_monthly();
        $data_month_user = array_reverse($data_month_user);

        $xml_month_user = "<graph xAxisName='".__('Month/Year', 'iwps')."' yAxisName='Posts' showNames='1' decimalPrecision='0' formatNumberScale='0' rotateNames='1'>";
           foreach( $data_month_user as $key => $value ) :
           $xml_month_user = $xml_month_user . "<set name='".$this->get_month_name($value['month'])."/".$value['year']."' value='".$value['total']."' />";
           endforeach;
        $xml_month_user = $xml_month_user ."</graph>";

        return $xml_month_user;
     }

     /**
      * Function that create the XML tag for crontruct chart to Posts per Category
      * @return string
      */
     function get_graph_post_category()
     {
        $data_post_category = $this->get_category_post();

        $xml_post_category = "<graph xAxisName='".__('Category', 'iwps')."' yAxisName='Posts' showNames='1' decimalPrecision='0' formatNumberScale='0' rotateNames='1'>";
           foreach( $data_post_category as $key => $value ) :
           $xml_post_category = $xml_post_category . "<set name='".$value['name']."' value='".$value['caregory_posts']."' />";
           endforeach;
        $xml_post_category = $xml_post_category ."</graph>";

        return $xml_post_category;
     }

    /**
     * This function insert JS in admin plugin
     */
    function admin_header()
    {
        if (!empty($_GET['page']))
            if ( strpos( $_GET['page'], 'imasters-wp-statistics' ) !== false ) :
                $mtime = filemtime( dirname( __FILE__ ) . '/assets/js/FusionCharts.js' );
                wp_enqueue_script( 'st.scripts1', WP_PLUGIN_URL . '/imasters-wp-statistics/assets/js/FusionCharts.js', array( 'jquery' ), $mtime );
            endif;
    }


}
//Create capability from plugin management
$role = get_role('administrator');
	if(!$role->has_cap('manage_statistics')) {
		$role->add_cap('manage_statistics');
        }

$imasters_wp_statistics = new IMASTERS_WP_Statistics();
?>