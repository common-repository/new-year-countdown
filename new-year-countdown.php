<?php
/*
Plugin Name: New Year Countdown
Plugin URI: http://newyear0.com/
Description: A nice countdown to new year.
Author: NewYear0.com
Version: 1.0.1
Author URI: http://newyear0.com/
*/

/*  Copyright 2009  wildblogger  (email : wb@christmas0.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function ny_cdn_widget($args) {
        extract($args);
        
        $swf = 'ny.swf';
        $version = get_option('ny_cdn_version');
        if ($version == 'dark')
            $swf = 'ny_dark.swf';
            
        $width=get_option('ny_cdn_width');
        $wrap=get_option('ny_cdn_wrap');
        if (!is_numeric($width)){
            $width = 200;
        }
        $height = round($width/3);

        $timerTitle=get_option('ny_cdn_title');
        $footerText=get_option('ny_cdn_footer_text');
        $daysCaption=get_option('ny_cdn_days_text');
        $hoursCaption=get_option('ny_cdn_hours_text');
        $minsCaption=get_option('ny_cdn_mins_text');
        $secsCaption=get_option('ny_cdn_secs_text');
        $padding=get_option('ny_cdn_padding');

        $flashVars = '?';
        if (!empty($timerTitle))
                $flashVars .= "&timerCaption=".urlencode($timerTitle);
        if (!empty($daysCaption))
                $flashVars .= "&daysCaption=".urlencode($daysCaption);
        if (!empty($hoursCaption))
                $flashVars .= "&hoursCaption=".urlencode($hoursCaption);
        if (!empty($minsCaption))
                $flashVars .= "&minsCaption=".urlencode($minsCaption);
        if (!empty($secsCaption))
                $flashVars .= "&secsCaption=".urlencode($secsCaption);

        if ($flashVars == '?')
            $flashVars = '';

        if (empty($footerText))
                $footerText = 'New Year Countdown';

        if ($wrap) echo $before_widget;
        if ($wrap) echo $before_title;
        if ($wrap) echo "&nbsp;";
        if ($wrap) echo $after_title;

        echo '<div style="text-align:center;width:'.$width.'px;margin:0;padding:'.$padding.';overflow:hidden;">';
        echo '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$width.'" height="'.$height.'">';
        echo '<param name="movie" value="http://static.newyear0.com/plugins/wp/swf/'.$swf.$flashVars.'" />';
        echo '<!--[if !IE]>-->';
        echo '<object type="application/x-shockwave-flash" data="http://static.newyear0.com/plugins/wp/swf/'.$swf.$flashVars.'" width="'.$width.'" height="'.$height.'">';
        echo '<!--<![endif]-->';
        echo '<!--[if !IE]>-->';
        echo '</object>';
        echo '<!--<![endif]-->';
        echo '</object>';
        echo "<div><a href = \"http://newyear0.com\" title='new year countdown' style='font-size:80%;'>{$footerText}</a></div>";
        echo '</div>';

        if ($wrap) echo $after_widget;
}

function ny_cdn_control(){
    if ($_POST['ny_cdn_width']) {
        ny_cdn_save_option('ny_cdn_width');
        ny_cdn_save_option('ny_cdn_padding');
        ny_cdn_save_option('ny_cdn_version');
        ny_cdn_save_option('ny_cdn_title');
        ny_cdn_save_option('ny_cdn_footer_text');
        ny_cdn_save_option('ny_cdn_days_text');
        ny_cdn_save_option('ny_cdn_hours_text');
        ny_cdn_save_option('ny_cdn_mins_text');
        ny_cdn_save_option('ny_cdn_secs_text');
        update_option('ny_cdn_wrap', isset($_POST['ny_cdn_wrap']));
    }
    ny_cdn_ib('ny_cdn_width', 'Width', ' pixels', 5);
    ny_cdn_ib('ny_cdn_padding', 'Padding', ' <i>i.e. 5<b>px</b>, 10<b>pt</b></i>', 10, '0');
    ny_cdn_select('ny_cdn_version', 'Version', array('light'=>'Light', 'dark'=>'Dark'));
    ny_cdn_ib('ny_cdn_title', 'Caption');
    ny_cdn_ib('ny_cdn_footer_text', 'Footer Text');
    ny_cdn_ib('ny_cdn_days_text', 'Days Caption');
    ny_cdn_ib('ny_cdn_hours_text', 'Hours Caption');
    ny_cdn_ib('ny_cdn_mins_text', 'Minutes Caption');
    ny_cdn_ib('ny_cdn_secs_text', 'Seconds Caption');
    ny_cdn_cb('ny_cdn_wrap', 'Use theme\'s wrapping');
}
function ny_cdn_save_option($var){
    $val=$_POST[$var];
    update_option($var, $val);
}
function ny_cdn_ib($var, $caption, $afterInput='', $size=0, $default=''){
    $value=get_option($var);
    echo '<label for="'.$var.'">'.$caption.':<br/><input id="'.$var.'" name="'.$var.'" type="text"';
    if (!empty($size))
            echo ' size="'.$size.'"';
    echo ' value="';
    if (empty($value) && !empty($default))
        echo $default;
    else
        echo $value;
    echo '" />'.$afterInput.'</label><br/>';
}
function ny_cdn_cb($var, $caption){
    $val=get_option($var);
    echo '<label for="'.$var.'">';
    echo '<input id="'.$var.'" name="'.$var.'" type="checkbox"';
    if ($val)
        echo ' checked';
    echo ' /> '.$caption.'</label><br/>';
}
function ny_cdn_select($var, $caption, $options){
    $selected = get_option($var);
    echo '<label for="'.$var.'">'.$caption.':</label><br/>';
    echo "<select name='{$var}' id='{$var}'>";
    foreach ($options as $ovar=>$ocap){
        echo "<option value='{$ovar}'";
        if ($ovar == $selected)
            echo ' selected';
        echo ">{$ocap}</option>";
    }
    echo "</select><br/>";
}
function init_ny_cdn(){
    register_sidebar_widget("New Year Countdown", "ny_cdn_widget");
    register_widget_control("New Year Countdown", "ny_cdn_control");
}

add_action("plugins_loaded", "init_ny_cdn");

?>