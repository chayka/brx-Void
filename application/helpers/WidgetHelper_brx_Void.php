<?php

class WidgetHelper_brx_Void extends WidgetHelper{
    
    public static function renderWidget($data, $tpl, $js, $css = null) {
        parent::addScriptPath(BRX_VOID_APPLICATION_PATH.'/views/scripts');
        return parent::renderWidget($data, $tpl, $js, $css);
    }
    
    public static function renderDummy($user){
        return self::renderWidget(array('user'=>$user), 'widgets/brx.void.Dummy.view.phtml', 'brx.void.Dummy.view');
    }

    public static function renderDummyForm($user){
        return self::renderWidget(array('user'=>$user), 'widgets/brx.void.DummyForm.view.phtml', 'brx.void.DummyForm.view');
    }
}
