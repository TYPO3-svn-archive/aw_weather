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

	/**
	 * weatherRepository
	 *
	 * @var \Alexweb\AwWeather\Domain\Repository\WeatherRepository
	 * @inject
	 */
	protected $weatherRepository;

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
        $aFilesCopied = $this->weatherRepository->installDefaultTheme();
        $aImagesDownloaded = $this->weatherRepository->getDefaultImages();
        $isCssGenerated = $this->weatherRepository->generateCss();

        $this->view->assign("files", $aFilesCopied);
        $this->view->assign("isCssGenerated", $isCssGenerated);
        $this->view->assign("images", $aImagesDownloaded);
    }

    public function uploadThemeAction()
    {
        $isUploaded = false;
        $aZipErrors = $this->weatherRepository->uploadTheme();

        if(isset($_FILES["files"]))
            if(count($aZipErrors) == 0)
                $isUploaded = true;

        $this->view->assign("isUploaded", $isUploaded);
        $this->view->assign("error_messages", $aZipErrors);
    }

    public function generateCssAction()
    {
        $css = "css";
        $GeneralUtility = new GeneralUtility();
        $Post = $GeneralUtility->_POST();

        $themes = $this->weatherRepository->getThemes();

        if(isset($Post["theme"]))
        {
            $this->weatherRepository->setTheme($Post["theme"]);
            $css = $this->weatherRepository->generateCss($Post["theme"]);
        }

        $this->view->assign("themes", $themes);
        $this->view->assign("css", $css);
    }

    protected function getIconsAction()
    {
        $this->weatherRepository->getDefaultImages();
    }

    public function installThemeAction()
    {

    }
}
?>