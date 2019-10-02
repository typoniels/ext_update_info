<?php
namespace SvenJuergens\ExtUpdateInfo\Backend\ToolbarItem;

use TYPO3\CMS\Backend\Backend\ToolbarItems\SystemInformationToolbarItem;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Toolbar\Enumeration\InformationStatus;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extensionmanager\Utility\ListUtility;

/**
 * VersionToolbarItem
 */
class VersionToolbarItem
{


    /**
     * Modifies the SystemInformation array
     *
     * @param SystemInformationToolbarItem $systemInformationToolbarItem
     * @throws \TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException
     */
    public function appendMessage(SystemInformationToolbarItem $systemInformationToolbarItem)
    {
        $listUtility = GeneralUtility::makeInstance(ObjectManager::class)->get(ListUtility::class);
        $extensions = $listUtility->getAvailableAndInstalledExtensionsWithAdditionalInformation();
        $counter = 0;
        foreach ($extensions as $extension){
            if($extension['updateAvailable'] === true){
                ++$counter;
            }
        }
        if ($counter > 0) {
            $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
            $systemInformationToolbarItem->addSystemMessage(
                sprintf(
                    LocalizationUtility::translate('ext_update_info.updateAvailable', 'ext_update_info'),
                    $counter,
                    (string)$uriBuilder->buildUriFromRoute(
                        'tools_ExtensionmanagerExtensionmanager'
                    )
                ),
                InformationStatus::STATUS_ERROR,
                $counter,
                'tools_ExtensionmanagerExtensionmanager'
            );
        }
    }
}
