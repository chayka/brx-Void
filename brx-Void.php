<?php
/*
  Plugin Name: brx-Void
  Plugin URI: http://github.com/chayka/brx-Void.git
  Description: Empty plugin.
  Version: 1.0
  Author: Boris Mossounov
  Author URI: http://facebook.com/mossounov
  License: GPL2
 */
require_once 'application/helpers/WidgetHelper_brx_Void.php';
require_once 'application/helpers/HtmlHelper_brx_Void.php';
class brx_Void extends WpTheme{
    
    protected static $instance = null;
    
    const POST_TYPE_DUMMY = 'dummy';
    const TAXONOMY_DUMMY_TAG = 'dummy-tag';
    
    public static function baseUrl(){
        echo BRX_VOID_URL;
    }
    
    public static function init() {
        
        NlsHelper::load('brx_Void');

        self::$instance = $theme = new brx_Void(array(
            'index', 
            'post',
            'entry',
            'dummy',
            'dummies',
        //  'not-found-404',
        ));
        
        $theme->addSupport_Thumbnails(900, 600, true);
        $theme->addSupport_Excerpt(30, '...');
        $theme->addSupport_CustomPermalinks();
        $theme->addSupport_Metaboxes();

        //  Uncomment if you need processing on post create, update, delete    
        //  $theme->addSupport_PostProcessing();

        $theme->hideAdminBar();
        //  $theme->showAdminBar();
        //  $theme->showAdminBarToAdminOnly();
        
        ZF_Query::forbidRoute('/^blog\b/');
    }

    public static function getInstance() {
        return self::$instance;
    }

    public function registerCustomPostTypes() {
        ZF_Core::registerCustomPostTypeContentFragment();
        self::registerCustomPostTypeDummy();
    }

