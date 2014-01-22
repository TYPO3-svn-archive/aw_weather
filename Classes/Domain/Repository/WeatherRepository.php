<?php
namespace Alexweb\AwWeather\Domain\Repository;

    /***************************************************************
     *  Copyright notice
     *
     *  (c) 2014 alexandros <websurfer992@gmail.com>, alex-web.gr
     *
     *  All rights reserved
     *
     *  This script is part of the TYPO3 project. The TYPO3 project is
     *  free software; you can redistribute it and/or modify
     *  it under the terms of the GNU General Public License as published by
     *  the Free Software Foundation; either version 3 of the License, or
     *  (at your option) any later version.
     *
     *  The GNU General Public License can be found at
     *  http://www.gnu.org/copyleft/gpl.html.
     *
     *  This script is distributed in the hope that it will be useful,
     *  but WITHOUT ANY WARRANTY; without even the implied warranty of
     *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *  GNU General Public License for more details.
     *
     *  This copyright notice MUST APPEAR in all copies of the script!
     ***************************************************************/
use TYPO3\CMS\Core\Utility\GeneralUtility;
use ZipArchive;

/**
 *
 *
 * @package aw_weather
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class WeatherRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    protected $extPath;
    protected $theme = "default";
    protected $staticThemesPath = "uploads/tx_awweather/themes/";
    protected $cssFolder;
    protected $iconsFolder;
    protected $themesFolder;
    protected $imgPath = "http://openweathermap.org/img/w/";

    public function __construct()
    {
        $this->themesFolder = $this->getThemesDir();
        $this->iconsFolder = $this->themesFolder . $this->theme . "/icons/";
        $this->cssFolder = $this->themesFolder . $this->theme . "/css/";

        //$this->extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath("aw_weather");
    }

    public function getDefaultImages()
    {
        for($i = 1;$i < 51; $i++)
        {
            $key = $i;
            if($i < 10)
                $key = "0" . $i;

            $file = file_get_contents($this->imgPath . $key . "d.png");

            if($file)
                $this->saveFile($this->iconsFolder, $key . "d.png", $file);

            $fileN = file_get_contents($this->imgPath . $key . "n.png");

            if($fileN)
                $this->saveFile($this->iconsFolder, $key . "n.png", $fileN);

        }
    }

    protected function saveFile($filePath, $filename, $resource)
    {
        //var_dump($filePath);

        if(!file_exists($filePath))
            mkdir($filePath, "0775", true);

        $this->writeToFile($filePath . $filename, $resource);
    }

    protected function writeToFile($filename, $resource)
    {
        if (!$handle = fopen($filename, 'w+')) {
            echo "Cannot open file <b>$filename</b> ";
            echo "<br />";
            exit;
        }

        if (fwrite($handle, $resource) === FALSE) {
            echo "Cannot write to file <b>$filename</b> ";
            echo "<br />";
            exit;
        }

        echo "Success, wrote to file <b>$filename</b> ";
        echo "<br />";

        fclose($handle);
    }

    public function getThemes()
    {
        $folders = array();

        //$themesFolder = $this->getThemesDir();
        $themesFolder = $this->themesFolder;

        if (is_dir($themesFolder)) {
            if ($dh = opendir($themesFolder)) {
                while (($folder = readdir($dh)) !== false)
                {
                    if($this->isSupportedFolder($folder))
                    {
                        $folders[]["basename"] = $folder;
                    }
                }
                closedir($dh);
            }
        }

        return $folders;
    }

    protected function isSupportedFolder($folder)
    {
        if( $folder != "." &&
            $folder != ".." &&
            $folder != "_processed_" &&
            $folder != "_migrated_" &&
            $folder != "_migrated" &&
            $folder != "_temp_"
        )
            return true;

        return false;
    }

    public function generateCss()
    {
        $css = null;

        $icons = $this->getIcons();

        if(!empty($icons))
            foreach($icons as $icon)
            {
                $pathInfo = pathinfo($icon);
                $cssSource = "." . $this->theme . " .icon_" . $pathInfo["filename"] . "{ background: url(../icons/" . $pathInfo['filename'] . "." . $pathInfo['extension'] .") no-repeat;}" . "\n";
                $css .= $cssSource;
            }

        $this->saveFile($this->cssFolder, "icons.css", $css);

        //var_dump($css);
        return $css;
    }

    protected function getIcons()
    {
        $icons = $this->readDir($this->iconsFolder);

        return $icons;
    }

    protected function readDir($dir)
    {
        $folders = array();

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($folder = readdir($dh)) !== false)
                {
                    if($folder != "." && $folder != "..")
                        $folders[] = $folder;
                }
                closedir($dh);
            }
        }

        return $folders;
    }

    public function setTheme($theme)
    {
        if(!empty($theme))
            $this->theme = $theme;
    }

    protected function getThemesDir()
    {
        $requestUri = $_SERVER["REQUEST_URI"];
        $parts = explode("/typo3/mod", $requestUri);

        if(isset($parts[0]))
            $this->staticThemesPath = $parts[0] . "/" . $this->staticThemesPath;

        $themesFolder = $_SERVER["DOCUMENT_ROOT"] . $this->staticThemesPath;

        return $themesFolder;
    }

    public function uploadTheme()
    {
        if($_FILES['files']['name'])
        {
            var_dump($_FILES);
            //if no errors...
            if(!$_FILES['files']['error'])
            {
                $valid_file = true;
                //now is the time to modify the future file name and validate the file
                $new_file_name = strtolower($_FILES['files']['tmp_name']); //rename file
                if($_FILES['files']['size'] > (1024000)) //can't be larger than 1 MB
                {
                    $valid_file = false;
                    $message = 'Oops!  Your file\'s size is to large.';
                }

                //if the file has passed the test
                if($valid_file)
                {
                    //move it to where we want it to be
                    move_uploaded_file($_FILES['files']['name'], $this->themesFolder);
                    $message = 'Congratulations!  Your file was accepted.';
                }
            }
            //if there is an error...
            else
            {
                //set that to be the returned message
                $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['files']['error'];
            }

            echo $message;
        }
    }

}

?>