<div class="container">
	<div class="row">
		<div class="col-md-2">
			<?php 
				if (!empty($_SESSION['translation_message'])):
					echo $_SESSION['translation_message'];
					unset($_SESSION['translation_message']);
				endif; 
			?>
            <form method="POST" action=""  enctype="multipart/form-data">
            	<input type="hidden" name="uploadtranslation">
                <div class="form-field">
                    <label for="client-name">Your name</label>
                    <input name="name" id="client-name" type="text" value="" size="40" aria-required="true">
                </div>
                <br />
                <div class="form-field">
                    <label for="client-email">Your email</label>
                    <input name="email" id="client-email" type="text" value="" size="40" aria-required="true">
                    <p>Email should be valid because we will send price by email !</p>
                </div>
                <br />
                <div class="form-field">
                    <label for="translate-from">Translate From</label>
                    <select style="width:95%;" name="translate_from" id="translate-from" >
                        <?php foreach($val['languages'] as $language): ?>
                            <option value="<?php echo $language->id; ?>"><?php echo ucfirst($language->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br />
                <div class="form-field">
                    <label for="translate-to">Translate To</label>
                    <select style="width:95%;" name="translate_to" id="translate-to" >
                        <?php foreach($val['languages'] as $language): ?>
                            <option value="<?php echo $language->id; ?>"><?php echo ucfirst($language->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
				<br />
				<div class="form-field">
					<input type="file" name="translatefile" style="width:95%; "/>
					<p>.xls, .pdf, .doc, docx</p>
				</div>
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Get A Quote">
                </p>
            </form>
		</div>
	</div>
</div>