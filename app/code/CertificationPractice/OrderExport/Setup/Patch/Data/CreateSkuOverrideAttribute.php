<?php

declare(strict_types=1);

namespace CertificationPractice\OrderExport\Setup\Patch\Data;

use CertificationPractice\OrderExport\Attributes;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreateSkuOverrideAttribute implements DataPatchInterface
{
    protected EavSetupFactory $eavSetupFactory;
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup, EavSetupFactory $eavSetupFactory)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            Product::ENTITY,
            Attributes::SKU_OVERRIDE_ATTRIBUTE,
            [
                'type' => 'varchar',
                'label' => 'SKU override',
                'input' => 'text',
                'class' => '',
                'sort_order' => 70,
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'required' => false,
                'user_defined' => false,
                'used_in_product_listing' => true
            ]);
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }
}
