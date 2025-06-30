<?php
// utils/validators/AssetValidator.php
require_once BASE_PATH . "config/database.php";
require_once BASE_PATH . "asset/helpers/AssetHelper.php";
require_once BASE_PATH . "utils/ValidationHelper.php";

class AssetValidator
{
    public static function getStatusOptions(): array
    {
        $options = json_decode(file_get_contents(BASE_PATH . "config/asset_status.json"), true);
        return $options;
    }

    public static function validateStatus(string $status, string $category)
    {
        if (empty($status))
            return;

        $status = strtolower($status); // Normalize input
        $category = strtolower($category); // Normalize category too

        $pattern = "/^[a-z_]+$/";
        if (!preg_match($pattern, $status)) {
            self::$errors["statusError"] = "Status can only contain lowercase alphabetic characters and underscores.";
            return;
        }

        $statusOptions = self::getStatusOptions();

        // Normalize config too
        $softwareStatuses = array_map('strtolower', $statusOptions['software'] ?? []);
        $hardwareStatuses = array_map('strtolower', $statusOptions['hardware'] ?? []);

        if ($category === "software") {
            if (!in_array($status, $softwareStatuses)) {
                self::$errors["statusError"] = "Invalid status. Please select a valid status from the software options.";
            }
        } else {
            if (!in_array($status, $hardwareStatuses)) {
                self::$errors["statusError"] = "Invalid status. Please select a valid status from the hardware options.";
            }
        }
    }


    public static function getConditions()
    {
        $conditionOptions = json_decode(file_get_contents(BASE_PATH . "config/asset_conditions.json"), true);

        return $conditionOptions;
    }

    public static function validateCondition(string $condition)
    {
        if (empty($condition)) {
            return;
        }

        $condition = strtolower($condition);

        $pattern = "/^[a-z_]+$/";
        if (!preg_match($pattern, $condition)) {
            self::$errors["conditionError"] = "Condition can only contain lowercase alphabetic characters and underscores.";
            return;
        }

        $conditionOptions = self::getConditions();

        $validConditions = array_map('strtolower', $conditionOptions['condition'] ?? []);

        if (!in_array($condition, $validConditions)) {
            self::$errors["conditionError"] = "Invalid condition. Please select a valid condition from the options provided.";
            return;
        }
    }


    public static array $errors = [];
    public static function validateWarranty($warranty)
    {
        if (!ValidationHelper::isPositiveInteger($warranty) || $warranty > 120) {
            self::$errors["warrantyPeriodError"] = "Warranty Period must be a positive integer and cannot exceed 120 months.";
        }
    }
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

    public static function validateCategory(string $category, string $subcategory)
    {
        $category = strtolower($category);
        $subcategory = strtolower($subcategory ?? '');

        $categories = AssetHelper::getCategories();

        $normalized = [];
        foreach ($categories as $key => $value) {
            $lowerKey = strtolower($key);
            $normalized[$lowerKey] = [
                'subcategories' => array_map('strtolower', $value['subcategories'] ?? [])
            ];
        }

        if (empty($normalized)) {
            self::$errors["categoryError"] = "Categories are empty or not defined.";
            return;
        }

        if (!isset($normalized[$category])) {
            self::$errors["categoryError"] = "Category $category does not exist.";
            return;
        }

        if (!empty($subcategory)) {
            if (!in_array($subcategory, $normalized[$category]['subcategories'])) {
                self::$errors["subcategoryError"] = "Subcategory $subcategory does not exist in category $category.";
            }
        }
    }


    public static function validateSerialNumber(mysqli $conn, string $serialNumber, int $id = null)
    {
        if (empty($serialNumber)) {
            self::$errors["serialNumberError"] = "Serial Number is required.";
            return;
        }
        $pattern = "/^[a-zA-Z0-9\-\/:\._\*\+ ]{6,50}$/";
        $result = preg_match($pattern, $serialNumber);
        if (!$result) {
            self::$errors["serialNumberError"] = "Please enter a valid serial number. It can only contain alphanumeric characters, hyphens, slashes, colons, dots, asterisks, and plus signs. It Number must be between 6 and 50 characters.";
            return;
        } else if (!ValidationHelper::isUnique($conn, "asset", "serial_number", $serialNumber, "asset_id", $id)) {
            self::$errors["serialNumberError"] = "Serial Number must be unique.";
        }
    }

