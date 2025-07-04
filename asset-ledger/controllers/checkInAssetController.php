<?php

require_once BASE_PATH . "asset-ledger/models/AssetLedgerModel.php";
require_once BASE_PATH . "asset/models/AssetModel.php";
require_once BASE_PATH . "employee/models/EmployeeModel.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Step 1: Fetch details for the selected ledger entry
    $ledgerId = isset($_GET['ledger_id']) ? (int) $_GET['ledger_id'] : 0;

    if (!$ledgerId) {
        $_SESSION['error'] = "Invalid ledger ID.";
        header("Location: " . BASE_URL . "asset-ledger/ledger");
        exit;
    }

    try {
        $ledgerEntry = AssetLedgerModel::getLedgerById($conn, $ledgerId);

        if (!$ledgerEntry) {
            $_SESSION['error'] = "Ledger entry not found.";
            header("Location: " . BASE_URL . "asset-ledger/ledger");
            exit;
        }

        $pageTitle = "Check-In Asset";
        $viewToInclude = BASE_PATH . "asset-ledger/views/check_in_form.php";
        require BASE_PATH . "views/layouts/layout.php";
        exit;
    } catch (Exception $e) {
        error_log("Check-In GET Error: " . $e->getMessage());
        $_SESSION['error'] = "Failed to load asset data.";
        header("Location: " . BASE_URL . "asset-ledger/ledger");
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ledgerId = isset($_POST['ledger-id']) ? (int) $_POST['ledger-id'] : 0;
    $checkInDate = $_POST['checkin-date'] ?? null;
    $comments = $_POST['comments'] ?? '';

    if (!$ledgerId || !$checkInDate) {
        $_SESSION['error'] = "Missing required check-in data.";
        header("Location: " . BASE_URL . "asset-ledger/ledger");
        exit;
    }

    try {
        $checkinSuccess = AssetLedgerModel::checkin($conn, $ledgerId, $checkInDate, $comments);

        if ($checkinSuccess) {
            $_SESSION['success'] = "Asset checked in successfully.";
        } else {
            $_SESSION['error'] = "Failed to check in asset.";
        }

        header("Location: " . BASE_URL . "asset-ledger/ledger");
        exit;
    } catch (Exception $e) {
        error_log("Check-In POST Error: " . $e->getMessage());
        $_SESSION['error'] = "An unexpected error occurred during check-in.";
        header("Location: " . BASE_URL . "asset-ledger/ledger");
        exit;
    }
}
