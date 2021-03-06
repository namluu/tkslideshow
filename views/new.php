<div class="wrap">
    <?php screen_icon(); ?>
    <h1>
        New TK Slideshow 
        <a href="<?php echo admin_url( 'themes.php?page=tk-slideshow' ); ?>" class="page-title-action">Back</a>
    </h1>
    <form name="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="action" value="tk_slideshow_new_action" />
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <div class="inside"></div>
                    <div id="postdivrich" class="postarea wp-editor-expand">
                        <p>
                            <input class="tk-text" type="text" name="name" size="30" value="" spellcheck="true" autocomplete="off" placeholder="Enter name here" required>
                        </p>
                        <p>
                            <textarea name="description" class="tk-desc"></textarea>
                        </p>
                        <p>
                            <input class="tk-text" type="text" name="link_url" size="30" value="" spellcheck="true" autocomplete="off" placeholder="Enter link URL here">
                        </p>
                        <p>
                            <input class="tk-text" type="file" name="link_image" size="30" value="" required>
                            <small>Size: 900x300</small>
                        </p>
                        <p>
                            <input class="tk-text" type="number" name="ordering" size="30" value="<?php echo $row->ordering ?>" autocomplete="off" placeholder="Enter Ordering here">
                        </p>
                    </div>
                </div>

                <div id="postbox-container-1" class="postbox-container">
                    <div class="postbox">
                        <h2><span>Publish</span></h2>
                        <div class="inside">
                            <input id="save" class="button button-primary" type="submit" value="Save" name="save" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>