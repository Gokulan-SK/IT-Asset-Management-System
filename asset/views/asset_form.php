<?php include BASE_PATH . "views/layouts/components/quick-access.php"; ?>

<div class="content-frame">
  <h3>Add Asset</h3>
  <div>

    <form action="#" class="form">
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
        <input type="text" name="serial-number" id="serial-number" placeholder="Serial Number" value="" autocomplete=""
          required />
      </div>
      <div class="label-input">
        <label for="date-of-purchase">Date of Purchase</label>
        <input type="date" name="date-of-purchase" id="date-of-purchase" />
      </div>
      <div class="label-input">
        <label for="note">Note</label>
        <textarea name="note" id="note" placeholder="Additional Notes"></textarea>
      </div>
      <div class="label-input hardware">
        <label for="warranty-period">Warranty Period in Months</label>
        <input type="number" name="warranty-period" id="warranty-period" placeholder="Warranty Period in Months"
          value="" autocomplete="" />
      </div>
      <div class="label-input hardware">
        <label for="condition">Asset Condition</label>
        <select name="condition" id="condition" required>
          <option value="new" default>New</option>
          <option value="good">Good</option>
          <option value="needs-repair">needs-repair</option>
          <option value="damaged">Damaged</option>
        </select>
      </div>
      <div class="label-input software">
        <label for="license-key" class=" required">License Key</label>
        <input type="text" name="license-key" id="license-key" placeholder="License Key" value="" autocomplete=""
          required />
      </div>
      <div class="label-input software">
        <label for="license-expiry">License Expiry Date</label>
        <input type="date" name="license-expiry" id="license-expiry" placeholder="License Expiry Date" value=""
          autocomplete="" required />
      </div>
      <div class="label-input">
        <label for="status">Asset Status</label>
        <select name="status" id="status" required value="">
          <option value="" disabled selected>select a status</option>
        </select>
      </div>
      <div class="label-input">
        <label for="unit-price">Unit Price</label>
        <input type="number" name="unit-price" id="unit-price" placeholder="Unit Price" value="" autocomplete="" />
      </div>
      <div class="label-input">
        <label for="image">Image</label>
        <input type="file" name="image" id="image" accept="image/*" placeholder="Upload Image" />
      </div>
      <div class="submit-reset">
        <button type="reset">Clear</button>
        <button type="submit">Save</button>
      </div>
    </form>
  </div>
</div>