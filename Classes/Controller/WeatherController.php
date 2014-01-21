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
	public function listAction() {
		$weathers = $this->weatherRepository->findAll();
		$this->view->assign('weathers', $weathers);
	}

}
?>