<?php

class WidgetHelper_brx_Void extends WidgetHelper{
    
    public static function renderWidget($data, $tpl, $js, $css = null) {
        parent::addScriptPath(BRX_VOID_APPLICATION_PATH.'/views/scripts');
        return parent::renderWidget($data, $tpl, $js, $css);
    }
    
    public static function renderDummy($user){
        return self::renderWidget(array('user'=>$user), 'widgets/brx.Void.Dummy.view.phtml', 'brx.Void.Dummy.view');
    }

    public static function renderDummyForm($user){
        return self::renderWidget(array('user'=>$user), 'widgets/brx.Void.DummyForm.view.phtml', 'brx.Void.DummyForm.view');
    }
}
