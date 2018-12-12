<?php

namespace XLiteWeb\tests;

use Facebook\WebDriver\Remote\RemoteWebDriver;

/**
 * @author cerber
 */
class testProductsComparison extends \XLiteWeb\AXLiteWeb
{

    protected $data = [
                 'categoryId' => '2',
                 'statusMessagesAddToCart' => 'Product has been added to your cart',
                 'statusMessagesAdd' => 'The product has been added to the comparison table',
                 'statusMessagesRemoved' => 'The product has been removed from the comparison table',
                 'products' => [['productId' => '37','productName' =>'Apple iPhone 6S [Options & Attributes] [Custom tabs]',
                                                     'attributes' => ['Price' => '$650.00',
                                                                      'Weight' => '2.8 lbs',
                                                                      'Capacity, GB' => '16, 64, 128',
                                                                      'Chip' => 'A9 chip with 64‑bit architecture',
                                                                      'Color' => 'Space Gray, Silver, Gold',
                                                                      'Sim card' => 'Nano-SIM',
                                                                      'Manufacturer' => '',
                                                                      'Display type' => 'Retina HD display',
                                                                      'Dimension, inches' => '4.7',
                                                                      'Resolution' => '1334x750',
                                                                      'GSM model' => 'GSM/EDGE/LTE',
                                                                      'Wi-Fi' => '802.11a/b/g/n/ac',
                                                                      'Bluetooth' => 'Bluetooth 4.2',
                                                                      'GPS' => '',
                                                                      'Battery type' => 'Built-in rechargeable lithium-ion battery',
                                                                      'Talk time' => 'Up to 14 hours on 3G',
                                                                      'Standby time' => 'Up to 10 days (250 hours)',
                                                                      'Video playback' => 'Up to 11 hours',
                                                                      'Audio playback' => 'Up to 50 hours']],
                                ['productId' => '41','productName' =>'Apple iPhone 6S Plus [Options & Attributes]',
                                                     'attributes' => ['Price' => '$850.00',
                                                                      'Weight' => '4.53 lbs',
                                                                      'Capacity, GB' => '16, 64, 128',
                                                                      'Chip' => 'A9 chip with 64‑bit architecture',
                                                                      'Color' => 'Space Gray, Silver, Gold',
                                                                      'Sim card' => 'Nano-SIM',
                                                                      'Manufacturer' => '',
                                                                      'Display type' => 'Retina HD display',
                                                                      'Dimension, inches' => '5.5',
                                                                      'Resolution' => '1920x1080',
                                                                      'GSM model' => 'GSM/EDGE/LTE',
                                                                      'Wi-Fi' => '802.11a/b/g/n/ac',
                                                                      'Bluetooth' => 'Bluetooth 4.2',
                                                                      'GPS' => '',
                                                                      'Battery type' => 'Built-in rechargeable lithium-ion battery',
                                                                      'Talk time' => 'Up to 24 hours on 3G',
                                                                      'Standby time' => 'Up to 16 days (384 hours)',
                                                                      'Video playback' => 'Up to 10 hours',
                                                                      'Audio playback' => 'Up to 40 hours']],
                                ['productId' => '42','productName' =>'Apple iPhone SE [Options & Attributes] [Tabs]',
                                                     'attributes' => ['Price' => '$299.00',
                                                                      'Weight' => '4.1 lbs',
                                                                      'Capacity, GB' => '16, 64',
                                                                      'Chip' => 'A9 chip with 64‑bit architecture',
                                                                      'Color' => 'Space Gray, Silver, Gold, Rose Gold',
                                                                      'Sim card' => 'Nano-SIM',
                                                                      'Manufacturer' => '',
                                                                      'Display type' => 'Retina HD display',
                                                                      'Dimension, inches' => '4',
                                                                      'Resolution' => '1136x640',
                                                                      'GSM model' => '',
                                                                      'Wi-Fi' => '802.11a/b/g/n/ac',
                                                                      'Bluetooth' => 'Bluetooth 4.2',
                                                                      'GPS' => '',
                                                                      'Battery type' => 'Built-in rechargeable lithium-ion battery',
                                                                      'Talk time' => 'Up to 14 hours on 3G',
                                                                      'Standby time' => 'Up to 10 days (250 hours)',
                                                                      'Video playback' => 'Up to 10 hours',
                                                                      'Audio playback' => 'Up to 40 hours']]

                                ]
                 ];

