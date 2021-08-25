<?php

declare(strict_types=1);

function printFile(string $strFile, string $strType): string {
    if (isset($strFile) && isset($strType)) {
        if (strpos($strFile, '/') == false) {

            $strDirName;
            $strFileExtension;
            switch ($strType) {

                case 'html':
                    $strDirName = $strType;
                    $strFileExtension = $strType;
                    break;

                case 'css':
                    $strDirName = $strType;
                    $strFileExtension = $strType;
                    header("Content-type: text/css");
                    break;

                case 'script':
                    $strDirName = 'javascript';
                    $strFileExtension = 'js';
                    header("Content-type: text/javascript");
                    break;

                case 'png':
                case 'jpg':
                    $strDirName = 'assets';
                    $strFileExtension = $strType;
                    header("Content-type: image/" . ($strType == 'jpg' ? 'jpeg' : $strType));
                    break;
            }
            if (isset($strDirName) && isset($strFileExtension)) {
                $strFileName = '../' . $strDirName . '/' . $strFile . '.' . $strFileExtension;
                if (file_exists($strFileName)) {
                    readfile($strFileName);
                    exit;
                }
            }
            header("HTTP/1.0 418 I'm a teapot");
        } else {
            header("HTTP/1.0 403 Forbidden");
        }
    }
    exit;
}
