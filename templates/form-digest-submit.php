<form action="" method="post" id="editor-form" enctype="multipart/form-data">

    <?php do_action('form-submit-topic-before-fields') ?>

    <input type="hidden" id="nonce" name="nonce" value="<?= $nonce ?>">
    <input type="hidden" name="_wp_http_referer" value="/editor/">
    <input type="hidden" name="id" value="<?= $post_id ?>">
    <input type="hidden" id="post-image" name="og-image" value="">
    <input type="hidden" name="post_category[]" value="860">


    <div class="form-row form-row-wide p-5">
        <strong>Добавить пост в дайджест</strong>
    </div>

    <div class="form-row form-row-wide p-5">
        <label for="post_url">URL</label>
        <span class="woocommerce-input-wrapper">
            <input type="url" id="post_url" name="post_url" placeholder="URL" value="<?= $url ?>" class="bg-white">
        </span>
    </div>

    <div class="form-row form-row-wide p-5">
        <label for="post-title">Заголовок</label>
        <span class="woocommerce-input-wrapper">
            <input type="text" id="post-title" name="title" value="<?= $post_title ?>" placeholder="Заголовок" required="">
        </span>
    </div>

    <div class="form-row form-row-wide p-5">
        <label for="post-desc">Текст</label>
        <span class="woocommerce-input-wrapper">
            <textarea name="description" id="post-desc" rows="8" placeholder="Текст" required=""><?= $post_content ?></textarea>
        </span>
    </div>
    <div class="select-tags p-5">
        <strong>Метки и темы</strong>
        <div>
     
            <select class="select-products-input choices__input" style="width: 100%" name="tags[]" multiple="" tabindex="-1">

                <?php foreach ($tags_options as $tag_option) : ?>

                    <option value="<?= $tag_option->term_id ?>" selected><?= $tag_option->name ?></option>

                <?php endforeach; ?>

            </select>

        </div>

    </div>

    <?php if (!empty($post_permalink)) : ?>
        <p class="form-row form-row-wide">
            <a href="<?= $post_permalink ?>" target="_blank" rel="noopener noreferrer">Посмотреть</a>
        </p>

    <?php endif; ?>

    <input type="submit" name="publish" class="btn" value="Опубликовать">
    <input type="submit" name="save" class="btn" value="Сохранить как черновик">
    <a href="/editor/?type=digest" class="btn">Новый пост</a>
</form>