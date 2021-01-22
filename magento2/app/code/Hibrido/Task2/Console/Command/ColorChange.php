<?php

namespace Hibrido\Task2\Console\Command;

use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ColorChange extends Command
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Config
     */
    private $resourceConfig;
    /**
     * @var TypeListInterface
     */
    private $cacheTypeList;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Config $resourceConfig,
        TypeListInterface $cacheTypeList,
        string $name = null
    ) {
        parent::__construct($name);
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->resourceConfig = $resourceConfig;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('hibrido:color-change');
        $this->setDescription('Magento Color Change');
        $this->addArgument('color');
        $this->addArgument('store_view_id');
        $this->addUsage('bin/magento hibrido:color-change COLOR STORE_VIEW_ID');

        parent::configure();
    }

    /**
     * CLI command description
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $color = $input->getArgument('color');
        if (!$this->validateColor($color)) {
            $output->writeln("<error>Color '{$color}' is invalid!</error>");
            return;
        }

        $storeViewId = $input->getArgument('store_view_id');
        try {
            if (!$storeViewId) {
                throw new NoSuchEntityException();
            }
            $this->storeManager->getStore($storeViewId);
        } catch (NoSuchEntityException $e) {
            $output->writeln("<error>Store view id '{$storeViewId}' is invalid!</error>");
            return;
        }

        $this->resourceConfig->saveConfig(
            'hibrido_theme/buttons/color',
            $color,
            'stores',
            $storeViewId
        );
        $output->writeln("<info>Success!</info>");
        $this->cacheTypeList->cleanType(\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER);
        $this->cacheTypeList->cleanType('full_page');
    }

    private function validateColor(string $color = null): bool
    {
        return ctype_xdigit($color) && in_array(mb_strlen($color), [3, 6, 8]);
    }
}
