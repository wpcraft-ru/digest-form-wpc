<?php 
/*
* Plugin Name: FrontEditor by uptimizt
* Description: Digest form, full editor
* Author: uptimnizt
* Version: 0.1
*/

namespace U7\FrontEditor;

class Core {

    public static function init(){

        require_once __DIR__ . '/src/DigestSubmitShortcode.php';
    }

    public static function get_file_path($file_path = ''){
        return trailingslashit(plugin_dir_path(__FILE__)) . $file_path;
    }

    public static function get_file_url($file_path = ''){
        return plugins_url($file_path, __FILE__);
    }


    public static function render($path = '', $data = []){

        if(empty($path)){
            echo '';
        }

        if ( ! empty( $data ) && is_array( $data ) ) {
            extract( $data ); 
        }

        $path = self::get_file_path("templates/form-digest-submit.php");

        $template = file_exists( $path ) ? $path : '';

    	include $template;

    }

}

Core::init();