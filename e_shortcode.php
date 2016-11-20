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

    function sc_xpandslider_title($parm = '')
    {
        $tp = e107::getParser();

        return $tp->replaceConstants($this->var['slide']['title']);
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
}