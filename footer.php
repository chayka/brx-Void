            </div><!-- container -->
            <!--<div class="pre_footer"></div>-->
        </div>
        <div class="brx_void-footer">
            <div class="brx_void-bottom">
                <div class="container">
                    <div class="bottom_navbar">
                    <?php wp_nav_menu(array('theme_location' => 'main-menu', 'container'=>false, 'menu_class'=>'menu'));?>
                    </div>
                    <!--<div class="copyright">Copyright © 2013 Magna Carta College Oxford. All Rights Reserved.</div>-->
                </div>
            </div>
        </div>
        <div id="scroll_top_button">^</div>
        <script>
            (function($){
                var ratio = $('body').width() / $(window).width();
                var windowHeight = $(window).height()*ratio;
//                alert(ratio);
                var buttonY = Math.floor(windowHeight * 0.66);
                var buttonSince = windowHeight*1;
                var button = $('#scroll_top_button');
                button
                    .css('top', buttonY+'px')
                    .click(function(){
                        $('html, body').animate({
                            scrollTop: 0
                        }, 1000);                
                    });
                
                $.scrolly.addItemToScrollLayout('scroll_top_button', button, [
                    {
                        since: 0,
                        to: buttonSince,
                        alias: 'top',
                        css: {'opacity': 0}
                    },
                    {
                        since: buttonSince,
                        to: 'bottom',
                        alias: 'searchform',
                        css: {'opacity': 1}
                    }
                ]);
            }(jQuery));
        </script>
        <script>
    <?php 
            $userId = get_current_user_id();
            $user = $userId?get_userdata(get_current_user_id()):null;
            if($user):?>
            window.currentUser = window.currentUser || <?php echo json_encode($user);?>;
        <?php else:?>
            window.currentUser = window.currentUser || {ID: 0};
        <?php endif;?>
        </script>
<?php wp_footer(); ?>
    </body>
</html>
