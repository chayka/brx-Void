<?php
global $paged, $wp_query, $post;
$link = get_pagenum_link(65635);
$linkPattern = str_replace('65635', '.page.', $link);
PaginationModel::getInstance()->setPageLinkPattern($linkPattern);
//HtmlHelper::setMeta('_linkPattern', $linkPattern);
if (have_posts()) {
    /* Включаем сам LOOP */
    if(is_single() || is_page()){
        the_post();
        echo ZF_Query::processRequest(sprintf('/entry/%d/%s', $post->ID, $post->post_name));
    }else{ 
        // post list
//        $link = get_pagenum_link(65635);
//        $linkPattern = str_replace('65635', '.page.', $link);
//        HtmlHelper::setMeta('_linkPattern', $linkPattern);
        echo ZF_Query::processRequest('/post/list/');
    } 
}else {
    /* в случае ошибки 404 */
    wp_enqueue_style('brx.Void.404');
    echo ZF_Query::processRequest('/not-found-404/');
}