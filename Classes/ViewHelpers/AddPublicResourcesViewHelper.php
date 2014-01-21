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

        $pageRenderer->addCssFile($extRelPath . "Resources/Public/Themes/" . $this->arguments['theme'] . "/css/styles.css");

        return $pageRenderer->render();
    }
}
