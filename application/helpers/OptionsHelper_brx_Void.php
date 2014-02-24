<?php

class OptionHelper_brx_Void {
    public static function getOption($option, $default='', $reload = false){
        $key = 'brx_Void.'.$option;
        return get_site_option($key, $default, !$reload);
    }
    
    public static function setOption($option, $value){
        $key = 'brx_Void.'.$option;
        return update_site_option($key, $value);
    }
    
}