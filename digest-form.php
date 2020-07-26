<?php
/*
* Plugin Name: Digest Forms by WPCraft
* Description: Forms as shortcode [digest-submit]
* Plugin URI: https://github.com/wpcraft-ru/digest-form-wpc
* Author: uptimizt
* GitHub Plugin URI: wpcraft-ru/likes-wp
* Version: 0.1
*/

namespace WPCraft;

class DigestForm
{

    public static $errors = [];
    public static $success = [];
    public static $walker_hook_name = 'digest_form_action_shedule';

    public static function init()
    {

        // return;
        // add_action('init', function () {

        //     if (!isset($_GET['dd'])) {
        //         return;
        //     }

        //     // $url = 'https://wptavern.com/astra-becomes-the-only-non-default-wordpress-theme-with-1-million-installs';
        //     // $d = self::handler_load_image();

        //     // $args = array(
        //     //     'post_type' => 'attachment',
        //     //     'posts_per_page' => 2000,
        //     //     'date_query' => array(
        //     //         array(
        //     //             'after'     => '18.07.2020',
        //     //             'before'    => '21.07.2020',
        //     //             'inclusive' => true,
        //     //         ),
        //     //     ),
        //     // );

        //     // $posts = get_posts($args);
        //     // foreach($posts as $post){

        //     //     echo wp_get_attachment_image_url($post->ID);
        //     //     echo '<hr>';

        //     //     // wp_delete_attachment( $post->ID, $force_delete = true );


        //     // }

        //     // $r = self::save_image_as_featured($post_id = 52776, $url_img = $d);
        //     exit;
        // });



        add_shortcode('digest-submit', function () {

            $data_args = self::get_data();
            extract($data_args);
            ob_start();
            include __DIR__ . '/templates/form-digest-submit.php';
            $content = ob_get_clean();
            return $content;
        });

        add_action('wp', [__CLASS__, 'form_handler']);

        add_action('form-submit-topic-before-fields', function () {
            if (isset(self::$success['post_id'])) {

                $notices = [];
                $notices[] = array(
                    'notice' => 'Пост опубликован в дайджест'
                );

                wc_get_template(
                    "notices/success.php",
                    array(
                        'notices'  => $notices,
                    )
                );
            }
        });

        add_action('init', [__CLASS__, 'add_task_actions_sheduler']);
        add_action('digest_form_action_shedule', [__CLASS__, 'handler_load_image']);
    }



    public static function save_image_as_featured($post_id, $url_img)
    {

        // var_dump($post_id);

        $imageUrl = $url_img;

        $imageUrl = strtok($imageUrl, '?');

        // var_dump($imageUrl);


        // Get the file name
        $filename = substr($imageUrl, (strrpos($imageUrl, '/')) + 1);

        if (!(($uploads = wp_upload_dir(current_time('mysql'))) && false === $uploads['error'])) {
            return null;
        }

        // Generate unique file name
        $filename = wp_unique_filename($uploads['path'], $filename);

        // Move the file to the uploads dir
        $new_file = $uploads['path'] . "/$filename";

        if (!ini_get('allow_url_fopen')) {
            $file_data = curl_get_file_contents($imageUrl);
        } else {
            $file_data = @file_get_contents($imageUrl);
        }

        if (!$file_data) {
            return null;
        }

        file_put_contents($new_file, $file_data);

        // Set correct file permissions
        $stat = stat(dirname($new_file));
        $perms = $stat['mode'] & 0000666;
        @chmod($new_file, $perms);

        // Get the file type. Must to use it as a post thumbnail.
        $wp_filetype = wp_check_filetype($filename, $mimes);

        extract($wp_filetype);

        // No file type! No point to proceed further
        if ((!$type || !$ext) && !current_user_can('unfiltered_upload')) {
            return null;
        }

        // Compute the URL
        $url = $uploads['url'] . "/$filename";

        // Construct the attachment array
        $attachment = array(
            'post_mime_type' => $type,
            'guid' => $url,
            'post_parent' => null,
            'post_title' => $imageTitle,
            'post_content' => '',
        );

        $thumb_id = wp_insert_attachment($attachment, $file, $post_id);

        if (!is_wp_error($thumb_id)) {
            require_once(ABSPATH . '/wp-admin/includes/image.php');

            // Added fix by misthero as suggested
            wp_update_attachment_metadata($thumb_id, wp_generate_attachment_metadata($thumb_id, $new_file));
            update_attached_file($thumb_id, $new_file);

            return $thumb_id;
        }

        return null;
    }

