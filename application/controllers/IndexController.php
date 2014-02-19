<?php

class brx_Void_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        global $paged;
        $page = InputHelper::getParam('page', $paged);
        $page = $page?$page:1;
        $this->view->posts = PostModel::query()
                ->postType_Post()
                ->pageNumber($page)
                ->postsPerPage(10)
                ->ignoreStickyPosts()
                ->select();
        $this->_forward('list', 'post', 'brx_Void');
    }


}

