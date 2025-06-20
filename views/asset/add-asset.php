<?php include BASE_PATH . "views/layout/components/quick-access.php"; ?>

<div class="content-frame form-container">
  <h3>Add Asset</h3>
  <div>
    <form action="#" class="form">
      <div class="label-input asset-category-dropdown">
        <label for="asset-category">Category</label>
        <select name="asset-category" id="asset-category" required>
          <option value="" disabled selected>
            select a category
          </option>
          <option value="software">Software</option>
          <option value="laptop">Laptop</option>
          <option value="desktop">Desktop</option>
          <option value="monitor">Monitor</option>
          <option value="server">Server</option>
          <option value="av equipment">AV Equipment</option>
          <option value="printer">Printer</option>
          <option value="mobile device">Mobile Device</option>
          <option value="network equipment">Network Equipment</option>
          <option value="office equipment">Office Equipment</option>
          <option value="other">Other</option>
        </select>
      </div>

      <!-- Secondary dropdown (conditionally shown) -->
      <div class="label-input software hidden">
        <label for="software-category">Software Type</label>
        <select id="software-category" name="software-category" value="">
          <option value="" disabled selected>
            Select a software type
          </option>
          <option value="operating system">Operating System</option>
          <option value="productivity tool">Productivity Tool</option>
          <option value="development tool">Development Tool</option>
          <option value="security software">Security Software</option>
          <option value="enterprise software">
            Enterprise Software
          </option>
          <option value="cloud subscription">
            Cloud Subscription
          </option>
          <option value="other">Other</option>
        </select>
      </div>
      <div class="label-input">
        <label for="asset-name">Name</label>
        <input type="text" name="asset-name" id="asset-name" placeholder="Asset Name" value="" autocomplete=""
          required />
      </div>
      <div class="label-input hardware">
        <label for="serial-number">Serial Number</label>
        <input type="text" name="serial-number" id="serial-number" placeholder="Serial Number" value="" autocomplete=""
          required />
      </div>
      <div class="label-input software hidden">
        <label for="license-key">License Key</label>
        <input type="text" name="license-key" id="license-key" placeholder="License Key" value="" autocomplete=""
          required />
      </div>
      <div class="label-input software hidden">
        <label for="license-expiry">License Expiry Date</label>
        <input type="date" name="license-expiry" id="license-expiry" placeholder="License Expiry Date" value=""
          autocomplete="" required />
      </div>
      <!-- <div class="label-input">
                  <label for="description">Description</label>
                  <textarea
                    name="description"
                    id="description"
                    placeholder="Asset Description"
                    required
                  ></textarea>
                </div>
                <div class="label-input">
                  <label for="unit-price">Unit Price</label>
                  <input
                    type="number"
                    name="unit-price"
                    id="unit-price"
                    placeholder="Unit Price"
                    value=""
                    autocomplete=""
                    required
                  />
                </div>
                <div class="label-input">
                  <label for="status">Asset Status</label>
                  <select name="status" id="status" required value="">
                    <option value="" disabled selected>select a status</option>
                  </select>
                </div>
                <div class="label-input hardware">
                  <label for="asset-condition">Asset Condition</label>
                  <select name="asset-condition" id="asset-condition" required>
                    <option value="" disabled selected>
                      select a condition
                    </option>
                    <option value="new">New</option>
                    <option value="good">Good</option>
                    <option value="needs-repair">needs-repair</option>
                    <option value="damaged">Damaged</option>
                  </select>
                </div> -->
      <div class="label-input">
        <label for="date-of-purchase">Date of Purchase</label>
        <input type="date" name="date-of-purchase" id="date-of-purchase" required />
      </div>
      <!-- <div class="label-input hardware">
                  <label for="date-of-manufacture">Date of Manufacture</label>
                  <input
                    type="date"
                    name="date-of-manufacture"
                    id="date-of-manufacture"
                  />
                </div> -->
      <!-- <div class="label-input">
                  <label for="warranty-period">Warranty Period in Months</label>
                  <input
                    type="number"
                    name="warranty-period"
                    id="warranty-period"
                    placeholder="Warranty Period in Months"
                    value=""
                    autocomplete=""
                  />
                </div> -->
      <!-- <div class="label-input">
                  <label for="note">Note</label>
                  <textarea
                    name="note"
                    id="note"
                    placeholder="Additional Notes"
                  ></textarea>
                </div> -->
      <div class="label-input">
        <label for="image">Image</label>
        <input type="file" name="image" id="image" accept="image/*" placeholder="Upload Image" required />
      </div>
      <div class="submit-reset">
        <button type="reset">Clear</button>
        <button type="submit">Save</button>
      </div>
    </form>
  </div>
</div>
<script src="../../public/js/asset/add-asset.js"></script>