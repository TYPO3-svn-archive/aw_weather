<?php
namespace Alexweb\AwWeather\Domain\Model;

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

/**
 *
 *
 * @package aw_weather
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Weather extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
    protected $apiName;
    protected $query;
    protected $units;
    protected $mode;
    protected $type;
    protected $url;
    protected $aParams = array();
    public $baseWeatherUrl = "http://api.openweathermap.org/data/2.5/";
    public $baseImgUrl = "http://openweathermap.org/img/w/";

    /**
     * @return mixed
     */
    public function getApiName() {
        return $this->apiName;
    }

    /**
     * @param string $apiName
     * possible options weather|find|forecast
     * @return $this
     */
    public function setApiName($apiName = "weather") {
        $this->apiName = $apiName;

        return $this;
    }

    public function getQuery() {
        return $this->query;
    }

    public function setQuery($query) {
        $this->query = $query;
        $this->aParams["q"] = $this->query;

        return $this;
    }

    public function getUnits() {
        return $this->units;
    }

    public function setUnits($units = "metric") {
        $this->units = $units;
        $this->aParams["units"] = $this->units;

        return $this;
    }

    public function getMode() {
        return $this->mode;
    }

    public function setMode($mode = "json") {
        $this->mode = $mode;
        $this->aParams["mode"] = $this->mode;

        return $this;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type = "accurate") {
        $this->type = $type;
        $this->aParams["type"] = $this->type;

        return $this;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl() {
        $this->url =
            $this->baseWeatherUrl
            . $this->getApiName()
            . $this->getParams()
        ;

        return $this;
    }

    protected function getParams()
    {
        $queryString = "?";

        if(!empty($this->aParams))
        {
            foreach($this->aParams as $key => $param)
            {
                $queryString .= "&" . $key . "=" . $param;
            }
        }

        return $queryString;
    }

//$url = "http://api.openweathermap.org/data/2.5/weather?q=frankfurt,germany&units=metric";

}
?>