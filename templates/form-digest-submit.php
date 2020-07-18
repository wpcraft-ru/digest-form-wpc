
<form action="" method="post" id="submit-topic" enctype="multipart/form-data">

    <?php do_action('form-submit-topic-before-fields') ?>

    <p class="form-row form-row-wide">
        <label for="post_url">URL</label>
        <span class="woocommerce-input-wrapper">
            <input type="url" id="post_url" name="post_url" placeholder="URL" value="<?= $url ?>" required="">
        </span>
    </p>

    <p class="form-row form-row-wide">
        <label for="post-title">Заголовок</label>
        <span class="woocommerce-input-wrapper">
            <input type="text" id="post-title" name="title" value="<?= $post_title ?>" placeholder="Title" required="">
        </span>
    </p>

    <p class="form-row form-row-wide">
        <label for="post-desc">Описание</label>
        <span class="woocommerce-input-wrapper">
            <textarea name="description" id="post-desc" rows="8" placeholder="Description" required=""><?= $post_content ?></textarea>
        </span>
    </p>

    <input type="hidden" id="nonce" name="nonce" value="<?= $nonce ?>">
    <input type="hidden" name="_wp_http_referer" value="/submit/">
    <input type="hidden" name="post_id" value="<?= $post_id ?>">
    <input type="hidden" id="post-image" name="og-image" value="">
    <input type="submit" name="submit" class="btn" value="Отправить">
</form>