    public static function handler_load_image()
    {

        $args = array(
            'post_type'              => 'post',
            'post_status'              => 'any',
            'meta_query'             => array(
                array(
                    'key'     => 'wpc-digest-image-url-task',
                    'compare' => 'EXISTS',
                ),
            ),
            'no_found_rows'          => true,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'cache_results'          => false,
        );

        $posts = get_posts($args);


        foreach ($posts as $post) {

            if (get_post_thumbnail_id($post)) {
                delete_post_meta($post->ID, 'wpc-digest-image-url-task');
                continue;
            }

            $url_post = get_post_meta($post->ID, 'ext-link-block', true);


            if ($url_post) {
                $url_img = self::get_url_image_from_meta_tags($url_post);


                $r = self::save_image_as_featured($post->ID, $url_img);
                if ($r) {
                    delete_post_meta($post->ID, 'wpc-digest-image-url-task');
                }
            }
        }


        return;
    }


    public static function add_task_actions_sheduler()
    {
        if (as_next_scheduled_action(self::$walker_hook_name)) {
            return;
        }

        as_schedule_single_action(time() + 11, self::$walker_hook_name, [], 'DigestForm');
    }


    public static function get_url_image_from_meta_tags($url)
    {
        $args = [
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36',
        ];


        $http = wp_remote_get($url, $args);
        $data = wp_remote_retrieve_body($http);
        $dom = new \DOMDocument('1.0', 'UTF-8');

        // $prev_libxml_use_internal_errors = libxml_use_internal_errors(true);

        $dom->loadHTML($data, LIBXML_NOWARNING | LIBXML_NOERROR);

        // libxml_clear_errors();

        $head = $dom->getElementsByTagName('head')->item(0);
        $meta_list = $head->getElementsByTagName("meta");

        foreach ($meta_list as $item) {
            // echo $item->getAttribute("property");
            if ($item->getAttribute("property") == "og:image") {
                $url_image = $item->getAttribute("content");
            }
        }

        if (isset($url_image)) {
            return $url_image;
        }

        // libxml_use_internal_errors($prev_libxml_use_internal_errors);

        return false;
        // foreach($links as $link){
        //     var_dump($link); 
        //     echo '<hr>';

        // }


        // $tags = get_meta_tags($url);

        exit;
    }
    public static function form_handler()
    {
        if ('/submit/' != @$_POST['_wp_http_referer']) {
            return;
        }

        //check spam
        if ('sc-hp' != @$_POST['sc']) {
            return;
        }

        $post_data = [
            'ID'    => intval($_POST['post_id']) ? intval($_POST['post_id']) : '',
            'post_title'    => wp_strip_all_tags($_POST['title']),
            'post_content'  => esc_textarea($_POST['description']),
            'post_status'   => 'draft',
            'post_author'   => get_user_by('login', 'digestbot')->ID,
            'post_category' => [get_term_by('slug', 'digest', 'category')->term_id]
        ];

        if(is_user_logged_in()){
            $post_data['post_status'] = 'publish';
            $post_data['post_author'] = get_current_user_id();
        }

        if (empty($post_data['post_title'])) {
            self::$errors[] = 'Пустой заголовок формы. Нужно указать заголовок';
        }

        if (empty($post_data['post_content'])) {
            self::$errors[] = 'Нет описания. Нужно добавить описание';
        }

        if (empty($_POST['post_url'])) {
            self::$errors[] = 'Нет URL. Нужно добавить URL';
        }

        if (empty(self::$errors)) {
            $post_id = wp_insert_post(wp_slash($post_data));
            self::$success['post_id'] = $post_id;
            update_post_meta($post_id, 'ext-link-block', esc_url($_POST['post_url']));
            update_post_meta($post_id, 'wpc-digest-image-url-task', 1);
        }
    }

    public static function get_data()
    {

        $data = [
            'nonce' => wp_create_nonce('digest-submit-form')
        ];

        if (isset($_GET['post_id']) && $post = get_post($_GET['post_id'])) {
            $data['post_id'] = intval($_GET['post_id']);
            $url = get_post_meta($data['post_id'], 'ext-link-block', true);
        } else {
            $data['post_id'] = '';
        }

        $data['post_title'] = empty($post->post_title) ? '' : $post->post_title;
        $data['post_content'] = empty($post->post_content) ? '' : $post->post_content;
        $data['url'] = empty($url) ? '' : $url;


        return $data;
    }
}

DigestForm::init();
