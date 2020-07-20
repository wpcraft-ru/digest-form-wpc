<form action="" method="post" id="submit-topic" enctype="multipart/form-data">

    <?php do_action('form-submit-topic-before-fields') ?>

    <p class="form-row form-row-wide">
        <label for="post_url">URL</label>
        <span class="woocommerce-input-wrapper">
            <input type="url" id="post_url" name="post_url" class="form-field-sc" placeholder="URL" value="<?= $url ?>" required="">
        </span>
    </p>

    <p class="form-row form-row-wide">
        <label for="post-title">Заголовок</label>
        <span class="woocommerce-input-wrapper">
            <input type="text" id="post-title" class="form-field-sc" name="title" value="<?= $post_title ?>" placeholder="Заголовок поста" required="">
        </span>
    </p>

    <p class="form-row form-row-wide">
        <label for="post-desc">Описание</label>
        <span class="woocommerce-input-wrapper">
            <textarea name="description" class="form-field-sc" id="post-desc" rows="8" placeholder="Краткое описание" required=""><?= $post_content ?></textarea>
        </span>
    </p>

    <input type="hidden" id="nonce" name="nonce" value="<?= $nonce ?>">
    <input type="hidden" name="_wp_http_referer" value="/submit/">
    <input type="hidden" class="sc-hp" name="sc" value="no">
    <input type="hidden" name="post_id" value="<?= $post_id ?>">
    <input type="hidden" id="post-image" name="og-image" value="">
    <input type="submit" name="submit" class="btn submit-action" value="Отправить">
</form>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        
        document.querySelector("#submit-topic").addEventListener('click', function(){
            document.querySelector("#submit-topic .sc-hp").value = 'sc-hp';
        });

        document.addEventListener('change', function(event) {

            if(event.target.classList.contains("form-field-sc")){
                document.querySelector("#submit-topic .sc-hp").value = 'sc-hp';
            }
        });
        
    });
</script>