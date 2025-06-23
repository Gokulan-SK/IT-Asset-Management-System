<?php

function getCategories()
{
    try {
        $filePath = BASE_PATH . "config/asset_categories.json";
        if (!file_exists($filePath)) {
            error_log("asset/helpers/loadCategories:: File not found: " . $filePath);
            return [];
        }
        $jsonContent = file_get_contents($filePath);
        $categories = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Error decoding JSON: " . json_last_error_msg() . "<br>");
        }
        return $categories;
    } catch (Exception $e) {
        error_log("asset/helpers/loadCategories:: Error loading categories: " . $e->getMessage());
        return [];
    }
}

?>