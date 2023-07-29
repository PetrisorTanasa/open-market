<?php
// src/DataFixtures/ProductFixture.php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadProducts($manager);
    }

    private function loadProducts(ObjectManager $manager){

        $productNames = [
            'Smartphone',
            'Laptop',
            'Headphones',
            'Camera',
            'Smart Watch',
            'Tablet',
            'Bluetooth Speaker',
            'Gaming Console',
            'Wireless Earbuds',
            'Fitness Tracker',
            'Electric Toothbrush',
            'Air Fryer',
            'Instant Pot',
            'Robot Vacuum',
            'Coffee Maker',
            'Wireless Router',
            'Power Bank',
            'USB Flash Drive',
            'External Hard Drive',
            'Portable Charger',
            'Printer',
            'Wireless Mouse',
            'LED TV',
            'Monitor',
            'Keyboard',
            'Mouse Pad',
            'Drone',
            'Sneakers',
            'Backpack',
            'Sunglasses',
            'Wristwatch',
            'Wallet',
            'T-shirt',
            'Hoodie',
            'Jeans',
            'Dress',
            'Sweater',
            'Sneakers',
            'Sandals',
            'Running Shoes',
            'Pajamas',
            'Yoga Mat',
            'Dumbbells',
            'Treadmill',
            'Resistance Bands',
            'Jump Rope',
            'Foam Roller',
            'Water Bottle',
            'Protein Powder',
            'Meal Prep Containers',
            'Blender',
            'Face Mask',
            'Hand Sanitizer',
            'Essential Oil Diffuser',
            'Aromatherapy Candles',
            'Scented Oil Refills',
            'Stainless Steel Water Bottle',
            'Stainless Steel Straw',
            'Reusable Grocery Bags',
            'Glass Food Storage Containers',
            'LED Desk Lamp',
            'Wireless Charging Pad',
            'Car Phone Mount',
            'Car Vacuum Cleaner',
            'Car Air Freshener',
            'Car Phone Holder',
            'Car Sun Shade',
            'Car Seat Covers',
            'Car Jump Starter',
            'Car Floor Mats',
            'Car Backseat Organizer',
            'Car Wheel Brush',
            'Car Wash Mitt',
            'Car Windshield Cover',
            'Car Trash Can',
            'Car Air Purifier',
            'Car Seat Cushion',
            'Car Phone Charger',
            'Car Cleaning Kit',
            'Car Dashboard Mat',
            'Car Seat Gap Filler',
            'Car Glass Cleaner',
            'Car Wash Soap',
            'Car Wax',
            'Car Polisher',
            'Car Wheel Cleaner',
            'Car Scratch Remover',
            'Car Headlight Restorer',
            'Car Tire Shine',
            'Car Upholstery Cleaner',
            'Car Leather Cleaner',
            'Car Carpet Cleaner',
            'Car Dashboard Cleaner',
            'Car Bug Remover',
            'Car Paint Sealant',
            'Car Clay Bar',
            'Car Microfiber Towel',
            'Car Detailing Brush',
            'Car Wax Applicator',
            'Car Drying Towel',
            'Car Foam Cannon',
            'Car Tire Dressing',
            'Car Snow Brush',
            'Car Snow Shovel',
            'Car Ice Scraper',
            'Car Ice Melter',
        ];
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 200; $i++) {
            $product = new Product();
            $product->setProduct($faker->randomElement($productNames));
            $product->setQuantity($faker->numberBetween(1, 100));
            $product->setPrice($faker->randomFloat(2, 1, 1000));
            $product->setDescription($faker->sentence());
            $product->setUserId(8);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
