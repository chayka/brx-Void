<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<?php 
    global $post;
    $title = '';
    $description = get_bloginfo('description');
    $keywords = '';
    $xPost = null;
    if($post->ID){
        $xPost = PostModel::selectById($post->ID);
    }
    if($xPost){
        $seoDescription = $xPost->getMeta('seo_description');
        $excerpt = $xPost->getExcerpt(true);
        if($seoDescription){
            $description = $seoDescription;
        }elseif($excerpt){
            $description = $excerpt;
        }

        $seoKeywords = $xPost->getMeta('seo_keywords');
        $terms = $xPost->queryTerms(array('post_tag', 'category'))
                ->fields_Names()
                ->select();

        $uncategorized = array_search('Без рубрики', $terms['category']);
        if($uncategorized !== false){
            unset($terms['category'][$uncategorized]);
        }
        $uncategorized2 = array_search('Uncategorized', $terms['category']);
        if($uncategorized2 !== false){
            unset($terms['category'][$uncategorized2]);
        }
        if($seoKeywords){
            $keywords .= ', '.$seoKeywords;
        }else{
            if (!empty ($terms['category'])) {
                $keywords .= ', '.implode(', ', $terms['category']);
            }
            if (!empty ($terms['post_tag'])) {
                $keywords .= ', '.implode(', ', $terms['post_tag']);
            }
        }
        $seoTitle = $xPost->getMeta('seo_title');
    }
    if((is_page() || is_single()) && get_the_title()){
        $title = $seoTitle?$seoTitle:get_the_title(); 
        $title.= ' - ';

    } 
    if(is_404()){
        $title = '404 - ';
    } 
    if(is_category()){
        $headcategory = get_the_category(); 
        $title = $headcategory[0]->name.' - ';
    }			
    $title.= get_bloginfo('name', 'display');
?>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title; ?></title>
        <meta name="description" content="<?php echo $description; ?>"/>
        <meta name="keywords" content="<?php echo $keywords; ?>" />
        <meta name="viewport" content="width=device-width"/>
        <!--<meta name="viewport" content="width=device-width, user-scalable=yes" />-->
        <!--<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />-->
        <link rel="stylesheet" href="<?php brx_Void::baseUrl();?>style.css">
        <script type="text/javascript">less = { env: 'development' };</script>
        <script type="text/javascript">
            window.isMobile = false;
            (function(a){
                if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))){
                    window.isMobile = true;
                }
                window.isMobile |= window.isAndroid = /Android/i.test(a);
                window.isMobile |= window.isWebOs = /webOS/i.test(a);
                window.isMobile |= window.isIPhone = /iPhone/i.test(a);
                window.isMobile |= window.isIPad = /iPad/i.test(a);
                window.isMobile |= window.isIPod = /iPod/i.test(a);
                window.isMobile |= window.isBlackberry = /BlackBerry/i.test(a);
                window.isMobile |= window.isWindowsPhone = /Windows Phone/i.test(a);

            }(navigator.userAgent||navigator.vendor||window.opera));
        </script>
        <?php
            wp_print_scripts(array('jquery'));
        ?>
        <script type="text/javascript">
            (function($, window, document){
                window.fixViewport = function (){
                    if(window.isAndroid){
                        var width = $(window).width();
                        var viewport = '<meta name="viewport" content="width='+width+'px"/>';
                        $('head meta[name=viewport]').remove();
                        $('head').append(viewport);
                    }
                    
                }
                
                setTimeout(window.fixViewport, 0);
            }(jQuery, window, document));
        </script>
        <?php
            wp_enqueue_style('normalize');
//            wp_enqueue_style('bootstrap');
//            wp_enqueue_style('bootstrap-responsive');
//            wp_enqueue_script('bootstrap');
            wp_enqueue_script('modernizr');
            wp_enqueue_script('jquery-scrolly');
            wp_enqueue_style('brx.void.body');
        ?>
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="shortcut icon" href="<?php brx_Void::baseUrl();?>res/img/favicon.ico">
        <!--link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php brx_Void::baseUrl();?>res/img/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php brx_Void::baseUrl();?>res/img/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php brx_Void::baseUrl();?>res/img/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="<?php brx_Void::baseUrl();?>res/img/apple-touch-icon-57-precomposed.png"-->

	<!--wp head-->
		<?php wp_head();?>
	<!--wp head-->
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
<?php if ( is_active_sidebar( 'stats-counters' ) ) : ?>
	<div id="statsCounters" style="display: none;">
            <?php dynamic_sidebar( 'stats-counters' ); ?>
	</div>
<?php endif; ?>
        <div class="brx_void-header">
            <div class="container">
                <a href="/"><img src="<?php brx_Void::baseUrl();?>res/img/logo.png" class="logo"/></a>
                <div class="top_navbar">
                <?php 
                    //WidgetHelper_brx_Void::renderAdaptiveMenu('main-menu');
                    wp_nav_menu(array('theme_location' => 'main-menu', 'container'=>false, 'menu_class'=>'menu'));?>
                </div>
                <div class="brx-auth">
                    <?php if(get_current_user_id()):?>
                    <div class="auth-user_menu">
                        <div class="user_menu-header">
                            <?php echo get_avatar(get_current_user_id(), '48');?>
                            <span class="user-display_name"><?php $user = UserModel::currentUser(); echo $user->getDisplayName(); ?></span>
                        </div>
                        <ul class="user_menu-options">
                            <!--<li><a href="<?php echo $user->getProfileLink();?>">Профиль</a></li>-->
                            <?php if(AclHelper::isAdmin() || AclHelper::isEditor() || AclHelper::isAuthor()):?>
                            <li><a href="/wp-admin/">Консоль</a></li>
                            <li><a href="/user/me">Профиль</a>
                            <?php endif;?>
                            <li><a href="#change-password">Сменить пароль</a></li>
                            <li><a href="#logout">Выход</a></li>
                        </ul>
                    </div>
                                      <!--<a href="#logout">img src="<?php brx_Void::baseUrl();?>res/img/icon-auth-login.png" /<i class="auth-icon-signout"></i>Выход</a>-->
                    <?php if(AclHelper::isAdmin() || AclHelper::isEditor() || AclHelper::isAuthor()):?><!--a href="/wp-admin/"><i class="auth-icon-signin"></i>Консоль</a--><?php endif;?>
                    <?php else:?>
                    <a href="#login"><!--img src="<?php brx_Void::baseUrl();?>res/img/icon-auth-login.png" /--><i class="icon-auth icon-auth-signin"></i>Войти</a>
                    <a href="#join"><!--img src="<?php brx_Void::baseUrl();?>res/img/icon-auth-join.png" /--><i class="icon-auth icon-auth-signup"></i>Регистрация</a>
                    <?php endif;?>

                </div>
            </div>
        </div>
        <div class="brx_void-body">
            <div class="container">
