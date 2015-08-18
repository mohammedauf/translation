<style>
    .form-warp label{
        display:block;
    }
</style>
<div class="wrap">
    <h2>Add Translate Price<a href="<?php echo admin_url('admin.php?page=word-count-prices'); ?>" class="add-new-h2">List</a></h2>
    <div class="container">
        <div id="col-right">
            
        </div>
        <div id="col-left">
            <div class="col-warp">
                <div class="form-warp">
                    <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                        <div class="form-field">
                            <label for="translate-from">Translate From</label>
                            <select style="width:95%;" name="translate_from" id="translate-from" >
                                <?php foreach($val as $language): ?>
                                    <option value="<?php echo $language->id; ?>"><?php echo ucfirst($language->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <br />
                        <div class="form-field">
                            <label for="translate-to">Translate To</label>
                            <select style="width:95%;" name="translate_to" id="translate-to" >
                                <?php foreach($val as $language): ?>
                                    <option value="<?php echo $language->id; ?>"><?php echo ucfirst($language->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <br />
                        <div class="form-field">
                            <label for="language-name">Word Price</label>
                            <input name="word_price" id="word-price" type="text" value="" size="40" aria-required="true">
                        </div>
                        <p class="submit">
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="Add New Price">
                        </p>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>