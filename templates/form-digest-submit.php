<form action="" method="post" id="editor-form" enctype="multipart/form-data">

    <?php do_action('form-submit-topic-before-fields') ?>


    <p class="form-row form-row-wide">
        <label for="post-title">Заголовок</label>
        <span class="woocommerce-input-wrapper">
            <input type="text" id="post-title" name="title" value="<?= $post_title ?>" placeholder="Заголовок" required="">
        </span>
    </p>

    <p class="form-row form-row-wide">
        <label for="post-desc">Текст</label>
        <span class="woocommerce-input-wrapper">
            <textarea name="description" id="post-desc" rows="8" placeholder="Текст" required=""><?= $post_content ?></textarea>
        </span>
    </p>


    <div class="editor-form--additional-wrapper mb-4 bg-gray-200 p-3 mt-3">

        <input type="checkbox" id="additional-enable" name="additional-enable" <?= checked(true, $additional_enable)?>> <label for="additional-enable">Дополнительные опции</label>

        <div class="list-none editor-form--additional-sections hidden bg-gray-100 mt-3 mb-3">

            <div class="form-row form-row-wide p-5">
                <label for="post_url">URL</label>
                <span class="woocommerce-input-wrapper">
                    <input type="url" id="post_url" name="post_url" placeholder="URL" value="<?= $url ?>" class="bg-white">
                </span>
            </div>



            <div class="select-products p-5">
                <strong>Выбор меток</strong>
                <div>
                    <?php
                    // $args_tags = [
                    //     'taxonomy' => 'post_tag',
                    //     'number'       => 10
                    // ];
                    // $terms = get_terms($args_tags);

                    // dd($tags_options);

                    ?>
                    <select class="select-products-input choices__input" style="width: 100%" name="tags[]" multiple="" tabindex="-1">

                        <?php foreach ($tags_options as $tag_option) : ?>

                            <option value="<?= $tag_option->term_id ?>" selected><?= $tag_option->name ?></option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>



            <div class="select-categories p-5">
                <strong>Выбор категорий</strong>

                <div class="overscroll-auto overflow-auto h-64">
                    <?php

                    // dd($terms_checklist_args);
                    wp_terms_checklist($id, $terms_checklist_args);

                    ?>
                </div>
            </div>



        </div>
    </div>


    <input type="hidden" id="nonce" name="nonce" value="<?= $nonce ?>">
    <input type="hidden" name="_wp_http_referer" value="/editor/">
    <input type="hidden" name="id" value="<?= $post_id ?>">
    <input type="hidden" id="post-image" name="og-image" value="">
    <input type="submit" name="save" class="btn" value="Сохранить">
    <input type="submit" name="publish" class="btn" value="Опубликовать">
</form>