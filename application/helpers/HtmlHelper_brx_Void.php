<?php

/**
 * This helper lets render some specific html pieces outside of MVC logic
 */

class HtmlHelper_brx_Void{
    
    /**
     * Get zend view
     * 
     * @return \Zend_View
     */
    public static function getView(){
        $view = new Zend_View();
        $view->setScriptPath(BRX_VOID_PATH.'application/views/scripts');
        
        return $view;
    }
    
    /**
     * Render zend view with supplied vars
     * 
     * @param string $path
     * @param array $vars
     * @return string
     */
    public static function renderView($path, $vars = array(), $output = true){
        $view = self::getView();
        foreach($vars as $key=>$val){
            $view->assign($key, $val);
        }
        $res = $view->render($path);
        if($output){
            echo $res;
        }
        return $res;
    }
    
    public static function renderPostAttachments($post, $output = true){
        $metaAttachments = $post->getMeta('attachments');
        if($metaAttachments){
            $ids = array();
            $metaAttachments = json_decode($metaAttachments);
            foreach($metaAttachments->attachments as $attachment){
                $ids[]=$attachment->id;
            }
            $attachments = PostModel::query()
                    ->postIdIn($ids)
                    ->postType_Attachment()
                    ->postStatus_Any()
                    ->orderBy_None()
                    ->select();
            return self::renderView('post/attachments.phtml', array(
                'post'=>$post,
                'attachments'=>$attachments,
            ), $output);
        }
        return '';
    }
}

