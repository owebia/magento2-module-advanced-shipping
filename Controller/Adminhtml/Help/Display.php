<?php
/**
 * Copyright © 2016-2019 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShipping\Controller\Adminhtml\Help;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class Display extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Framework\Module\Dir\Reader $reader */
        $reader = $this->_objectManager->get(\Magento\Framework\Module\Dir\Reader::class);
        $viewDir = $reader->getModuleDir(
            \Magento\Framework\Module\Dir::MODULE_VIEW_DIR,
            'Owebia_AdvancedShipping'
        );

        $locale = $this->_localeResolver->getLocale();

        $defaultPath = $viewDir . '/doc_en_US.html';
        $localePath = str_replace('en_US', $locale, $defaultPath);

        /** @var Filesystem $filesystem */
        $filesystem = $this->_objectManager->get(Filesystem::class);
        $readInterface = $filesystem->getDirectoryRead(DirectoryList::ROOT);
        $docPath = $readInterface->isFile($localePath) ? $localePath : $defaultPath;
        $docRelativePath = $readInterface->getRelativePath($docPath);

        return $this->resultRawFactory->create()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Content-type', 'text/html; charset=UTF-8', true)
            ->setContents($readInterface->readFile($docRelativePath));
    }
}
