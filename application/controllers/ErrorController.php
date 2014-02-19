<?php

class brx_Void_ErrorController extends Zend_Controller_Action
{

    public function notFound404Action(){
        $this->getResponse()->setHttpResponseCode(404);
        $frag = PostModel::query()
                ->postType(ZF_Core::POST_TYPE_CONTENT_FRAGMENT)
                ->postSlug('not-found')
                ->selectOne();
        $this->view->post = $frag;
    }

}

