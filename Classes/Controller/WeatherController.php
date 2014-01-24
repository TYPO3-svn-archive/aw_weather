<?php
namespace Alexweb\AwWeather\Controller;

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
use Alexweb\AwWeather\Domain\Repository\WeatherRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 *
 * @package aw_weather
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class WeatherController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    protected $weatherRepository;
    /**
     * weatherRepository
     *
     * @var \Alexweb\AwWeather\Domain\Repository\WeatherRepository
     * @inject
     */
    protected $aPost = array();

    public function __construct()
    {
        parent::__construct();

        $this->weatherRepository = new WeatherRepository();
    }
    /**
	 * action list
	 *
	 * @return void
	 */
	public function listAction()
    {
        $themes = $this->weatherRepository->getThemes();

        $this->view->assign("themes", $themes);
	}

    public function installDefaultThemeAction()
    {
        $aFilesCopied = array();
        $aImagesDownloaded = array();
        $isCssGenerated = false;

        $GeneralUtility = new GeneralUtility();
        $this->aPost = $GeneralUtility->_POST();

        if(isset($this->aPost["installDefaultTheme"]))
        {
            $aFilesCopied = $this->weatherRepository->installDefaultTheme();
            $aImagesDownloaded = $this->weatherRepository->getDefaultImages();
            $isCssGenerated = $this->weatherRepository->generateCss();
        }

        $this->view->assign("files", $aFilesCopied);
        $this->view->assign("isCssGenerated", $isCssGenerated);
        $this->view->assign("images", $aImagesDownloaded);
    }

    public function uploadThemeAction()
    {
        $isUploaded = false;
        $isCssGenerated = false;
        $aImagesDownloaded = array();
        $aZipErrors = array();

        $GeneralUtility = new GeneralUtility();
        $this->aPost = $GeneralUtility->_POST();

        if(isset($_FILES["files"]))
        {
            $aZipErrors = $this->weatherRepository->uploadTheme();

            if(count($aZipErrors) == 0)
            {
                $isUploaded = true;

                if(isset($this->aPost["getDefaultImages"]))
                    $aImagesDownloaded = $this->weatherRepository->getDefaultImages();

                if(isset($this->aPost["generateCss"]))
                    $isCssGenerated = $this->weatherRepository->generateCss();
            }
        }

        $this->view->assign("isUploaded", $isUploaded);
        $this->view->assign("error_messages", $aZipErrors);
        $this->view->assign("isCssGenerated", $isCssGenerated);
        $this->view->assign("images", $aImagesDownloaded);
    }

    public function generateCssAction()
    {
        $css = "css";
        $themes = $this->weatherRepository->getThemes();

        $GeneralUtility = new GeneralUtility();
        $this->aPost = $GeneralUtility->_POST();

        if(isset($this->aPost["theme"]))
        {
            $this->weatherRepository->setTheme($this->aPost["theme"]);
            $css = $this->weatherRepository->generateCss($this->aPost["theme"]);
        }

        $this->view->assign("themes", $themes);
        $this->view->assign("css", $css);
    }

    public function generateJsonAction()
    {
        $css = "css";
        $themes = $this->weatherRepository->getThemes();

        $GeneralUtility = new GeneralUtility();
        $this->aPost = $GeneralUtility->_POST();

        if(isset($this->aPost["theme"]))
        {
            $this->weatherRepository->setTheme($this->aPost["theme"]);
            $css = $this->weatherRepository->generateJson($this->aPost["theme"]);
        }

        $this->view->assign("themes", $themes);
        $this->view->assign("css", $css);
    }

    protected function getIconsAction()
    {
        $GeneralUtility = new GeneralUtility();
        $this->aPost = $GeneralUtility->_POST();

        if(isset($this->aPost["getIcons"]))
            $this->weatherRepository->getDefaultImages();
    }
}
?>