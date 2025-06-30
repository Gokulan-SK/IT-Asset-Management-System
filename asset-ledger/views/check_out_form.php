<?php include BASE_PATH . "views/layouts/components/quick-access.php"; ?>
<div class="content-frame">
  <h3><?= $pageTitle ?? "Asset Management System" ?></h3>
  <div>
    <?php
    $errors = $errors ?? null;
    $successMessage = $successMessage ?? null;
    $errorMessage = $errorMessage ?? null;

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
    <form action="check-out" method="POST" class="form">
      <div class="label-input">
        <label for="employee-id">Select Employee ID</label>
        <select name="employee-id" id="employee-id" class="select2-selection" style="width: 100%;" required>
          <option value="" disabled selected>
            Select an Employee ID
          </option>
        </select>
      </div>
      <div class="label-input">
        <label for="employee-name">Employee Name</label>
        <input type="text" name="employee-name" id="employee-name" placeholder="Employee Name" disabled />
      </div>
      <div class="label-input">
        <label for="asset">Select Asset</label>
        <select name="asset" id="asset" style="width: 100%;" required>
          <option value="" disabled selected>
            Select an Asset
          </option>
        </select>
      </div>
      <div class="label-input">
        <label for="checkout-date">Allocation Date</label>
        <input type="date" name="checkout-date" id="checkout-date" required />
      </div>
      <div class="label-input">
        <label for="comments">Comments</label>
        <textarea name="comments" id="comments" placeholder="Any additional comments or instructions"
          rows="4"></textarea>
      </div>
      <input type="hidden" name="assigned-by" id="assigned-by" value="<?= $_SESSION['user']['id'] ?>" />
      <div class="submit-reset">
        <button type="reset">Clear</button>
        <button type="submit">Check-out</button>
      </div>
    </form>
  </div>
</div>