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
use Alexweb\AwWeather\Domain\Model\WeatherWidget;
use Alexweb\AwWeather\Domain\Repository\WeatherWidgetRepository;

/**
 *
 *
 * @package aw_weather
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class WeatherWidgetController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * weatherWidgetRepository
     *
     * @var \Alexweb\AwWeather\Domain\Repository\WeatherWidgetRepository
     * @inject
     */
    protected $weatherWidgetRepository;

    /**
     * action index
     *
     * @return void
     */
    public function indexAction()
    {
    }

    public function listAction()
    {
        $this->weatherWidgetRepository = new WeatherWidgetRepository();

        //var_dump($this->settings["apiName"]);

        switch($this->settings["apiName"]) {
            case "weather":
                $this->getWeather();
            break;

            case "forecast":
            case "forecast___daily":
                $this->getForecast();
            break;
        }
    }

    private function getWeather()
    {
        //var_dump($this->settings);

        $Model = new WeatherWidget();
        $Model
            ->setApiName($this->settings["apiName"])
            ->setCity($this->settings["city"])
            ->setCountry($this->settings["country"])
            ->setUnits($this->settings["units"])
            ->setType()
            ->setMode()
            ->setUrl();

        $url = $Model->getUrl();

        $response = $this->weatherWidgetRepository->getApiResponse($url);

        //var_dump($response);
        $this->view->assign("url", $url);
        $this->view->assign('settings', $this->settings);
        $this->view->assign('apiName', $this->settings["apiName"]);
        $this->view->assign('response', $response);
    }

    private function getForecast()
    {
        //var_dump($this->settings);

        $Model = new WeatherWidget();
        $Model
            ->setApiName($this->settings["apiName"])
            ->setCity($this->settings["city"])
            ->setCountry($this->settings["country"])
            ->setUnits($this->settings["units"])
            ->setCnt($this->settings["cnt"])
            ->setType()
            ->setMode()
            ->setUrl();

        $url = $Model->getUrl();
        $response = $this->weatherWidgetRepository->getApiResponse($url);
        //var_dump($response["list"]);

        $this->view->assign("url", $url);
        $this->view->assign('apiName', $this->settings["apiName"]);
        $this->view->assign('response', $response);
    }
}

?>