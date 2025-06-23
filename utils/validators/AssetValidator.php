<?php

require_once BASE_PATH . "asset/helpers/get_categories.php";
require_once BASE_PATH . "utils/Helpers.php";

class AssetValidator
{
    public static function validateCategories($formCategory, $formSubCategory)
    {
        $errors = [];

        $categories = getCategories();

        foreach ($categories as $category) {
            if ($category['label'] != $formCategory) {
                continue;
            }
        }

        return true;
    }
}

?>