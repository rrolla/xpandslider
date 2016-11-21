<?php

class xpandslider_slides extends e_admin_ui {

    // required
    protected $plugincaption = XPNSLD_caption;
    protected $pluginName = XPNSLD_NAME;
    protected $table = XPNSLD_DB;
    protected $pid = "id";

    // optional
    protected $perPage = 50;
    protected $batchDelete = true;
    protected $batchCopy = true;
    protected $listOrder = 'position, id desc';
    protected $sortField = 'position';
    protected $preftabs = [0 => LAN_PLUG_XPNSLD_PREF, 1 => LAN_PLUG_XPNSLD_DEBUG];

    protected $fields = [
        'checkboxes' => [
            'title' => '',
            'type' => null,
            'data' => null,
            'width' => '5%',
            'thclass' => 'center',
            'forced' => true,
            'class' => 'center',
            'toggle' => 'e-multiselect'
        ],
        'id' => [
            'title' => LAN_PLUG_XPNSLD_ID,
            'type' => 'number',
            'data' => 'int',
            'width' => '2%',
            'thclass' => '',
            'class' => '',
            'forced' => true,
            'primary' => true
        ],
        'caption' => [
            'title' => LAN_PLUG_XPNSLD_CAPTION,
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => '',
            'class' => '',
            'help' => LAN_PLUG_XPNSLD_HELP_CAPTION,
            'inline' => true,
            'forced' => true,
            'batch' => true
        ],
        'content' => [
            'title' => LAN_PLUG_XPNSLD_CONTENT,
            'type' => 'bbarea',
            'data' => 'str',
            'width' => 'auto',
            'size' => 'small',
            'thclass' => '',
            'class' => '',
            'parms' => 'rows=20&cols=20',
            'readParms' => 'expand=...&truncate=50&bb=1',
            'writeParms'=>'size=small&template=admin',
        ],
        'image' => [
            'title' => LAN_PLUG_XPNSLD_IMAGE,
            'type' => 'method',
            'width' => 'auto',
            'forced' => true,
        ],
        'created' => [
            'title' => LAN_PLUG_XPNSLD_CREATED,
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => 'disabled',
            'class' => 'disabled',
        ],
        'updated' => [
            'title' => LAN_PLUG_XPNSLD_UPDATED,
            'type' => 'method',
            'width' => 'auto',
            'thclass' => '',
            'class' => 'disabled'
        ],
        'extra' => [
            'title' => LAN_PLUG_XPNSLD_EXTRA,
            'type' => 'method',
            //'width' => 'auto',
            //'thclass' => '',
            'class' => 'bilzuizvele',
            //'nolist' => true
        ],
        'visibility' => [
            'title' => LAN_PLUG_XPNSLD_VISIBILITY,
            'type' => 'userclass',
            'data' => 'int',
            'width' => 'auto',
            'inline' => true,
            'filter' => true,
            'batch' => true
        ],
        'position' => [
            'title' => LAN_PLUG_XPNSLD_POSITION,
            'type' => 'number',
            'width' => 'auto',
            'inline' => true,
            'filter' => true,
            'batch' => true
        ],
        'options' => [
            'title' => LAN_PLUG_XPNSLD_OPTIONS,
            'type' => null,
            'data' => null,
            'width' => '10%',
            'thclass' => 'center last',
            'class' => 'center last',
            'forced' => true
        ]
    ];
    //required - default column user prefs
    protected $fieldpref = ['checkboxes', 'id', 'caption', 'content'];

    protected $prefs = [
        'xpnsld_camerarandom' => [
            'title' => LAN_PLUG_XPNSLD_CAMERARANDOM,
            'type' => 'boolean',
            'tab' => 0,
        ],
        'xpnsld_cameraskin' => [
            'title' => LAN_PLUG_XPNSLD_CAMERASKIN,
            'type' => 'method',
            'tab' => 0,
        ],
        'xpnsld_global_debug' => [
            'title' => '<a href="' . e_ADMIN . '?[debug=everything+]">' . LAN_PLUG_XPNSLD_TURN_ON_GLOBAL_DEBUG .'</a>',
            'type' => 'method',
            'tab' => 1,
            'note' => LAN_PLUG_XPNSLD_SEE . ' e107_handlers/debug_handler.php'
        ],
        'xpnsld_debug' => [
            'title' => LAN_PLUG_XPNSLD_DEBUG,
            'type' => 'bool',
            'tab' => 1,
        ],
    ];

    // optional
    public function init()
    {
        $sql = e107::getDB();
        $sql->db_Set_Charset("utf8");
    }

    public function testPage()
    {
    }

    public function beforeUpdate($new_data, $old_data, $id)
    {
        /*
        echo '<pre>';
        print_r($old_data);
        print_r($new_data);
        exit;

        if(is_numeric($new_data['comment_author_name']) && !empty($new_data['comment_author_name']))
        {
            $userData = e107::user($new_data['comment_author_name']);
            $new_data['comment_author_id'] = $new_data['comment_author_name'];
            $new_data['comment_author_name'] = $userData['user_name'];
        }

        return $new_data;
         *
         */
    }
}

class xpandslider_slides_form_ui extends e_admin_form_ui {

    public function image($curVal, $mode)
    {
        switch ($mode)
        {
            case 'read':
                return '<img src="' . e_BASE .'thumb.php?src=' . e_PLUGIN_ABS . XPNSLD_DIR . XPNSLD_IMG_DIR . $curVal .'&w=100&h=100">';
            case 'write':
                return '<img src="' . e_BASE .'thumb.php?src=' . e_PLUGIN_ABS . XPNSLD_DIR . XPNSLD_IMG_DIR . $curVal .'&w=300&h=300">';
        }
    }
    
    public function updated($curVal, $mode)
    {
        switch ($mode)
        {
            case 'read':
                return $curVal;
            case 'write':
                return date("Y-m-d H:i:s");
        }
    }

    public function extra($curVal, $mode)
    {
        // see http://pixedelic.com/plugins/camera/ data attributes
        $allowed = [
            'alignment',
            'easing',
            'mobileEasing',
            'fx',
            'link',
            'portrait',
            'slideOn',
            'src',
            'target',
            'thumb',
            'time',
            'transPeriod',
            'video',
        ];

        //print_r($curVal);

        //$data = ['alignment' => 'topCenter'];

        //$safe_string_to_store = json_encode($curVal);
        //echo $safe_string_to_store;

        //$array_restored_from_db = json_decode($curVal);

        //print_r($array_restored_from_db);
        //echo $array_restored_from_db;
        //exit;

        switch ($mode)
        {
            case 'read':
                $html = '<script type="text/javascript">
                            dataAttribute = ' . $curVal .  ';
                            dataAttributes.push(dataAttribute);
                         </script>';
                $html .= $curVal;

                return $html;
            case 'write':
                $html = '<script type="text/javascript">
                            dataAttribute = ' . $curVal .  ';
                            dataAttributes.push(dataAttribute);
                         </script>';

                $html .=  $this->text('extra', $curVal, 255, ["id" => "extra", "class" => "tbox"]);

                $html .= '<div id="form-container"></div>';

                return $html;
        }
    }

    public function xpnsld_cameraskin($curVal, $mode)
    {
        require_once(e_PLUGIN . XPNSLD_NAME . '/includes/xpandslider.php');
        $xpandSliderRepo = new xpandslider;

        switch ($mode)
        {
            case 'read':

            case 'write':
                return $this->select('xpnsld_cameraskin', $xpandSliderRepo->cameraSkins, $curVal, $options = [], true);
        }
    }
}
