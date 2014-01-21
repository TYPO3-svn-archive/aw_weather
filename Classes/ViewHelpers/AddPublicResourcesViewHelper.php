<?php
namespace Alexweb\AwWeather\ViewHelpers;

class AddPublicResourcesViewHelper extends  \TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper {

    public function render() {
        $doc = $this->getDocInstance();
        $pageRenderer = $doc->getPageRenderer();
        $extRelPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath("aw_weather");

        $pageRenderer->addCssFile($extRelPath . "Resources/Public/Themes/default/css/styles.css");

        $output = $this->renderChildren();
        $output = $doc->startPage("aw_weather") . $output;
        $output .= $doc->endPage();

        return $output;
    }
}