    public function testProductsComparisonTest()
    {

        //добавление в product comparison со страницы продукта
        $counter = $this->addOnProductPage();

        //удаление в product comparison со страницы продукта
        foreach ($this->data['products'] as $product) {

            $customProduct = $this->CustomerProduct;

            $customProduct->load(false,$product['productId']);

            $this->assertTrue($customProduct->validate(), 'Error validating products list page.');

            $customProduct->addToCompare();

            $counter = $counter - 1;

            $this->assertEquals($customProduct->getStatusMessage(), $this->data['statusMessagesRemoved'], 'The product has Not been removed.');
            $this->assertEquals($customProduct->getcounter(), $counter, 'The counter is not valid.');

        }

        $counter = $this->addOnCategoryPage();

        foreach ($this->data['products'] as $product) {

            $category = $this->CustomerCategory;

            $this->assertTrue($category->load(false,$this->data['categoryId']), 'Error loading Category page.');
            $this->assertTrue($category->validate(),'Loaded page is not Category page.');

            $category->addToCompare($product['productId']);

            $counter = $counter - 1;

            $this->assertEquals($category->getStatusMessage(), $this->data['statusMessagesRemoved'], 'The product has Not been removed.');
            $this->assertEquals($category->getcounter(), $counter, 'The counter is not valid.');

        }

        //testClearListOnComparePage()
        //$counter = $this->addOnCategoryPage();
        $this->addOnProductPage();

        $comparisonTable = $this->CustomerProductsComparison;
        $this->assertTrue($comparisonTable->load(false), 'Error loading Comparison Table page.');

        $comparisonTable->clearList();

        $storeFront = $this->CustomerIndex;
        $storeFront->load();
        $this->assertTrue($storeFront->validate(), 'Storefront is inaccessible.');

        $this->assertEquals($storeFront->getcounter(),'' , 'The counter is not valid.');

        //testRemoveOnComparePage()
        //$counter = $this->addOnCategoryPage();
        $counter = $this->addOnProductPage();

        $comparisonTable = $this->CustomerProductsComparison;
        $this->assertTrue($comparisonTable->load(false), 'Error loading Comparison Table page.');

        $comparisonTable->removeProduct($this->data['products'][1]['productId']);

        $this->assertFalse($comparisonTable->checkNameProduct($this->data['products'][1]['productName']), 'The Name product is not removed.');
        $this->assertEquals($comparisonTable->getcounter(), $counter - 1, 'The counter is not valid.');

        $comparisonTable->clearList();

        //testAddToCartOnComparePage()
        //$counter = $this->addOnCategoryPage();
        $counter = $this->addOnProductPage();

        $comparisonTable = $this->CustomerProductsComparison;
        $this->assertTrue($comparisonTable->load(false), 'Error loading Comparison Table page.');

        $comparisonTable->addToCart();

        $this->assertEquals($comparisonTable->getStatusMessage(), $this->data['statusMessagesAddToCart'], 'The product has Not been added.');
        $this->assertEquals($comparisonTable->countProductsToCart(), 1, 'The product has Not been added to Cart .');

        //testDifferencesOnComparePage()
        $comparisonTable = $this->CustomerProductsComparison;
        $this->assertTrue($comparisonTable->load(false), 'Error loading Comparison Table page.');

        $isChecked = $comparisonTable->isDifferencesOnly();

        if ($isChecked == true) {

            $isDiff = $comparisonTable->isPresentDifferentOnlyAttritbutes(count($this->data['products']));
            $this->assertFalse($isDiff, 'Error Differention Product from Comparison Table page.');

        }

        $comparisonTable->clickDifferencesOnly();

        $isChecked = $comparisonTable->isDifferencesOnly();

        if ($isChecked == false) {

            $arrayDiff = $comparisonTable->attributesProduct(count($this->data['products']),$this->data['products']);

            for ($i = 1; $i <= count($this->data['products']); $i++) {

                $arrayDiffProduct[$i] = empty($arrayDiff[$i]);
                $this->assertTrue($arrayDiffProduct[$i], 'Error Differention Product from Comparison Table page.');


            }

        }

    }

    //добавление в product comparison со страницы продукта
    public function addOnProductPage() {

        $counter = 0;

        foreach ($this->data['products'] as $product) {

            $customProduct = $this->CustomerProduct;

            $customProduct->load(false,$product['productId']);

            $this->assertTrue($customProduct->validate(), 'Error validating products list page.');

            $customProduct->addToCompare();

            $counter = $counter + 1;

            $this->assertEquals($customProduct->getStatusMessage(), $this->data['statusMessagesAdd'], 'The product has Not been added.');
            $this->assertEquals($customProduct->getcounter(), $counter, 'The counter is not valid.');

        }

        return $counter;

    }

    //добавление в product comparison со страницы Категории
    public function addOnCategoryPage() {

        $counter = 0;

        foreach ($this->data['products'] as $product) {

            $category = $this->CustomerCategory;

            $this->assertTrue($category->load(false,$this->data['categoryId']), 'Error loading Category page.');
            $this->assertTrue($category->validate(),'Loaded page is not Category page.');

            $category->addToCompare($product['productId']);

            $counter = $counter + 1;

            $this->assertEquals($category->getStatusMessage(), $this->data['statusMessagesAdd'], 'The product has Not been added.');
            $this->assertEquals($category->getcounter(), $counter, 'The counter is not valid.');

        }

        return $counter;

    }

}
