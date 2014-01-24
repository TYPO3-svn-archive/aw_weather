<?php
namespace Alexweb\AwWeather\ViewHelpers;

class AddPublicResourcesViewHelper extends  \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    public function __construct()
    {
        $this->registerArgument("theme", "string", "");
    }

    public function render()
    {
        $pageRenderer = new \TYPO3\CMS\Core\Page\PageRenderer();

        $extRelPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath("aw_weather");
        $baseSaveDir = $extRelPath . "../../../fileadmin";
        $extThemeUploadDir = "/tx_awweather/themes/";
        $destinationDir = $baseSaveDir . $extThemeUploadDir .  $this->arguments['theme'];

        $pageRenderer->addCssFile($destinationDir . "/css/styles.css");
        $pageRenderer->addCssFile($destinationDir . "/css/icons.css");

        return $pageRenderer->render();
    }
}
