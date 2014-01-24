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
    protected $cssFolder;
    protected $iconsFolder;
    protected $themesFolder;
    protected $initThemesPath;
    protected $theme = "default";
    protected $imgPath = "http://openweathermap.org/img/w/";
    protected $staticThemesPath = "fileadmin/tx_awweather/themes/";

    public function __construct()
    {
        $this->extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath("aw_weather");
        $this->themesFolder = $this->getThemesFolder();
    }

    protected function getThemesFolder()
    {
        $this->themesFolder = PATH_site . $this->staticThemesPath;

        return $this->themesFolder;
    }

    protected function getCssFolder()
    {
        return $this->cssFolder = $this->themesFolder . $this->theme . "/css/";
    }

    protected function getIconsFolder()
    {
        return $this->iconsFolder = $this->themesFolder . $this->theme . "/icons/";
    }

    protected function getInitThemesPath()
    {
        return $this->initThemesPath = PATH_site .$this->extPath . "Resources/Public/themes/" . $this->theme;
    }

    public function setTheme($theme)
    {
        if(!empty($theme))
            $this->theme = $theme;
    }

    public function getDefaultImages()
    {
        $files = array();
        for($i = 1;$i < 51; $i++)
        {
            $key = $i;
            if($i < 10)
                $key = "0" . $i;

            $file = file_get_contents($this->imgPath . $key . "d.png");
            $fileN = file_get_contents($this->imgPath . $key . "n.png");

            if($file)
            {
                $isFileSaved = $this->saveFile($this->getIconsFolder(), $key . "d.png", $file);
                $files[$key . "d"]["icon"] = $isFileSaved;
            }

            if($fileN)
            {
                $isFileSaved = $this->saveFile($this->getIconsFolder(), $key . "n.png", $fileN);
                $files[$key . "n"]["icon"] = $isFileSaved;
            }
        }

        return $files;
    }

    protected function copyFile($sourceFile, $targetFile)
    {
        $success = copy($sourceFile, $targetFile);

        if($success)
            $message = "Copy successful for " .$targetFile;
        else
            $message = "Copy failed for " .$targetFile;

        return $message;
    }

    protected function saveFile($filePath, $filename, $resource)
    {
        if(!file_exists($filePath))
            mkdir($filePath, 0775, true);

        return $this->writeToFile($filePath . $filename, $resource);
    }

    protected function writeToFile($filename, $resource)
    {
        $message = "Success, wrote to file $filename ";

        if (!$handle = fopen($filename, 'w+'))
            $message = "Cannot open file  $filename ";

        if (fwrite($handle, $resource) === FALSE)
            $message = "Cannot write to file  $filename ";

        fclose($handle);

        return $message;
    }

    public function getThemes()
    {
        $folders = array();

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

    /** TODO a way for mapping the icons to the weather codes
     *  possible options
     *      weather code in filename
     *      visual mapping with the backend module
     */

    public function generateCss()
    {
        //TODO css class name should be the weather code not the filename
        $css = "/*** CAUTION ***/ \n/* File is automatically generated. All changes will be lost ! */\n\n";
        $icons = $this->getIcons();
        $isFileSaved = false;
        $extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath("aw_weather");
        $weatherCodes = json_decode(file_get_contents($extPath . "Resources/Public/themes/default/js/weatherCodes.js"), true);

        if(!empty($weatherCodes))
        {
            $extension = "png";
            foreach($weatherCodes as $weatherCode)
            {
                if(!empty($weatherCode["icon"]))
                {
                    $cssSource = "." . $this->theme . " .icon_" . $weatherCode["id"] . "{ background: url(../icons/" . $weatherCode['icon'] . "." . $extension .") no-repeat;}\n";
                    $css .= $cssSource;
                }
            }

            $isFileSaved = $this->saveFile($this->getCssFolder(), "icons.css", $css);
        }

        return $isFileSaved;
    }

    public function generateJson()
    {
        $css = "/*** CAUTION ***/ \n/* File is automatically generated. All changes will be lost ! */\n\n";
        $icons = $this->getIcons();
        $isFileSaved = false;
        $extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath("aw_weather");
        $weatherCodes = json_decode(file_get_contents($extPath . "Resources/Public/themes/default/js/weatherCodes.js"), true);

        var_dump($icons);
        if(!empty($weatherCodes))
        {
            $extension = "png";
            foreach($weatherCodes as $key => $weatherCode)
            {
                if(!empty($weatherCode["icon"]))
                {
                    $pathInfo = pathinfo($icons[$key]);
                    $cssSource = "." . $this->theme . " .icon_" . $weatherCode["id"] . "{ background: url(../icons/" . $pathInfo['filename'] . "." . $extension .") no-repeat;}\n";
                    $css .= $cssSource;
                }
            }

            $isFileSaved = $this->saveFile($this->getCssFolder(), "icons.css", $css);
        }

        return $isFileSaved;
    }

    protected function getIcons()
    {
        $icons = $this->readDir($this->getIconsFolder());

        return $icons;
    }

    protected function readDir($dir)
    {
        $folders = array();

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($folder = readdir($dh)) !== false)
                {
                    if($folder != "." && $folder != ".." && $folder != "Thumbs.db")
                        $folders[] = $folder;
                }
                closedir($dh);
            }
        }

        return $folders;
    }

    public function installDefaultTheme()
    {
        $aFilesCopied = array();
        $sourceDir = $this->getInitThemesPath() . "/css/";
        $targetDir = $this->getCssFolder();

        if(!file_exists($targetDir))
            mkdir($targetDir, 0775, true);

        $aFiles = $this->readDir($sourceDir);

        if(!empty($aFiles))
            foreach($aFiles as $file)
            {
                $aFilesCopied[]["message"] = $this->copyFile($sourceDir . $file, $targetDir . $file);
            }

        return $aFilesCopied;
    }

    public function uploadTheme()
    {
        $aZipErrors = array();

        if(isset($_FILES["files"]))
        {
            $pathInfo = pathinfo($_FILES['files']['name']);

            /**
             * check for any upload errors
             */
            if($_FILES["files"]["error"] == 0)
            {
                /**
                 * check for right file type
                 */
                if(
                    (
                        $_FILES['files']["type"] == "application/zip" ||
                        $_FILES['files']["type"] == "multipart/x-zip"
                    ) &&
                    $pathInfo["extension"] == "zip"
                )
                {
                    $this->setTheme($pathInfo["filename"]);

                    if(!file_exists($this->themesFolder))
                        mkdir($this->themesFolder, 0775, true);

                    //TODO sanitize filename
                    $filename = $pathInfo["filename"] . "." . $pathInfo["extension"];
                    $target = $this->themesFolder . $filename;

                    //upload and save
                    move_uploaded_file( $_FILES['files']['tmp_name'], $target);

                    //check for errors in the zip file
                    $aZipErrors = $this->checkZip($target);

                    //extract contents and delete zip file
                    if(count($aZipErrors) == 0)
                        $this->extractZipContents($target);

                    unlink($target);
                }
                else
                {
                    $aZipErrors[]["message"] = "file type not supported";
                }
            }
            else
                $aZipErrors[]["message"] = $_FILES["files"]["error"];
        }

        return $aZipErrors;
    }

    protected function checkZip($zip)
    {
        $aErrorMessages = array();

        if(file_exists($zip))
        {
            $pathInfo = pathinfo($zip);

            $ZipArchive = new ZipArchive();
            $ZipArchive->open($zip);

            /**
             * check if the required files and folders are here
             */
            if(!$ZipArchive->statName($pathInfo["filename"] . "/css/"))
                $aErrorMessages[]["message"] = "folder : css not found";

            if(!$ZipArchive->statName($pathInfo["filename"] . "/css/styles.css"))
                $aErrorMessages[]["message"] = "file : css/style.css not found";

            if(!$ZipArchive->statName($pathInfo["filename"] . "/icons/"))
                $aErrorMessages[]["message"] = "folder : icons not found";
        }
        else
            $aErrorMessages[]["message"] = "file does not exist";

        return $aErrorMessages;
    }

    protected function extractZipContents($zip)
    {
        if(file_exists($zip))
        {
            $ZipArchive = new ZipArchive();
            $ZipArchive->open($zip);

            $ZipArchive->extractTo($this->themesFolder);
        }
        else
            $aErrorMessages[]["message"] = "contents could not be extracted";
    }
}

?>