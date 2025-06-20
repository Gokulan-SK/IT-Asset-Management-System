<?php include BASE_PATH . "views/layout/components/quick-access.php"; ?>
<div class="content-frame form-container">
  <h3>Allocate Asset</h3>
  <div>
    <form action="#" method="#" class="form">
      <div class="label-input">
        <label for="employee-id">Select Employee ID</label>
        <select name="employee-id" id="employee-id" required>
          <option value="" disabled selected>
            Select an Employee ID
          </option>
          <option value="emp001">EMP001</option>
          <option value="emp002">EMP002</option>
          <option value="emp003">EMP003</option>
          <option value="emp004">EMP004</option>
          <option value="emp005">EMP005</option>
          <option value="emp006">EMP006</option>
          <option value="emp007">EMP007</option>
          <option value="emp008">EMP008</option>
        </select>
      </div>
      <div class="label-input">
        <label for="employee-name">Employee Name</label>
        <input type="text" name="employee-name" id="employee-name" placeholder="Employee Name" disabled />
      </div>
      <div class="label-input">
        <label for="asset">Select Asset</label>
        <select name="asset" id="asset" required>
          <option value="" disabled selected>select an Assset</option>
          <option value="laptop">Laptop</option>
          <option value="desktop">Desktop</option>
          <option value="monitor">Monitor</option>
          <option value="keyboard">Keyboard</option>
          <option value="mouse">Mouse</option>
          <option value="printer">Printer</option>
          <option value="projector">Projector</option>
          <option value="microsoft-office">Microsoft Office</option>
          <option value="adobe-creative-cloud">
            Adobe Creative Cloud
          </option>
          <option value="antivirus">Antivirus Software</option>
          <option value="vpn-license">VPN License</option>
          <option value="accounting-software">
            Accounting Software
          </option>
          <option value="erp-license">ERP License</option>
        </select>
      </div>
      <div class="label-input">
        <label for="allocation-date">Allocation Date</label>
        <input type="date" name="allocation-date" id="allocation-date" required />
      </div>
      <!-- <div class="label-input">
                  <label for="return-date">Expected Return Date</label>
                  <input
                    type="date"
                    name="return-date"
                    id="return-date"
                    required
                  />
                </div> -->
      <div class="label-input">
        <label for="comments">Comments</label>
        <textarea name="comments" id="comments" placeholder="Any additional comments or instructions" rows="4"
          required></textarea>
      </div>
      <div class="submit-reset">
        <button type="reset">Clear</button>
        <button type="submit">Allocate</button>
      </div>
    </form>
  </div>
</div>