<?php include BASE_PATH . "views/layouts/components/quick-access.php"; ?>

<div class="content-frame">
  <h3>Add Asset</h3>
  <div>
    <?php
    $errors = $errors ?? [];
    $success = $success ?? '';
    $action = $action ?? 'asset/add';
    $errorMessage = $errorMessage ?? '';
    $formData = $formData ?? [];

    ?>

    <form action="<?= BASE_URL . $action ?>" method="POST" class="form" enctype="multipart/form-data">
      <div class="label-input">
        <label for="name" class=" required">Name</label>
        <input type="text" name="name" id="name" placeholder="Asset Name" value="" autocomplete="" required />
      </div>
      <div class="label-input category-dropdown">
        <label for="category" class=" required">Category</label>
        <select name="category" id="category" required>
          <option value="" disabled selected>
            select a category
          </option>
          <option value="hardware">Hardware</option>
          <option value="software">Software</option>
          <option value="office-equipment">Office Equipment</option>
          <option value="other">Other</option>
        </select>
      </div>

      <!-- Secondary dropdown (conditionally shown) -->
      <div class="label-input" id="subcategory-dropdown">
        <label for="subcategory" class=" required">Sub Category</label>
        <select name="subcategory" id="subcategory">
          <option value="" disabled selected>
            select a sub category
          </option>
        </select>
      </div>
      <div class="label-input hardware">
        <label for="serial-number" class=" required">Serial Number</label>
        <input type="text" name="serial-number" id="serial-number" placeholder="Serial Number" value=""
          autocomplete="" />
      </div>
      <div class="label-input">
        <label for="purchase-date">Purchase Date</label>
        <input type="date" name="purchase-date" id="purchase-date" />
      </div>
      <div class="label-input">
        <label for="notes">Note</label>
        <textarea name="notes" id="notes" placeholder="Additional Notes"></textarea>
      </div>
      <div class="label-input hardware">
        <label for="warranty-period">Warranty Period in Months</label>
        <input type="number" name="warranty-period" id="warranty-period" placeholder="Warranty Period in Months"
          value="" autocomplete="" />
      </div>
      <div class="label-input hardware">
        <label for="condition">Asset Condition</label>
        <select name="condition" id="condition">
          <option value="new" default>New</option>
          <option value="good">Good</option>
          <option value="needs-repair">needs-repair</option>
          <option value="damaged">Damaged</option>
        </select>
      </div>
      <div class="label-input software">
        <label for="license-key" class=" required">License Key</label>
        <input type="text" name="license-key" id="license-key" placeholder="License Key" value="" autocomplete="" />
      </div>
      <div class="label-input software">
        <label for="license-expiry">License Expiry Date</label>
        <input type="date" name="license-expiry" id="license-expiry" placeholder="License Expiry Date" value=""
          autocomplete="" />
      </div>
      <div class="label-input">
        <label for="status">Asset Status</label>
        <select name="status" id="status" value="">
        </select>
      </div>
      <div class="label-input">
        <label for="unit-price">Unit Price</label>
        <input type="number" name="unit-price" id="unit-price" placeholder="Unit Price" value="" autocomplete="" />
      </div>
      <div class="label-input">
        <label for="asset-image">Image</label>
        <input type="file" name="asset-image" id="asset-image" accept="image/*" placeholder="Upload Image" />
      </div>
      <div class="submit-reset">
        <button type="reset">Clear</button>
        <button type="submit">Save</button>
      </div>
    </form>
  </div>
</div>