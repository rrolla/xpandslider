<?php

if(!defined('e107_INIT'))
{
    exit;
}

require_once("conf.php");

class xpandslider_shortcodes extends e_shortcode
{
    function init()
    {
        $sql = e107::getDB();
        $sql->db_Set_Charset("utf8");
        $xpandSliderPrefs = e107::getPlugPref(XPNSLD_NAME); // get plugin prefs

        $slides = $sql->retrieve(XPNSLD_DB, '*', 'visibility IN (' . USERCLASS_LIST . ') ORDER BY position ASC', true);
        
        if (varset($xpandSliderPrefs['xpnsld_camerarandom'])) {
            shuffle($slides);
        }
        $this->setVars(compact('slides'));
    }

    function sc_xpandslider($parm = '')
    {
        $tp = e107::getParser();
        $template = e107::getTemplate(XPNSLD_NAME);

        $text = $tp->parseTemplate($template['start']);
        foreach($this->var['slides'] as $slide)
	{
            $this->addVars(compact('slide')); // set current slide
            $text .= $tp->parseTemplate($template['item']);
	}
        $text .= $tp->parseTemplate($template['end']);

        return $text;
    }

    function sc_xpandslider_id($parm = '')
    {
        $tp = e107::getParser();

        return $tp->replaceConstants($this->var['slide']['id']);
    }

    function sc_xpandslider_caption($parm = '')
    {
        $tp = e107::getParser();

        return $tp->replaceConstants($this->var['slide']['caption']);
    }

    function sc_xpandslider_content($parm = '')
    {
        $tp = e107::getParser();

        return $tp->toHTML($this->var['slide']['content'], true, 'BODY');
    }

    function sc_xpandslider_image($parm = '')
    {
        $tp = e107::getParser();

        return $tp->replaceConstants($this->var['slide']['image']);
    }
    
    function sc_xpandslider_thumb($parm = '')
    {
        $tp = e107::getParser();

        //$thumb = e_BASE .'thumb.php?src=' . e_PLUGIN_ABS . XPNSLD_DIR . XPNSLD_IMG_DIR . $this->var['slide']['image'] .'&w=300&h=300';

        return $tp->replaceConstants($this->var['slide']['image']);
    }

    function sc_xpandslider_data($parm = '')
    {
        $dataArray = json_decode(html_entity_decode($this->var['slide']['extra']));
        //echo '<pre>';
        //print_r($dataArray);
        //exit;

        $htmlData = '';
        foreach ($dataArray as $attr => $val) {
            $htmlData .= 'data-' . $attr . '="' . $val . '" ';
            //$htmlData .= 'data-video="hide"';
        }

        $tp = e107::getParser();

        return $tp->replaceConstants($htmlData);
    }

    function sc_xpandslider_cameraskin($parm = '')
    {
        $xpandSliderPrefs = e107::getPlugPref(XPNSLD_NAME); // get plugin prefs

        require_once(e_PLUGIN . XPNSLD_NAME . '/includes/xpandslider.php');
        $xpandSliderRepo = new xpandslider;

        return $xpandSliderPrefs['xpnsld_cameraskin'] ? $xpandSliderRepo->cameraSkins[$xpandSliderPrefs['xpnsld_cameraskin']] : "";
    }

    function sc_xpandslider_captionfx($parm = '')
    {
        $dataArray = json_decode(html_entity_decode($this->var['slide']['extra']));
        $captionFx = 'fadeIn';

        if (isset($dataArray->captionFx)) {
            $captionFx = $dataArray->captionFx;
        }
        $tp = e107::getParser();

        return $tp->replaceConstants($captionFx);
    }

    function sc_xpandslider_cameraoptions()
    {
        $xpandSliderPrefs = e107::getPlugPref(XPNSLD_NAME);
        $cameraOptionsArr = [
            'width' => $xpandSliderPrefs['xpnsld_camerawidth'],
            'height' => $xpandSliderPrefs['xpnsld_cameraheight'],
            'pagination' => $xpandSliderPrefs['xpnsld_camerapagination'],
            'thumbnails' => $xpandSliderPrefs['xpnsld_camerathumbnails'],
            'loader' => $xpandSliderPrefs['xpnsld_cameraloader'],
            'navigationHover' => false,
            'alignment' => 'bottomCenter',
            'autoAdvance' => true,
            'imagePath' => e_PLUGIN_ABS . XPNSLD_DIR . XPNSLD_IMG_DIR,
            //'hover' => true,
            //'navigation' => true,
        ];
        $tp = e107::getParser();

        return $tp->replaceConstants(json_encode($cameraOptionsArr));
    }
}