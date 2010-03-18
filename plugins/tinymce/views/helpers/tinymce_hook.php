<?php
/**
 * TinymceHook Helper
 *
 * PHP version 5
 *
 * @category Helper
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class TinymceHookHelper extends AppHelper {
/**
 * Other helpers used by this helper
 *
 * @var array
 * @access public
 */
    var $helpers = array(
        'Html',
        'Js',
    );
/**
 * Actions
 *
 * Format: ControllerName/action_name => settings
 *
 * @var array
 */
    var $actions = array(
        'Nodes/admin_add' => array(
            'elements' => 'NodeBody',
        ),
        'Nodes/admin_edit' => array(
            'elements' => 'NodeBody',
        ),
        'Translate/admin_edit' => array(
            'elements' => 'NodeBody',
        ),
    );
/**
 * Default settings for tinymce
 *
 * @var array
 * @access public
 */
    var $settings = array(
        // General options
        'mode' => 'exact',
        'elements' => '',
        'theme' => 'advanced',
        'relative_urls' => false,
        'plugins' => 'safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template',
        'height' => '250px',

        // Theme options
        'theme_advanced_buttons1' => 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect',
        'theme_advanced_buttons2' => 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,forecolor,backcolor',
        'theme_advanced_buttons3' => 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen',
        //'theme_advanced_buttons4' => 'insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak',
        'theme_advanced_toolbar_location' => 'top',
        'theme_advanced_toolbar_align' => 'left',
        'theme_advanced_statusbar_location' => 'bottom',
        'theme_advanced_resizing' => true,

        // Example content CSS (should be your site CSS)
        //'content_css' => 'css/content.css',

        // Drop lists for link/image/media/template dialogs
        'template_external_list_url' => 'lists/template_list.js',
        'external_link_list_url' => 'lists/link_list.js',
        'external_image_list_url' => 'lists/image_list.js',
        'media_external_list_url' => 'lists/media_list.js',

        // Attachments browser
        'file_browser_callback' => 'fileBrowserCallBack',
    );

    function fileBrowserCallBack() {
        $output = "function fileBrowserCallBack(field_name, url, type, win) {
            browserField = field_name;
            browserWin = win;
            window.open('".$this->Html->url(array('controller' => 'attachments', 'action' => 'browse'))."', 'browserWindow', 'modal,width=960,height=700,scrollbars=yes');
        }";

        return $output;
    }

    function selectURL() {
        $output = "function selectURL(url) {
            if (url == '') return false;

            url = '".Router::url('/uploads/', true)."' + url;

            field = window.top.opener.browserWin.document.forms[0].elements[window.top.opener.browserField];
            field.value = url;
            if (field.onchange != null) field.onchange();
            window.top.close();
            window.top.opener.browserWin.focus();
        }";
        
        return $output;
    }

    function getSettings($settings = array()) {
        $_settings = $this->settings;
        $action = Inflector::camelize($this->params['controller']).'/'.$this->params['action'];
        if (isset($this->actions[$action])) {
            $_settings = Set::merge($_settings, $this->actions[$action]);
        }
        $settings = Set::merge($_settings, $settings);
        return $settings;
    }

    function beforeRender() {
        if (Configure::read('Writing.wysiwyg')) {
            $this->Html->script('/tinymce/js/tiny_mce', array('inline' => false));
            $this->Html->scriptBlock($this->fileBrowserCallBack(), array('inline' => false));
            $this->Html->scriptBlock('tinyMCE.init(' . $this->Js->object($this->getSettings()) . ');', array('inline' => false));
        }

        if ($this->params['controller'] == 'attachments' && $this->params['action'] == 'admin_browse') {
            $this->Html->scriptBlock($this->selectURL(), array('inline' => false));
        }
    }
}

?>