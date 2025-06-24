<?php

require_once BASE_PATH . "asset/helpers/get_categories.php";
require_once BASE_PATH . "utils/ValidationHelper.php";

class AssetValidator
{
    public static array $errors = [];
    public static function validateName($name): void
    {
        if (empty($name)) {
            self::$errors['nameError'] = 'Asset Name is required.';
            return;
        }

        $pattern = "/^[a-zA-Z0-9\s\-_]+$/";
        $result = preg_match($pattern, $name);

        if (!$result) {
            self::$errors['nameError'] = 'Asset Name can only contain alphanumeric characters, spaces, hyphens, and underscores.';
            return;
        }
        if (!ValidationHelper::isValidLength($name, 1, 100)) {
            $error = 'Asset Name must be between 1 and 100 characters.';
            self::$errors['nameError'] = isset(self::$errors['nameError']) ? self::$errors['nameError'] . " " . $error : $error;
        }
    }

    //this method checks if the given category and subcategory are valid and is present in the config/asset_categories.json file.
    public static function validateCategory(string $category, string $subcategory)
    {
        $categories = AssetHelper::getCategories();

        if (empty($categories)) {
            self::$errors["categoryError"] = "Categories are empty or not defined.";
            return;
        }
        if (!isset($categories[$category])) {
            self::$errors["categoryError"] = "Category $category does not exist.";
            return;
        } else if (isset($subcategory)) {
            if (isset($categories[$category]['subcategory']) && !in_array($subcategory, $categories[$category]['subcategory'])) {
                self::$errors["subcategoryError"] = "subcategory $subcategory does not exist in category $category";
                return;
            }
        }
    }

    public static function validateSerialNumber(string $serialNumber)
    {
        $pattern = "/^[a-zA-Z0-9\-\/:\._\*\+]+$/";
        $result = preg_match($pattern, $serialNumber);
    }

    public static function validateForCreate(mysqli $conn, array $data): array
    {
        self::$errors = []; // Reset errors for each validation call

        $name = $data['name'] ?? null;
        $category = $data['category'] ?? null;
        $subcategory = $data['subcategory'] ?? null;
        $purchaseDate = $data['purchase-date'] ?? null;
        $serialNumber = $data['serial-number'] ?? null;
        $licenseKey = $data['license-key'] ?? null;
        $licenseExpiry = $data['license-expiry'] ?? null;
        $warrantyPeriod = $data['warranty-period'] ?? null;
        $unitPrice = $data['unit-price'] ?? null;
        $status = $data['status'] ?? null;
        $condition = $data['condition'] ?? null;
        $notes = $data['notes'] ?? null;
        $image = $data['image'] ?? null;

        //validate name 
        self::validateName($name);

        //validate category and subcategory
        self::validateCategory($category, $subcategory);

        //validate purchase date
        if (isset($purchaseDate) && !ValidationHelper::isValidDate($purchaseDate)) {
            self::$errors['purchaseDateError'] = 'Purchase Date must be in YYYY-MM-DD format.';
        }

        //validate serial number


        return self::$errors;
    }

}

?>