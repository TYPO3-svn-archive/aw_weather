<?php
namespace Alexweb\AwWeather\Domain\Repository;

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
class WeatherRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function getApiResponse($url)
    {
        $response = null;

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

        if(isset($response["list"]))
        {
            foreach($response["list"] as $key => $item)
            {
                //flattening the array. i dont see any reason why item['weather'] should be an array of arrays
                if(count($item["weather"]) > 0)
                    $item["weather"] = $item["weather"][0];

                $item["date"] = date("d",$item["dt"]);
                $item["day"] = date("l",$item["dt"]);
                $item["month"] = date("F",$item["dt"]);

                //saving back to the array
                $response["list"][$key] = $item;
            }
        }

        //var_dump($response);
        return $response;
    }

}
?>