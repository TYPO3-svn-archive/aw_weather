<?php
namespace Alexweb\AwWeather\ViewHelpers;

class AddPublicResourcesViewHelper extends  \TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper {

    public function render() {
        $doc = $this->getDocInstance();
        $pageRenderer = $doc->getPageRenderer();

        $pageRenderer->addCssFile('/typo3conf/ext/aw_weather/Resources/Public/themes/default/css/styles.css');

        $output = $this->renderChildren();
        $output = $doc->startPage("aw_weather") . $output;
        $output .= $doc->endPage();

        return $output;
    }
}
