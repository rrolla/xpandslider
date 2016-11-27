<?php

class xpandslider_slides extends e_admin_ui {

    // required
    protected $pluginTitle = XPNSLD_TITLE;
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
            'writeParms'=>'size=xxlarge',
            'inline' => true,
            'forced' => true,
            'batch' => true
        ],
        'content' => [
            'title' => LAN_PLUG_XPNSLD_CONTENT,
            'type' => 'bbarea',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => '',
            'class' => '',
            'parms' => 'rows=20&cols=20',
            'readParms' => 'expand=...&truncate=50&bb=1',
            'writeParms'=>'size=small&template=admin',
            'help' => LAN_PLUG_XPNSLD_SEE_MORE . ' <a href="http://www.pixedelic.com/plugins/camera/" target="_blank">pixedelic.com/plugins/camera</a> ' . LAN_PLUG_XPNSLD_CONTENT_TEMPLATES,
        ],
        'image' => [
            'title' => LAN_PLUG_XPNSLD_IMAGE,
            'type' => 'method',
            'width' => 'auto',
            'forced' => true,
        ],
        'created' => [
            'title' => LAN_PLUG_XPNSLD_CREATED,
            'type' => 'method',
            'width' => 'auto',
        ],
        'updated' => [
            'title' => LAN_PLUG_XPNSLD_UPDATED,
            'type' => 'method',
            'width' => 'auto',
        ],
        'extra' => [
            'title' => LAN_PLUG_XPNSLD_EXTRA,
            'type' => 'method',
            'width' => 'auto',
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
        'xpnsld_camerawidth' => [
            'title' => LAN_PLUG_XPNSLD_CAMERAWIDTH,
            'type' => 'text',
            'tab' => 0,
        ],
        'xpnsld_cameraheight' => [
            'title' => LAN_PLUG_XPNSLD_CAMERAHEIGHT,
            'type' => 'text',
            'tab' => 0,
        ],
        'xpnsld_cameraautoplay' => [
            'title' => LAN_PLUG_XPNSLD_CAMERAAUTOPLAY,
            'type' => 'boolean',
            'tab' => 0,
        ],
        'xpnsld_cameraloader' => [
            'title' => LAN_PLUG_XPNSLD_CAMERALOADER,
            'type' => 'method',
            'tab' => 0,
        ],
        'xpnsld_camerapagination' => [
            'title' => LAN_PLUG_XPNSLD_CAMERAPAGINATION,
            'type' => 'boolean',
            'tab' => 0,
        ],
        /*
        'xpnsld_camerathumbnails' => [
            'title' => LAN_PLUG_XPNSLD_CAMERATHUMBNAILS,
            'type' => 'boolean',
            'tab' => 0,
        ],
         * 
         */
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
         */
    }
}

class xpandslider_slides_form_ui extends e_admin_form_ui {

    public function image($curVal, $mode)
    {
        switch ($mode)
        {
            case 'read':
                return '<a href="' . e_PLUGIN_ABS . XPNSLD_DIR . XPNSLD_IMG_DIR . $curVal .'" data-gal="prettyPhoto[xpandSlider]"><img src="' . e_BASE .'thumb.php?src=' . e_PLUGIN_ABS . XPNSLD_DIR . XPNSLD_IMG_DIR . $curVal .'&w=100&h=100" class="zoom-image" data-path="' . e_PLUGIN_ABS . XPNSLD_DIR . XPNSLD_IMG_DIR . $curVal .'"></a>';
            case 'write':
                $debug = XPNSLD_DEBUG ? 'true' : 'false';
                return '<img src="' . e_BASE .'thumb.php?src=' . e_PLUGIN_ABS . XPNSLD_DIR . XPNSLD_IMG_DIR . $curVal .'&w=300&h=300" class="choose-image">
                <div id="elfinder"></div>
                <input name="image" value="'. $curVal .'" class="choosed-image hide">
                <script>
                var thumbPhpPath = "' . e_BASE . 'thumb.php";
                var xpandSliderImagespath = "' . e_PLUGIN_ABS . XPNSLD_DIR . XPNSLD_IMG_DIR . '";
                var elFinderConnectorPath = "' . e_PLUGIN_ABS . XPNSLD_DIR .'controllers/connector.php";
                var xpandSliderDebug = ' . $debug . ';

                </script>';
        }
    }

    public function created($curVal, $mode)
    {
        switch ($mode)
        {
            case 'read':
                $e107Date = e107::getDate();
                $ago = $e107Date->computeLapse($e107Date->decodeDateTime($curVal, 'datetime', 'ymd'), time(), false, false, 'short');

                return '<span title="' . $curVal .'">' . $ago . '</span>';
            case 'write':
                $html .=  $this->text('created', $curVal ? $curVal : date("Y-m-d H:i:s"), 255, ["id" => "created", "class" => "tbox hide"]);
                
                if ($curVal) {
                    $e107Date = e107::getDate();
                    $ago = $e107Date->computeLapse($e107Date->decodeDateTime($curVal, 'datetime', 'ymd'), time(), false, false, 'short');
                
                    $html .= '<span title="' . $curVal .'">' . $ago . '</span>';
                }

                return $html;
        }
    }

    public function updated($curVal, $mode)
    {
        switch ($mode)
        {
            case 'read':
                $e107Date = e107::getDate();
                $ago = $e107Date->computeLapse($e107Date->decodeDateTime($curVal, 'datetime', 'ymd'), time(), false, false, 'short');

                return '<span title="' . $curVal .'">' . $ago . '</span>';
            case 'write':
                $html .=  $this->text('updated', date("Y-m-d H:i:s"), 255, ["id" => "updated", "class" => "tbox hide"]);
                
                if ($curVal) {
                    $e107Date = e107::getDate();
                    $ago = $e107Date->computeLapse($e107Date->decodeDateTime($curVal, 'datetime', 'ymd'), time(), false, false, 'short');
                
                    $html .= '<span title="' . $curVal .'">' . $ago . '</span>';
                }

                return $html;
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
                $html .= '<div id="form-container"></div>';
                $html .=  $this->text('extra', $curVal, 255, ["id" => "extra", "class" => "hide"]);

                return $html;
        }
    }

    public function xpnsld_cameraloader($curVal, $mode)
    {
        switch ($mode)
        {
            case 'read':

            case 'write':
                return $this->select('xpnsld_cameraloader', ['bar' => 'bar', 'pie' => 'pie'], $curVal);
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
