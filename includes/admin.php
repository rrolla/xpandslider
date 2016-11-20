<?php

class plugin_xpandslider_admin extends e_admin_dispatcher {

    protected $modes = [
        'slides' => [
            'controller' => 'xpandslider_slides',
            'path' => 'controllers/xpandslider_slides.php',
            'ui' => 'xpandslider_slides_form_ui',
            'uipath' => null,
        ],
    ];

    protected $defaultMode = "slides";
    protected $defaultAction = "list";

    protected $adminMenu = [
        'other' => [
            'divider' => true
        ],
        'other1' => [
            'header' => LAN_PLUG_XPNSLD_SLIDES
        ],
        'slides/list' => [
            'text' => LAN_PLUG_XPNSLD_LIST,
            'perm' => '0',
        ],
        'slides/create' => [
            'caption' => LAN_PLUG_XPNSLD_CREATE,
            'perm' => '0',
            'include' => 'data-href="test"'
        ],
        'other7' => [
            'header' => LAN_PLUG_XPNSLD_PREFS
        ],
        'slides/prefs' => [
            'caption' => LAN_PLUG_XPNSLD_PREFS,
            'perm' => '0'
        ],
    ];

    /**
     * Optional, mode/action aliases, related with 'selected' menu CSS class
     * Format: 'MODE/ACTION' => 'MODE ALIAS/ACTION ALIAS';
     * This will mark active main/list menu item, when current page is main/edit
     * @var array
     */
    protected $adminMenuAliases = [
        'slides/edit' => 'slides/create'
    ];

    /**
     * Navigation menu title
     * @var string
     */
    protected $menuTitle = XPNSLD_TITLE;
}