    public static function validateLicenseKey(mysqli $conn, string $licenseKey, int $id = null)
    {
        if (empty($licenseKey)) {
            self::$errors["licenseKeyError"] = "License key is required.";
            return;
        }
        $pattern = "/[A-Za-z0-9\-_\.]{12,64}/";
        $result = preg_match($pattern, $licenseKey);

        if (!$result) {
            self::$errors["licenseKeyError"] = "License key can only contain alphanumeric characters, hyphens, slashes, colons, dots, asterisks, and plus signs. It must be between 12 and 64 characters long.";
            return;
        } else if (!ValidationHelper::isUnique($conn, "asset", "license_key", $licenseKey, "asset_id", $id)) {
            self::$errors["licenseKeyError"] = "License Key must be unique.";
        }

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
        $imagePath = $data['image'] ?? null;

        //validate name 
        self::validateName($name);

        //validate category and subcategory
        self::validateCategory($category, $subcategory);

        //validate purchase date
        if (!empty($purchaseDate) && !ValidationHelper::isValidDate($purchaseDate)) {
            self::$errors['purchaseDateError'] = 'Purchase Date must be in YYYY-MM-DD format.';
        }

        //validate serial number
        if ($category !== "software") {

            self::validateSerialNumber($conn, $serialNumber);

        }
        //validate license key and license expiry
        if ($category === "software") {
            self::validateLicenseKey($conn, $licenseKey);
            if (empty($licenseExpiry))
                self::$errors["licenseExpiryError"] = "License expiry date is required for software assets.";
            else if (!ValidationHelper::isValidDate($licenseExpiry)) {
                self::$errors["licenseExpiryError"] = "License expiry date must be in YYYY-MM-DD format.";
            }
        }

        //validate warranty period
        if ($category !== "software" && !empty($warrantyPeriod)) {
            self::validateWarranty($warrantyPeriod);
        }

        //validate unit price
        if (isset($unitPrice) && !ValidationHelper::isPositiveInteger($unitPrice)) {
            self::$errors["unitPriceError"] = "Unit Price is required and must be a positive integer.";
        }

        //validate status
        self::validateStatus($status, $category);

        //validate condition
        if ($category !== "software")
            self::validateCondition($condition);

        //validate notes
        if (!empty($notes)) {
            if (!ValidationHelper::isValidLength($notes, 0, 1000)) {
                self::$errors["notesError"] = "Notes must be between 0 and 1000 characters.";
            }
        }

        return self::$errors;
    }

    public static function validateForUpdate(mysqli $conn, array $data): array
    {
        self::$errors = []; // Reset previous errors

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
        $imagePath = $data['image'] ?? null;

        if (!is_null($name)) {
            self::validateName($name);
        }

        if (!is_null($category)) {
            self::validateCategory($category, $subcategory ?? '');
        }

        if (!empty($purchaseDate) && !ValidationHelper::isValidDate($purchaseDate)) {
            self::$errors['purchaseDateError'] = 'Purchase Date must be in YYYY-MM-DD format.';
        }

        if (!is_null($serialNumber) && $category !== 'software') {
            self::validateSerialNumber($conn, $serialNumber);
        }

        if ($category === 'software') {
            if (!is_null($licenseKey)) {
                self::validateLicenseKey($conn, $licenseKey);
            }

            if (!is_null($licenseExpiry) && !ValidationHelper::isValidDate($licenseExpiry)) {
                self::$errors["licenseExpiryError"] = "License expiry date must be in YYYY-MM-DD format.";
            }
        }

        if (!is_null($warrantyPeriod) && $category !== 'software') {
            self::validateWarranty($warrantyPeriod);
        }

        if (!is_null($unitPrice) && !ValidationHelper::isPositiveInteger($unitPrice)) {
            self::$errors["unitPriceError"] = "Unit Price must be a positive integer.";
        }

        if (!is_null($status) && !is_null($category)) {
            self::validateStatus($status, $category);
        }

        if (!is_null($condition) && $category !== 'software') {
            self::validateCondition($condition);
        }

        if (!is_null($notes)) {
            if (!ValidationHelper::isValidLength($notes, 0, 1000)) {
                self::$errors["notesError"] = "Notes must be between 0 and 1000 characters.";
            }
        }

        if (!is_null($imagePath)) {
            if (!ValidationHelper::isAbsolutePath($imagePath)) {
                self::$errors["imageError"] = "Invalid image path. Must be an absolute path.";
            }
        }

        return self::$errors;
    }

}

?>