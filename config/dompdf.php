<?php

return [
    'font_dir' => storage_path('fonts/'),
    'font_cache' => storage_path('fonts/'),
    'temp_dir' => sys_get_temp_dir(),
    'chroot' => realpath(base_path()),
    'allowed_protocols' => [
        'file://' => ['rules' => []],
        'http://' => ['rules' => []],
        'https://' => ['rules' => []],
    ],
    'log_output_file' => null,
    'options' => [
        'enable_font_subsetting' => false,
        'enable_html5_parser' => true,
        'enable_remote' => true,
        'font_height_ratio' => 1.1,
        'isHtml5ParserEnabled' => true,
        'isPhpEnabled' => true,
        'isRemoteEnabled' => true,
        'defaultMediaType' => 'screen',
        'defaultPaperSize' => 'a4',
        'defaultFont' => 'sans-serif',
        'dpi' => 96,
        'fontHeightRatio' => 1,
        'isPhpEnabled' => true,
    ],
];
