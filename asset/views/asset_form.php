<?php include BASE_PATH . "views/layouts/components/quick-access.php"; ?>

<div class="content-frame">
  <h3><?= $pageTitle ?? 'Asset Management System' ?></h3>
  <div>
    <?php
    $errors = $errors ?? [];
    $success = $success ?? null;
    $action = $action ?? 'asset/add';
    $errorMessage = $errorMessage ?? null;
    $formData = $formData ?? [];
    $isUpdate = $action == 'asset/update' ? true : false;
    ?>

    <?php if (isset($successMessage)): ?>
      <div class="alert success">
        <span class="closebtn">&times;</span>
        <p><?= htmlspecialchars($successMessage); ?></p>
      </div>
    <?php endif; ?>
    <?php if (isset($errorMessage)): ?>
      <div class="alert error">
        <span class="closebtn">&times;</span>
        <p><?= htmlspecialchars($errorMessage); ?></p>
      </div>
    <?php endif; ?>

    <form action="<?= BASE_URL . $action ?>" method="POST" class="form" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $formData['asset_id'] ?? '' ?>" />
      <div class="label-input">
        <label for="name" class="required">Name</label>

        <input type="text" name="name" id="name" placeholder="Asset Name" pattern="^[a-zA-Z0-9 \-_]+$"
          value="<?= $formData['name'] ?? '' ?>" autocomplete=""
          class="<?= isset($errors['nameError']) ? 'field-error' : '' ?>" <?php if (!$isUpdate): ?> required <?php endif; ?> />
        <?php if (!empty($errors['nameError'])): ?>
          <p class=" error-text"><?php echo htmlspecialchars($errors['nameError']); ?></p>
        <?php endif; ?>


      </div>
      <div class="label-input category-dropdown">
        <label for="category" class=" required">Category</label>
        <select name="category" id="category" <?php if (!$isUpdate): ?> required <?php endif; ?>
          class="<?= isset($errors["categoryError"]) ? 'field-error' : ''; ?>">
          <option value="" disabled selected>
            select a category
          </option>
          <option value="hardware" <?= ($formData["category"] ?? '') === "hardware" ? "selected" : "" ?>>Hardware</option>
          <option value="software" <?= ($formData["category"] ?? '') === "software" ? "selected" : "" ?>>Software</option>
          <option value="office_equipment" <?= ($formData["category"] ?? '') === "office_equipment" ? "selected" : "" ?>>
            Office
            Equipment</option>
          <option value="other" <?= ($formData["category"] ?? '') === "other" ? "selected" : "" ?>>Other</option>
        </select>
        <?php if (!empty($errors['categoryError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['categoryError']); ?></p>
        <?php endif; ?>
      </div>

      <!-- Secondary dropdown (conditionally shown) -->
      <div class="label-input" id="subcategory-dropdown">
        <label for="subcategory" class=" required">Sub Category</label>
        <input type="hidden" id="subcategory-value" value="<?= htmlspecialchars($formData['subcategory'] ?? '') ?>" />

        <select name="subcategory" id="subcategory"
          class="<?= isset($errors["subcategoryError"]) ? 'field-error' : ''; ?>">
          <option value="<?= $formData["subcategory"] ?? '' ?>" selected><?= $formData["subcategory"] ?? '' ?></option>
        </select>
        <?php if (!empty($errors['subcategoryError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['subcategoryError']); ?></p>
        <?php endif; ?>
      </div>
      <div class="label-input hardware hidden">
        <label for="serial-number" class=" required">Serial Number</label>
        <input type="text" name="serial-number" id="serial-number" placeholder="Serial Number"
          value="<?= $formData['serialNumber'] ?? '' ?>" autocomplete="" $pattern="/^[a-zA-Z0-9\-\/:\._\*\+ ]{6,50}$/"
          class="<?= isset($errors["serialNumberError"]) ? 'field-error' : ''; ?>" />
        <?php if (!empty($errors['serialNumberError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['serialNumberError']); ?></p>
        <?php endif; ?>
      </div>
      <div class="label-input">
        <label for="purchase-date">Purchase Date</label>
        <input type="date" min="1900-01-01" name="purchase-date" id="purchase-date"
          value="<?= $formData['purchaseDate'] ?? '' ?>"
          class="<?= isset($errors["purchaseDateError"]) ? 'field-error' : ''; ?>" />
        <?php if (isset($errors['purchaseDateError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['purchaseDateError']); ?></p>
        <?php endif; ?>
      </div>
      <div class="label-input">
        <label for="notes">Note</label>
        <textarea name="notes" id="notes" placeholder="Additional Notes"
          class="<?= isset($errors['notesError']) ? 'field-error' : ''; ?>"><?= $formData['notes'] ?? '' ?></textarea>
        <?php if (!empty($errors['notesError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['notesError']); ?></p>
        <?php endif; ?>
      </div>
      <div class="label-input hardware hidden">
        <label for="warranty-period">Warranty Period in Months</label>
        <input type="number" name="warranty-period" id="warranty-period" placeholder="Warranty Period in Months"
          class="<?= isset($errors['warrantyPeriodError']) ? 'field-error' : ''; ?>" min="0" max="120"
          value="<?= $formData['warrantyPeriod'] ?? '' ?>" autocomplete="" />
        <?php if (!empty($errors['warrantyPeriodError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['warrantyPeriodError']); ?></p>
        <?php endif; ?>
      </div>
      <div class="label-input hardware hidden">
        <label for="condition">Asset Condition</label>
        <select name="condition" id="condition" class="<?= isset($errors['conditionError']) ? 'field-error' : ''; ?>">
          <option value="new" <?= $formData["assetCondition"] ?? '' === "new" ? "selected" : "" ?> default>New</option>
          <option value="good" <?= $formData["assetCondition"] ?? '' === "good" ? "selected" : "" ?>>Good</option>
          <option value="needs_repair" <?= $formData["assetCondition"] ?? '' === "needs_repair" ? "selected" : "" ?>>
            needs-repair
          </option>
          <option value="damaged" <?= $formData["assetCondition"] ?? '' === "damaged" ? "selected" : "" ?>>Damaged</option>
        </select>
        <?php if (!empty($errors['conditionError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['conditionError']); ?></p>
        <?php endif; ?>
      </div>
      <div class="label-input software hidden">
        <label for="license-key" class=" required">License Key</label>
        <input type="text" name="license-key" id="license-key" placeholder="License Key"
          pattern="/^[A-Za-z0-9\_\.+=\[\]\{\}\(\):;,<>\?~\`!\@\#\$\%\^\&\*\|\- ]{12,64}$/"
          value="<?= $formData['licenseKey'] ?? null ?>" autocomplete=""
          class="<?= isset($errors['licenseKeyError']) ? 'field-error' : ''; ?>" />
        <?php if (!empty($errors['licenseKeyError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['licenseKeyError']); ?></p>
        <?php endif; ?>
      </div>
      <div class="label-input software hidden">
        <label for="license-expiry">License Expiry Date</label>
        <input type="date" name="license-expiry" min="1900-01-01" id="license-expiry" placeholder="License Expiry Date"
          value="<?= $formData['licenseExpiry'] ?? '' ?>" autocomplete=""
          class="<?= isset($errors['licenseExpiryError']) ? 'field-error' : ''; ?>" />
        <?php if (!empty($errors['licenseExpiryError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['licenseExpiryError']); ?></p>
        <?php endif; ?>
      </div>
      <div class="label-input">
        <label for="status">Asset Status</label>
        <input type="hidden" id="status-value" value="<?= htmlspecialchars($formData['status'] ?? '') ?>" />
        <select name="status" id="status" class="<?= isset($errors['statusError']) ? 'field-error' : ''; ?>">
          <option value="" disabled selected>select a status</option>
        </select>
        <?php if (!empty($errors['statusError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['statusError']); ?></p>
        <?php endif; ?>
      </div>
      <div class="label-input">
        <label for="unit-price">Unit Price</label>
        <input type="number" name="unit-price" id="unit-price" placeholder="Unit Price" min="0"
          value="<?= $formData['unitPrice'] ?? '' ?>" autocomplete=""
          class="<?= isset($errors['unitPriceError']) ? 'field-error' : ''; ?>" />
        <?php if (!empty($errors['unitPriceError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['unitPriceError']); ?></p>
        <?php endif; ?>
      </div>
      <div class="label-input">
        <label for="asset-image">Image</label>
        <?php if (!empty($formData["image"])): ?>
          <div class="image-preview">
            <a href="data:image/jpeg;base64,<?= htmlspecialchars($formData['image']) ?>" target="_blank">
              <img src="data:image/jpeg;base64,<?= htmlspecialchars($formData['image']) ?>" alt="Asset Image" width="120"
                style="cursor: zoom-in;" />
            </a>

          </div>
        <?php endif; ?>
        <input type="file" name="asset-image" id="asset-image" accept="image/*" placeholder="Upload Image"
          class="<?= isset($errors['imagePathError']) ? 'field-error' : ''; ?>" />
        <?php if (!empty($errors['imageError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['imageError']); ?></p>
        <?php endif; ?>
      </div>
      <div class="submit-reset">
        <button type="reset">Clear</button>
        <button type="submit">Save</button>
      </div>
    </form>
  </div>
</div>