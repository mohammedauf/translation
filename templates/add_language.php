<style>
    .form-warp label{
        display:block;
    }
</style>
<div class="wrap">
    <h2>Add Language <a href="<?php echo admin_url('admin.php?page=word-count-translate'); ?>" class="add-new-h2">List</a></h2>
    <div class="container">
        <div id="col-right">
            
        </div>
        <div id="col-left">
            <div class="col-warp">
                <div class="form-warp">
                    <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                        <div class="form-field">
                            <label for="language-name">Name</label>
                            <input name="language" id="language-name" type="text" value="" size="40" aria-required="true">
                        </div>
                        <br />
                        <div class="form-field">
                            <label for="language-name">Flag</label>
                            <input name="flag" id="language-name" type="text" value="" size="40" aria-required="true">
                            <p>just define the flag image without file extention. you can find it in plugin directory flags</p>
                        </div>

                        <p class="submit">
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="Add New Language">
                        </p>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>