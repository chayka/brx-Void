<?php

require_once 'application/helpers/UrlHelper_brx_Void.php';

class brx_Void_PostController extends Zend_Controller_Action{

    public function init(){
        
    }
    
    public function entryAction(){
        $id = InputHelper::getParam('id');
        $slug = InputHelper::getParam('post_name');
        $post = PostModel::selectById($id);
        if($post && $post->getId()){ 
            if(urldecode($post->getSlug())!=$slug){
                header('Location: '.$post->getHref(), true, 301);
                die();
            }
        }else{
            $post = PostModel::selectBySlug($slug);
            if($post && $post->getId()){
                $href = $post->getHref();
                if($id != $post->getId()){
                    header('Location: '.$href, true, 301);
                    die();
                }
            }else{
                ZF_Query::setNotFound(true);
                return;
            }
        }
        
        $post->incReviewsCount();

//        ZF_Query::setPostTitle($post->getTitle());
//        ZF_Query::setPostId($post->getId());
        ZF_Query::setPost($post);
        HtmlHelper::setSidebarId('entry-'.$post->getType());
        HtmlHelper::setPost($post);
        $this->view->post = $post;
        wp_enqueue_style('brx.Void.viewEntry');        
    }
    
    public function listAction(){
        global $paged;
        $page = $paged?$paged:InputHelper::getParam('page', 1);
//        die('!'.$page);
        $posts = PostModel::selectPosts();
//        die('@@@');

//        $link = get_pagenum_link(65635);
//        $linkPattern = str_replace('65635', '.page.', $link);
//        $linkPattern = HtmlHelper::getMeta('_linkPattern', 'page/.page.');
        $pagination = PaginationModel::getInstance();
        $pagination->setCurrentPage($page);
        $pagination->setTotalPages(PostModel::getWpQuery()->max_num_pages);
        if(!$pagination->getPageLinkPattern()){
            $pagination->setPageLinkPattern('page/.page.');
        }
    
        $this->view->pagination = $pagination;
        $this->view->posts = $posts;
        wp_enqueue_style('brx.Void.listEntries');
    }
}

