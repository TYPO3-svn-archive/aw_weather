<?php
namespace Alexweb\AwWeather\Domain\Model;

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

/**
 *
 *
 * @package aw_weather
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Weather extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
    protected $apiName;
    protected $city;
    protected $country;
    protected $units;
    protected $mode;
    protected $type;
    protected $url;
    protected $cnt;
    protected $aParams = array();
    protected $baseWeatherUrl = "http://api.openweathermap.org/data/2.5/";
    protected $baseImgUrl = "http://openweathermap.org/img/w/";

    public function getBaseImgUrl()
    {
        return $this->baseImgUrl;
    }

    /**
     * @param string $apiName
     * possible options weather|forecast/daily
     * @return $this
     */
    public function setApiName($apiName = "weather") {
        if(preg_match("/___/", $apiName))
        {
            $apiParts = explode("___", $apiName);
            $apiName = $apiParts[0] . "/" . $apiParts[1];
        }

        $this->apiName = $apiName;

        return $this;
    }

    public function setCity($city) {
        $this->city = $city;
        $this->aParams["q"] = $this->city;

        return $this;
    }

    public function setCountry($country) {
        $this->country = $country;
        $this->aParams["q"] .= "," . $this->country;

        return $this;
    }

    public function setUnits($units = "metric") {
        $this->units = $units;
        $this->aParams["units"] = $this->units;

        return $this;
    }

    public function setMode($mode = "json") {
        $this->mode = $mode;
        $this->aParams["mode"] = $this->mode;

        return $this;
    }

    public function setType($type = "accurate") {
        $this->type = $type;
        $this->aParams["type"] = $this->type;

        return $this;
    }

    public function setCnt($cnt = null) {
        $this->cnt = $cnt;
        $this->aParams["cnt"] = $this->cnt;

        return $this;
    }

    public function setUrl() {
        $this->url =
            $this->baseWeatherUrl
            . $this->apiName
            . $this->getParams()
        ;

        return $this;
    }

    public function getUrl() {
        return $this->url;
    }

    protected function getParams()
    {
        $queryString = "?";

        if(!empty($this->aParams))
        {
            foreach($this->aParams as $key => $param)
            {
                if(!empty($param))
                    $queryString .= "&" . $key . "=" . $param;
            }
        }

        return $queryString;
    }

//$url = "http://api.openweathermap.org/data/2.5/weather?q=frankfurt,germany&units=metric";

}
?>