    public static function registerCustomPostTypeDummy() {
        $labels = array(
            'name' => NlsHelper::_('Dummy'), //'post type general name'),
            'singular_name' => NlsHelper::_('Dummy'), //'post type singular name'),
            'add_new' => NlsHelper::_('Add dummy'), //'item'),
            'add_new_item' => NlsHelper::_('Add dummy'),
            'edit_item' => NlsHelper::_('Edit'),
            'new_item' => NlsHelper::_('New dummy'),
            'all_items' => NlsHelper::_('All dummies'),
            'view_item' => NlsHelper::_('View dummy'),
            'search_items' => NlsHelper::_('Search dummies'),
            'not_found' => NlsHelper::_('No dummies found'),
            'not_found_in_trash' => NlsHelper::_('No dummies in trash bin'),
            'parent_item_colon' => '',
            'menu_name' => NlsHelper::_('Dummies')
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => self::POST_TYPE_DUMMY),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => true,
            'menu_position' => 20,
            'taxonomies' => array(
                'category',
                'post_tag',
                self::TAXONOMY_DUMMY_TAG,
            ),
            'supports' => array(
                'title', 
                'editor', 
//                'author', 
                'thumbnail', 
                'excerpt', 
                'comments',
                'page-attributes',
                )
        );
        register_post_type(self::POST_TYPE_DUMMY, $args);
    }
    
    public function enableSearch($query){
        if ($query->is_search) {
            $postTypes = $query->get('post_type');
            if(!$postTypes){
                $postTypes = array('post', 'page');
            }
            
            $postTypes[]=  brx_Void::POST_TYPE_DUMMY;

            $query->set('post_type', $postTypes);
        }
        return $query;
    }
    
    public function registerTaxonomies(){
  // Add new taxonomy, make it hierarchical (like categories)
        self::registerTaxonomyDummyTag();
    }

    public static function registerTaxonomyDummyTag(){
        $labels = array(
            'name' => NlsHelper::_('Dummy tags'), //'taxonomy general name'),
            'singular_name' => NlsHelper::_('Dummy tag'), //'taxonomy singular name'),
            'search_items' => NlsHelper::_('Find dummy tag'),
            'all_items' => NlsHelper::_('All dummy tags'),
            'edit_item' => NlsHelper::_('Edit'),
            'update_item' => NlsHelper::_('Update'),
            'add_new_item' => NlsHelper::_('Add dummy tag'),
            'new_item_name' => NlsHelper::_('Name'),
            'menu_name' => NlsHelper::_('Dummy tags'),
        );

        register_taxonomy(self::TAXONOMY_DUMMY_TAG, 
                array(
                    self::POST_TYPE_DUMMY,
                ), 
                array(
                    'hierarchical' => false,
                    'labels' => $labels,
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array('slug' => self::TAXONOMY_DUMMY_TAG),
                ));
        
    }
    

    public function postPermalink($permalink, $post, $leavename = false){
        switch($post->post_type){
            case 'post':
                return '/entry/'.$post->ID.'/'.($leavename?'%postname%':$post->post_name);
            case self::POST_TYPE_DUMMY:
                return '/dummy/'.($leavename?'%postname%':$post->post_name);
            default:
                return $permalink;
        }
    }
    
    public function termLink($link, $term, $taxonomy){
        return $link;
    }

    public function registerNavMenus(){
        $this->registerNavMenu('main-menu', 'Main-menu');
    }
    
    public function customizeNavMenuItems($items, $args){
//            Util::print_r(func_get_args());
        $byId = array();
        foreach($items as $i=>$item){
            $byId[$item->ID] = $i; 
        }
        foreach($items as $item){
            if($item->menu_item_parent){
                $i = Util::getItem($byId, $item->menu_item_parent, -1);
                if($i >= 0 && !in_array('menu-item-parent', $items[$i]->classes)){
                    $items[$i]->classes[]='menu-item-parent';
                }
            }
            $url = preg_replace('%\/$%', '', $item->url);
            if($url && strpos($_SERVER['REQUEST_URI'], $url)!==false && !$item->current){
                $item->current = 1;
                $item->classes[]='current-menu-item';
            }
        }
        
        return $items;
    }
    
    public function registerMetaBoxes() {
        
        $this->addMetaBox('seo_params',
            NlsHelper::_( 'SEO Params'),
            '/metabox/seo-params',
            'normal',
            'high');
    }
    
    public function savePost($postId, $post){

    }
    
    public function registerResources($minimize = false){
        $this->registerStyle('brx.Void.body', 'brx.Void.body.less');
        $this->registerStyle('brx.Void.mainPage', 'brx.Void.mainPage.less');
        $this->registerStyle('brx.Void.viewEntry', 'brx.Void.viewEntry.less');
        $this->registerStyle('brx.Void.404', 'brx.Void.404.less');
        $this->registerStyle('brx.Void.Dummy.view', 'void.Dummy.view.less', array());
        $this->registerScript('brx.Void.Dummy.view', 'void.Dummy.view.view.js', array('backbone-brx', 'brx.Void.Dummy.nls'));
        NlsHelper::registerScriptNls('brx.Void.Dummy.nls', 'brx.Void.Dummy.view.js');
    }
    
    public function registerActions(){
//        $this->addAction('add_meta_boxes', array('wpt_MCC_v2', 'addMetaBoxStaff') );
    }
    
    public function registerFilters(){
        $this->addFilter( 'the_search_query', 'enableSearch');
        remove_filter('the_content', 'prepend_attachment', 10);
    }
    public function registerConsolePages() {
        $this->addConsolePage('Theme Options', 'Theme Options', 'update_core', 'brx-void-theme-options', '/admin/theme-options');

    }

    public function registerSidebars() {
        register_sidebar(array(
            'name'=>NlsHelper::_('Stats Counters'),
            'id'=>'stats-counters',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => "<!--",
            'after_title'   => "-->\n"             
        ));
        register_sidebar(array(
            'name'=>NlsHelper::_('Default'),
            'id'=>'default',
        ));
    }

    public static function blockStyles($block = true) {
        
    }
}


ZF_Core::hideAdminBar();
//ZF_Core::showAdminBar();
//ZF_Core::showAdminBarToAdminOnly();

add_action('init', array('brx_Void', 'init'));
