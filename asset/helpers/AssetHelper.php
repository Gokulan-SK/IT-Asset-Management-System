<?php
//asset/helpers/AssetHelper.php

class AssetHelper
{
    public static function handleImageUpload(array $file, string $uploadDir = BASE_URL . 'public/uploads')
    {

        //check for empty file or upload error
        if (empty($file['tmp_name'])) {
            return null;
        }

        if (!empty($file['tmp_name']) && $file['error'] !== UPLOAD_ERR_OK) {
            return ["imageError" => "File upload error: " . $file['error']];
        }

        //validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return ["imageError" => "Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed."];
        }

        //Generate unique file name
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFIleName = uniqid('asset_', true) . '.' . strtolower($extension);

        //ensure upload directory
        $targetPath = rtrim($uploadDir, '/') . '/';
        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0755, true);
        }

        //move uploaded file
        $destination = $targetPath . $newFIleName;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return ["path" => $destination];
        }
        return ["imageError" => "Failed to move uploaded file to destination."];
    }
    public static function getCategories()
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

}
?>