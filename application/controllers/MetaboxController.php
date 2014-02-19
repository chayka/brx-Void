<?php

class brx_Void_MetaboxController extends Zend_Controller_Action{
    
    public function init(){
    }
    
    public function seoParamsAction(){
        global $post;
        
        $zfPost = PostModel::unpackDbRecord($post);
        wp_nonce_field( 'seo_params', 'seo_params_nonce' );

        $meta = array();
        
        $this->view->seo_keywords = $meta['seo_keywords'] = $zfPost->getMeta('seo_keywords');
        $this->view->seo_description = $meta['seo_description'] = $zfPost->getMeta('seo_description');
        $this->view->seo_title = $meta['seo_title'] = $zfPost->getMeta('seo_title');

        $this->view->meta = $meta;
        
        $this->view->postId = $post->ID;
        $this->view->post = $zfPost;
    }
    
    
}
