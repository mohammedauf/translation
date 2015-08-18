<?php
/*
Plugin Name: WordCount Translate
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: The Plugin's Version Number, e.g.: 1.0
Author: Name Of The Plugin Author
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$object = new WordCountTranslation();

//add a hook into the admin header to check if the user has agreed to the terms and conditions.
add_action('admin_head',  array($object, 'adminHeader'));

//add footer code
add_action( 'admin_footer',  array($object, 'adminFooter'));

// Hook for adding admin menus
add_action('admin_menu',  array($object, 'addMenu'));

//This will create [yourshortcode] shortcode
add_shortcode('yourshortcode', array($object, 'shortcode'));
add_action('wp_loaded', array($object, 'check_post_data'));
class WordCountTranslation{

    /**
     * This will create a menu item under the option menu
     * @see http://codex.wordpress.org/Function_Reference/add_options_page
     */
    public function __construct(){
        add_action('wp_ajax_update_from_language', array(&$this, 'ajax_edit_from_language'));
        add_action('wp_ajax_update_to_language', array(&$this, 'ajax_edit_to_language'));
        add_action('wp_ajax_update_price', array(&$this, 'ajax_edit_price'));
        add_shortcode( 'wordcount', array(&$this, 'word_count_translation_shortcode' ));
        
    }
    public function addMenu(){

        add_menu_page("WordCount", "Translation", 0, "word-count-translate", array($this, 'wordCountHome'));
        add_submenu_page("word-count-translate", "Prices", "Word Prices", 0, "word-count-prices", array($this, "wordCountPrices"));
        add_submenu_page("word-count-translate", "Add New Language", "Add New Language", 0, "word-count-add-language", array($this, "add_language"));
        add_submenu_page("word-count-translate", "Add New Price", "Add Translation Price", 0, "word-count-add-price", array($this, "add_price"));

       // add_options_page('Your Plugin Options', 'Your Plugin', 'my_custom_submenu_page_callback', 'my-unique-identifier', array($this, 'optionPage'));
    }

    /**
     * This is where you add all the html and php for your option page
     * @see http://codex.wordpress.org/Function_Reference/add_options_page
     */
    public function wordCountHome(){
        global $wpdb;
        $table = $wpdb->prefix . 'wordcount_languages';

        $languages = $wpdb->get_results("SELECT * FROM `$table` ");
        $this->set($languages, 'home');
    }


    public static function ajax_edit_from_language() {
        global $wpdb; // this is how you get access to the database
        $priceRecordId = $_POST['id'];
        $fromLanguageId = $_POST['value'];

        $table = $wpdb->prefix . 'wordcount_prices';
        $langTable = $wpdb->prefix . 'wordcount_languages';

        $query = $wpdb->query("UPDATE $table SET $table.`translate_from` = $fromLanguageId Where $table.`id` = $priceRecordId");
        $queryString = "
            SELECT $table.*, $langTable.* 
            FROM $table INNER JOIN $langTable 
            ON ($table.`translate_from` = $langTable.`id`)
            WHERE $table.`id` = $priceRecordId
        ";
        $language = $wpdb->get_row( $queryString );

        echo ucfirst($language->name);
        wp_die();
        
    }

    public static function ajax_edit_to_language() {
        global $wpdb; // this is how you get access to the database
        $priceRecordId = $_POST['id'];
        $toLanguageId = $_POST['value'];

        $table = $wpdb->prefix . 'wordcount_prices';
        $langTable = $wpdb->prefix . 'wordcount_languages';

        $query = $wpdb->query("UPDATE $table SET $table.`translate_to` = $toLanguageId Where $table.`id` = $priceRecordId");
        $queryString = "
            SELECT $table.*, $langTable.* 
            FROM $table INNER JOIN $langTable 
            ON ($table.`translate_to` = $langTable.`id`)
            WHERE $table.`id` = $priceRecordId
        ";
        $language = $wpdb->get_row( $queryString );
        echo ucfirst($language->name);
        wp_die();
        
    }
    public static function ajax_edit_price() {
        global $wpdb; // this is how you get access to the database
        $priceRecordId = $_POST['id'];
        $word_price = $_POST['value'];

        $table = $wpdb->prefix . 'wordcount_prices';
        $langTable = $wpdb->prefix . 'wordcount_languages';

        $query = $wpdb->query("UPDATE $table SET $table.`word_price` = $word_price Where $table.`id` = $priceRecordId");
        $queryString = "SELECT * FROM $table where id=$priceRecordId";

        $price = $wpdb->get_row( $queryString );
        echo ucfirst($price->word_price);
        wp_die();
        
    }
    public function wordCountPrices(){
        global $wpdb;
        if (isset($_GET['delete']) && $_GET['delete'] == true){
            $langTable = $wpdb->prefix . 'wordcount_languages';
            $result = $wpdb->delete( $langTable, array( 'id' => $_GET['langid'] ) );

            if ($result)
                $this->show_message('The record deleted succesfully, redirecting please wait ...!', 'info');
            else
                $this->show_message('Error while delete data, redirecting please wait ... !', 'danger');
            
            echo '<meta http-equiv="refresh" content="5; url=' . admin_url('admin.php?page=word-count-translate') . '">';
            wp_die();

        }
        if ($_GET['ajax'] == true)
            return $this->ajaxRequest($_POST);

        global $wpdb;
        $id = $_GET['langid'];
        $target = $_GET['target'];
        if (!empty($target)){
            if ($target == 'from')
                $target = "WHERE b.id = $id";
            else
                $target = "c.id = $id";
        }elseif(isset($target) && empty($target)){
            $target = "WHERE b.id = $id OR c.id = $id";
        }elseif (!isset($target)){
            $target = '';
        }

        $languages  = $wpdb->prefix . 'wordcount_languages';
        $prices     = $wpdb->prefix . 'wordcount_prices';
        $query = "Select  a.`word_price`,
                    a.`translate_from` translate_from,
                    a.`translate_to`  translate_to,
                    a.id,
                    b.name from_lang,
                    c.name to_lang
                    from  $prices a
                    inner join $languages b 
                        on a.translate_from = b.id
                    inner join $languages c 
                        on a.translate_to = c.id
                    $target
                    ;";

        $p  = $wpdb->get_results($query);
        $this->set($p, 'prices');
    }

    public function add_language(){
        global $wpdb;
        if (!empty($_POST)){
            $table = $wpdb->prefix . 'wordcount_languages';
            $result = $wpdb->insert($table,array('name'=>$_POST['language'],'flag'=>$_POST['flag']),array('%s','%s'));
            if ($result)
                $this->show_message('The language has been saved', 'info');
            else
                $this->show_message('Error while saving new language', 'danger');
            

        }
        $this->set($a, 'add_language');
    }

    public function add_price(){
        global $wpdb;
        if (!empty($_POST)){
            $table = $wpdb->prefix . 'wordcount_prices';
            $result = $wpdb->insert($table,array('translate_from'=>$_POST['translate_from'],'translate_to'=>$_POST['translate_to'], 'word_price'=>$_POST['word_price'] ), array('%s','%s','%s'));
            if ($result)
                $this->show_message('The language has been saved', 'info');
            else
                $this->show_message('Error while saving new language', 'danger');
        }
        $language = $wpdb->prefix . 'wordcount_languages';
        $queryString = "SELECT * FROM `$language` where name != '' ";
        $languages = $wpdb->get_results($queryString);
        $this->set($languages, 'add_price');
    }

    public function show_message($message, $class, $data = false){
        if (!$data)
            include(plugin_dir_path( __FILE__ ). 'templates' . '/msg.php');
        else{
            ob_start();
            include(plugin_dir_path( __FILE__ ). 'templates' . '/msg.php');
            $myvar = ob_get_contents();
            ob_end_clean();
            return $myvar;
        }
    }
    /**
     * this is where you add the code that will be returned wherever you put your shortcode
     * @see http://codex.wordpress.org/Shortcode_API
     */
    public function shortcode(){
        return "add your image and html here...";
    }

    public function adminHeader(){

        wp_enqueue_script( 'zjeditable', plugin_dir_url( __FILE__ ) . 'js/jquery.jeditable.mini.js' );
        wp_enqueue_script( 'zustomscript', plugin_dir_url( __FILE__ ) . 'js/mycustom.js' );

        $languages = $this->getLanguageJson();


        echo "<script type=\"text/javascript\">
            jQuery(document).ready(function($) {

                jQuery('.editable_language_from').editable('" . admin_url('admin-ajax.php') ."?action=update_from_language', {
                     cancel    : 'Cancel',
                     submit    : 'Save',
                     type      : 'select',
                     data      : '". $languages ."',
                     indicator : 'Please wait..',
                     tooltip   : 'Click to edit...',
                     somekey   : 'somevalue'
                 });

                jQuery('.editable_language_to').editable('" . admin_url('admin-ajax.php') ."?action=update_to_language', {
                     type      : 'select',
                     cancel    : 'Cancel',
                     submit    : 'Save',
                     data      : '". $languages ."',
                     indicator : 'Please wait..',
                     tooltip   : 'Click to edit...',
                     somekey   : 'somevalue'
                 });

                jQuery('.editable_price').editable('" . admin_url('admin-ajax.php') ."?action=update_price', {
                     type      : 'text',
                     submit    : 'Save',
                     indicator : 'Please wait..',
                     tooltip   : 'Click to edit...',
                     somekey   : 'somevalue',
                     style     : 'display:block; padding:8px; width:50px;'
                 });
            }); </script>";
    }
    public function getLanguageJson(){
        global $wpdb;
        $languageTable = $wpdb->prefix . 'wordcount_languages';
        $languages = $wpdb->get_results("SELECT id, name FROM $languageTable");
        $lang = array();

        foreach($languages as $language){
            if (!empty($language->name))
            $lang[$language->id] = $language->name;
        }

        return json_encode($lang);
    }
    public function adminFooter(){

    }
    public function word_count_translation_shortcode( $atts, $content = null ) {
        global $wpdb;
        $languageTable = $wpdb->prefix . 'wordcount_languages';
        $languages = $wpdb->get_results("SELECT * FROM $languageTable");
        $vars = array('languages'=> $languages, 'contents' => $content);
        $this->set($vars, 'translate');
    }

    public function set($val, $template){
        
        include(plugin_dir_path( __FILE__ ). 'templates' . '/' . $template . '.php');
    }


    public function check_post_data(){
        if (isset($_POST['uploadtranslation'])){
            //pr($_POST);exit;

            $name           = $_POST['name'];
            $email          = $_POST['email'];
            $translate_from = $_POST['translate_from'];
            $translate_to   = $_POST['translate_to'];

            $upload_dir = wp_upload_dir();
            $target_dir = $upload_dir['basedir'] . '/wordcount/';
            $ufilename = uniqid(rand(), true) . basename($_FILES["translatefile"]["name"]);
            $target_file = $target_dir . $ufilename;


            $uploadOk = 1;
            $fileType = pathinfo($target_file,PATHINFO_EXTENSION);

            $allowedExt = array(
                'txt',
                'pdf',
                'xls',
                'doc',
                'docx'
            );

            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {

                if (!in_array(strtolower($fileType), $allowedExt)){
                    $_SESSION['translation_message'] = $this->show_message('Invalid File Extention.', 'danger', true);
                }
            }
            if (move_uploaded_file($_FILES["translatefile"]["tmp_name"], $target_file)) {
                $_SESSION['translation_message'] = $this->show_message('The file has been uploaded, check your email for quotation.', 'info', true);
            } else {
                $_SESSION['translation_message'] = $this->show_message('Error While Uploading.', 'danger', true);
            }
            
            switch($fileType){
                case "pdf":
                    $wordcount = $this->countPdfWords($target_file);
                break;

                case "txt":
                    $wordcount = $this->countTxtWords($target_file);
                    
                break;

                case "doc":
                    $wordcount = $this->countDocWords($target_file);
                break;
            }
            
            echo $this->getTranslationPrice($wordcount, $translate_from, $translate_to);
            exit;

        }
        $_POST = array();
    }

    public function getTranslationPrice($wc, $from, $to){
        global $wpdb;
        $languages  = $wpdb->prefix . 'wordcount_languages';
        $prices     = $wpdb->prefix . 'wordcount_prices';
        $query = "Select  a.`word_price`,
                    a.`translate_from` translate_from,
                    a.`translate_to`  translate_to,
                    a.id,
                    b.name from_lang,
                    c.name to_lang
                    from  $prices a
                    inner join $languages b 
                        on a.translate_from = b.id
                    inner join $languages c 
                        on a.translate_to = c.id
                    
                    Where a.translate_from = '$from' AND a.translate_to = '$to'
                    ;";

        $p  = $wpdb->get_row($query);
        return (int) $wc * (int) $p->word_price;
    }

    public function countPdfWords($file){

    }
    public function countTxtWords($file){
        $contents = file_get_contents($file);
        //$data = preg_split('/\s+/', mb_str_word_count$contents);
        $data = str_word_count($contents);
        return $data;
    }
    public function countDocWords($file){
        
    }
}

function mb_str_word_count($string, $format = 0, $charlist = '[]') {
        $string=trim($string);
        if(empty($string))
            $words = array();
        else
            $words = preg_split('~[^\p{L}\p{N}\']+~u',$string);
        switch ($format) {
            case 0:
                return count($words);
                break;
            case 1:
            case 2:
                return $words;
                break;
            default:
                return $words;
                break;
        }
    }
function pr($arr){
    echo '<pre>';
    print_r($arr);
}



?>


