<?php

error_reporting(E_ALL); // Set E_ALL for debuging
// elFinder autoload

require dirname(dirname(dirname(__DIR__))) . '/e107_web/js/elfinder/php/autoload.php';
// ===============================================

/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 * */
function access($attr, $path, $data, $volume)
{
    return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
            ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
            : null;                                    // else elFinder decide it itself
}

$opts = [
    'locale' => 'en_US.UTF-8',
    'debug' => true,
    'roots' => [
        [
            'driver' => 'LocalFileSystem',
            'path' => '../images/slides',
            //'startPath'  => '../files/test/',
            //'URL'        => dirname($_SERVER['PHP_SELF']) . '/../files/',
            // 'treeDeep'   => 3,
            'alias' => 'Slides',
            'mimeDetect' => 'internal',
            'tmbURL' => 'images/slides/.tmb',
            'tmbSize' => 186,
            'utf8fix' => true,
            'tmbCrop' => true,
            'tmbBgColor' => 'transparent',
            'accessControl' => 'access',
            'acceptedName' => '/^[^\.].*$/',
            // 'disabled' => array('extract', 'archive'),
            // 'tmbSize' => 128,
            'attributes' => [
                [
                    'pattern' => '/\.js$/',
                    'read' => true,
                    'write' => false
                ],
                [
                    'pattern' => '/^\/icons$/',
                    'read' => true,
                    'write' => false
                ]
            ]
        // 'uploadDeny' => array('application', 'text/xml')
        ]
    ]
];

$connector = new elFinderConnector(new elFinder($opts), true);
$connector->run();
