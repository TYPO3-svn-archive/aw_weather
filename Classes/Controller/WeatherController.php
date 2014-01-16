<?php
namespace Alexweb\AwWeather\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Alexandros <websurfer992@gmail.com>, alex-web.gr
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
use Alexweb\AwWeather\Domain\Model\Weather;

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

	/**
	 * action list
	 *
	 * @return void
	 */
    public function listAction()
    {
        $response = null;

        $Model = new Weather();
        $Model
            ->setApiName("weather")
            ->setQuery("frankfurt,germany")
            ->setUnits()
            ->setMode()
            ->setType()
            ->setUrl()
        ;

        $url = $Model->getUrl();

        if(curl_init())
        {
            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => 1
            );

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);

            $response = json_decode($response, true);

            if(curl_error($ch))
            {
                $response = array(
                    "error" => curl_error($ch),
                    //"message" => curl_strerror(curl_errno($ch)),
                    "errorno" => curl_errno($ch)
                );
            }

            curl_close($ch);
        }
        elseif(file_get_contents($url))
        {
            $response = json_decode(file_get_contents($url), true);
        }

        if(count($response["weather"]) > 0)
            $response["weather"] = $response["weather"][0];

        //var_dump($response);

        $this->view->assign('response', $response);
    }
}
?>