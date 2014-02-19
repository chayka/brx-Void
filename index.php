<?php
global $post;
$mainPost = $post;
ob_start();
include 'loop.php';
$content = ob_get_contents();
ob_clean();
$mainPostZf = PostModel::unpackDbRecord($mainPost);
$mainPostZf->populateWpGlobals();
get_header();
echo $content;
get_footer();


