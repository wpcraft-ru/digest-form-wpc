<?php
/*
* Plugin Name: Digest Forms by WPCraft
* Description: Forms as shortcode [digest-submit]
* Plugin URI: https://github.com/wpcraft-ru/likes-wp
* Author: uptimizt
* GitHub Plugin URI: wpcraft-ru/likes-wp
* Version: 0.1
*/

namespace WPCraft;

class DigestForm
{

    public static $errors = [];
    public static $success = [];

    public static function init()
    {
        add_shortcode('digest-submit', function () {

            $data_args = self::get_data();
            extract( $data_args );
            ob_start();
            include __DIR__ . '/templates/form-digest-submit.php';
            $content = ob_get_clean();
            return $content;
        });

        add_action('wp', [__CLASS__, 'form_handler']);

        add_action('form-submit-topic-before-fields', function(){
            if(isset(self::$success['post_id'])){

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
    }

    public static function form_handler()
    {
        if ('/submit/' != $_POST['_wp_http_referer']) {
            return;
        }

        $post_data = [
            'ID'    => intval($_POST['post_id']) ? intval($_POST['post_id']) : '',
            'post_title'    => wp_strip_all_tags($_POST['title']),
            'post_content'  => esc_textarea($_POST['description']),
            'post_status'   => 'draft',
            'post_author'   => get_user_by('login', 'digestbot')->ID,
            'post_category' => 'digest'
        ];

        if(empty($post_data['post_title'])){
            self::$errors[] = 'Пустой заголовок формы. Нужно указать заголовок';
        }

        if(empty($post_data['post_content'])){
            self::$errors[] = 'Нет описания. Нужно добавить описание';
        }

        if(empty($_POST['post_url'])){
            self::$errors[] = 'Нет URL. Нужно добавить URL';
        }

        if(empty(self::$errors)){
            $post_id = wp_insert_post( wp_slash($post_data) );
            self::$success['post_id'] = $post_id;
            update_post_meta($post_id, 'ext-link-block', esc_url($_POST['post_url']));
        }

    }

    public static function get_data()
    {

        $data = [
            'nonce' => wp_create_nonce('digest-submit-form')
        ];

        if(isset($_GET['post_id']) && $post = get_post($_GET['post_id'])){
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
