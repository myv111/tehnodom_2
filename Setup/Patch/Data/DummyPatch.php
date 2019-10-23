<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Newcms\Cms\Setup\Patch\Data;

use Magento\Cms\Model\PageFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Filesystem\DirectoryList;

class DummyPatch implements DataPatchInterface, PatchRevertableInterface
{

    private $moduleDataSetup;

    private $pageFactory;

    private $directory;

    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        PageFactory $PageFactory,
        DirectoryList $DirectoryList
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->pageFactory     = $PageFactory;
        $this->directory     = $DirectoryList;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $directory = $this->directory;
        $rootPath = $directory->getRoot();
        $templatePath = $rootPath . "/app/code/Newcms/Cms/view/frontend/templates/";

        $pagesData[] = [
            'title' => 'Title CMS',
            'page_layout' => '1column',
            'meta_keywords' => 'Keywords CMS',
            'meta_description' => 'Description CMS',
            'identifier' => 'cms',
            'content_heading' => 'cms',
            'content' => file_get_contents($templatePath . "astro.phtml"),
            'layout_update_xml' => '',
            'url_key' => 'cms',
            'is_active' => 1,
            'stores' => [0],
            'sort_order' => 0
        ];
        $this->moduleDataSetup->getConnection()->startSetup();

        foreach ($pagesData as $page) {
            $this->pageFactory->create()->setData($page)->save();
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}

