<?php
/*
  Plugin Name: Increase Maximum Upload File Size
  Description: Increase maximum upload file size with one click.
  Author: WebFactory Ltd
  Author URI: https://www.webfactoryltd.com/
  Plugin URI: https://wordpress.org/plugins/upload-max-file-size/
  Version: 1.3
  License: GPL2
  Text Domain:upload-max-filesize

  Copyright 2013 - 2019 WebFactory Ltd (email: support@webfactoryltd.com)

  Increase Upload Max Filesize is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  Increase Upload Max Filesize is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with  Upload Max File Size; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


// main plugin class
class WF_Upload_Max_File_Size
{

    static function init()
    {
        // Hook for adding admin menus
        add_action('admin_menu', array(__CLASS__, 'upload_max_file_size_add_pages'));
        add_filter('upload_size_limit', array(__CLASS__, 'upload_max_increase_upload'));

        if (isset($_POST['upload_max_file_size_field']) && wp_verify_nonce($_POST['upload_max_file_size_nonce'], 'upload_max_file_size_action')) {
            $number = (int)$_POST['upload_max_file_size_field'] * 1024 * 1024;
            update_option('max_file_size', $number);
            wp_safe_redirect(admin_url('?page=upload_max_file_size&max-size-updated=true'));
        }
    } // init

    /**
     * Add menu pages
     *
     * @since 1.4
     * 
     * @return null
     * 
     */
    static function upload_max_file_size_add_pages()
    {
        // Add a new menu under Settings
        add_menu_page('Increase Max Upload File Size', 'Increase Maximum Upload File Size', 'manage_options', 'upload_max_file_size', array(__CLASS__, 'upload_max_file_size_dash'), 'dashicons-admin-tools');
    } // upload_max_file_size_add_pages

    /**
     * Get closest value from array
     *
     * @since 1.4
     * 
     * @param int search value
     * @param array to find closest value in
     * 
     * @return int in MB, closest value
     * 
     */
    static function get_closest($search, $arr)
    {
        $closest = null;
        foreach ($arr as $item) {
            if ($closest === null || abs($search - $closest) > abs($item - $search)) {
                $closest = $item;
            }
        }
        return $closest;
    }

    /**
     * Dashboard Page
     *
     * @since 1.4
     * 
     * @return null
     * 
     */
    static function upload_max_file_size_dash()
    {
      echo '<style>';
      echo '.wrap, .wrap p { font-size: 15px; } .form-table th { width: 230px; }';
      echo '</style>';
      
        if (isset($_GET['max-size-updated'])) {
            echo '<div class="notice-success notice is-dismissible"><p>Maximum Upload File Size Saved!</p></div>';
        }

        $ini_size = ini_get('upload_max_filesize');
        if (!$ini_size) {
            $ini_size = 'unknown';
        } elseif (is_numeric($ini_size)) {
            $ini_size .= ' bytes';
        } else {
            $ini_size .= 'B';
        }

        $wp_size = wp_max_upload_size();
        if (!$wp_size) {
            $wp_size = 'unknown';
        } else {
            $wp_size = round(($wp_size / 1024 / 1024));
            $wp_size = $wp_size == 1024 ? '1GB' : $wp_size . 'MB';
        }

        $max_size = get_option('max_file_size');
        if (!$max_size) {
            $max_size = 64 * 1024 * 1024;
        }
        $max_size = $max_size / 1024 / 1024;


        $upload_sizes = array(16, 32, 64, 128, 256, 512, 1024);

        $current_max_size = self::get_closest($max_size, $upload_sizes);

        echo '<div class="wrap">';
        echo '<h1>Increase Maximum Upload File Size</h1><br><br>';

        echo 'Maximum upload file size, set by your hosting provider: ' . $ini_size . '.<br>';
        echo 'Maximum upload file size, set by WordPress: ' . $wp_size . '.<br>';
        echo '<br><b>Important</b>: if you want to upload files larger than the limit set by your hosting provider, you have to contact your hosting provider.<br>It\'s NOT possible to increase the hosting defined limit from a plugin.';
        echo '<form method="post">';
        settings_fields("header_section");
        echo '<table class="form-table"><tbody><tr><th scope="row"><label for="upload_max_file_size_field">Choose Maximum Upload File Size</label></th><td>';
        echo '<select id="upload_max_file_size_field" name="upload_max_file_size_field">';
        foreach ($upload_sizes as $size) {
            echo '<option value="' . $size . '" ' . ($size == $current_max_size ? 'selected' : '') . '>' . ($size == 1024 ? '1GB' : $size . 'MB') . '</option>';
        }
        echo '</select>';
        echo '</td></tr></tbody></table>';
        echo wp_nonce_field('upload_max_file_size_action', 'upload_max_file_size_nonce');
        submit_button();
        echo '</form>';
        
        echo '<p style="display: inline-block; padding: 15px; background-color: #ddd;">Did the plugin help you? Please <a href="https://wordpress.org/support/plugin/upload-max-file-size/reviews/?filter=5" target="_blank">rate it with ★★★★★</a>. It\'s what keeps it free!</p>';

        echo '</div>';
    } // upload_max_file_size_dash

    /**
     * Filter to increase max_file_size
     *
     * @since 1.4
     * 
     * @return int max_size in bytes
     * 
     */
    static function upload_max_increase_upload()
    {
        $max_size = get_option('max_file_size');
        if (!$max_size) {
            $max_size = 64 * 1024 * 1024;
        }

        return $max_size;
    } // upload_max_increase_upload

} // class WF_Upload_Max_File_Size

add_action('init', array('WF_Upload_Max_File_Size', 'init'));
