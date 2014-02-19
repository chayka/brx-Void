<?php

require_once 'application/helpers/UrlHelper_brx_Void.php';

class brx_Void_DummyController extends Zend_Controller_Action{

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
                WpHelper::setNotFound(true);
                return;
            }
        }
        
        $post->incReviewsCount();

        WpHelper::setPostTitle($post->getTitle());
        WpHelper::setPostId($post->getId());
        WpHelper::setSideBarId('dummy-'.$post->getType());
        HtmlHelper::setPostMeta($post);
        
        $this->view->post = $post;
    }
    
    public function listAction(){
        $page = InputHelper::getParam('page', 1);
        $posts = PostModel::query()
                ->postType(brx_Void::POST_TYPE_DUMMY)
                ->pageNumber($page)
                ->postsPerPage(10)
                ->select();
        $linkPattern = UrlHelper_brx_Void::dummies($page);
        $pagination = new PaginationModel();
        $pagination->setCurrentPage($page);
        $pagination->setTotalPages(PostModel::getWpQuery()->max_num_pages);
        $pagination->setPageLinkPattern($linkPattern);

        $this->view->pagination = $pagination;
        $this->view->posts = $posts;

    }
    
    public function widgetsAction(){
         
    }
    
    public function ajaxAction(){
        Util::turnRendererOff();
        $error = InputHelper::getParam('error');
        if(!$error){
            JsonHelper::respond(array('some', 'important', 'data'), 0, 'Success');
        }
        
        JsonHelper::respondError('Some nasty error occured');
    }
}

