<?php

//class wpt_JurcatalogBy_Counters extends wpt_JurcatalogBy_Widget {
//class brx_Void_Widget_Dummies extends WpSidebarWidget {
//    function __construct($id = 'brx-void-widget-dummies', $name = 'Dummies', 
//        $widget_ops = array(
//            'classname' => 'brx-void-widget-dummies',
//            'description' => "Widget that displays dummies"
//        )) {
//        parent::__construct($id, $name, $widget_ops);
//
//    }
//    
//    public static function registerWidget(){
//        $item = new self();
//        register_widget(get_class($item));
//    }
//
//}
//
//add_action( 'widgets_init', array('brx_Void_Widget_Dummies', 'registerWidget'));

class brx_Void_Widget_PopularPosts extends wpt_JurcatalogBy_Widget {
    function __construct($id = 'brx-void-widget-popular-posts', $name = 'Популярные записи', 
        $widget_ops = array(
            'classname' => 'brx-void-widget-popular-posts',
            'description' => "Популярные записи"
        )) {
        parent::__construct($id, $name, $widget_ops);

    }
    
    public static function registerWidget(){
        $item = new self();
        register_widget(get_class($item));
    }

}

add_action( 'widgets_init', array('brx_Void_Widget_PopularPosts', 'registerWidget'));

