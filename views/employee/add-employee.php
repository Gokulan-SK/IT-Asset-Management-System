<?php include BASE_PATH . "views/layout/components/quick-access.php"; ?>
<div class="content-frame form-container">
  <h3>Add Employee</h3>
  <div>
    <?php
    $errors = $errors ?? [];
    $success = $success ?? '';
    $errorMessage = $errorMessage ?? '';
    $formData = $formData ?? [];
    ?>

    <?php if (!empty($success)): ?>
      <p class="success-text"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
      <p class="error-text"><?php echo htmlspecialchars($errorMessage); ?></p>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>employee/add" method="POST" class="form">
      <div class="label-input">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" pattern="^[a-zA-Z0-9_]+$"
          autocomplete="" required class="<?= isset($errors['usernameError']) ? 'field-error' : '' ?>"
          value="<?= htmlspecialchars($formData['username'] ?? '') ?>" />
        <?php if (!empty($errors['usernameError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['usernameError']); ?></p>
        <?php endif; ?>
      </div>

      <div class="name">
        <div class="first-name">
          <label for="first-name">First Name</label><br />
          <input type="text" name="first-name" placeholder="First Name" pattern="^[a-zA-Z ]+$" minlength="1"
            maxlength="50" class="<?= isset($errors['firstNameError']) ? 'field-error' : '' ?>" required
            value="<?= htmlspecialchars($formData['first-name'] ?? '') ?>" />
          <?php if (!empty($errors['firstNameError'])): ?>
            <p class="error-text"><?php echo htmlspecialchars($errors['firstNameError']); ?></p>
          <?php endif; ?>
        </div>

        <div class="last-name">
          <label for="last-name">Last Name</label><br />
          <input type="text" name="last-name" placeholder="Last Name" pattern="^[a-zA-Z ]+$" minlength="2"
            maxlength="50" class="<?= isset($errors['lastNameError']) ? 'field-error' : '' ?>" required
            value="<?= htmlspecialchars($formData['last-name'] ?? '') ?>" />
          <?php if (!empty($errors['lastNameError'])): ?>
            <p class="error-text"><?php echo htmlspecialchars($errors['lastNameError']); ?></p>
          <?php endif; ?>
        </div>
      </div>

      <div class="label-input">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Email"
          class="<?= isset($errors['emailError']) ? 'field-error' : '' ?>"
          value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required />
        <?php if (!empty($errors['emailError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['emailError']); ?></p>
        <?php endif; ?>
      </div>

      <div class="label-input">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" minlength="8" autocomplete="new-password"
          placeholder="Password" class="<?= isset($errors['passwordError']) ? 'field-error' : '' ?>" required />
        <?php if (!empty($errors['passwordError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['passwordError']); ?></p>
        <?php endif; ?>
      </div>

      <div class="label-input">
        <label for="confirm-password">Confirm Password</label>
        <input type="password" id="confirm-password" name="confirm-password" autocomplete="new-password"
          placeholder="Confirm Password" class="<?= isset($errors['passwordError']) ? 'field-error' : '' ?>" required
          minlength="8" />
      </div>

      <div class="label-input">
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" placeholder="Phone" pattern="^[0-9]{10}$" minlength="10"
          maxlength="10" class="<?= isset($errors['phoneError']) ? 'field-error' : '' ?>"
          value="<?= htmlspecialchars($formData['phone'] ?? '') ?>" required />
        <?php if (!empty($errors['phoneError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['phoneError']); ?></p>
        <?php endif; ?>
      </div>

      <div class="label-input">
        <label for="dob">Date of Birth</label>
        <input type="date" id="dob" min="1950-01-01" max="2007-12-31" name="dob"
          class="<?= isset($errors['dobError']) ? 'field-error' : '' ?>"
          value="<?= htmlspecialchars($formData['dob'] ?? '') ?>" required />
        <?php if (!empty($errors['dobError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['dobError']); ?></p>
        <?php endif; ?>
      </div>

      <div class="label-input">
        <label for="designation">Designation</label>
        <select id="designation" name="designation" required>
          <option value="" disabled <?= empty($formData['designation']) ? 'selected' : '' ?>>Select Designation</option>
          <option value="manager" <?= ($formData['designation'] ?? '') === 'manager' ? 'selected' : '' ?>>Manager</option>
          <option value="employee" <?= ($formData['designation'] ?? '') === 'employee' ? 'selected' : '' ?>>Employee
          </option>
          <option value="intern" <?= ($formData['designation'] ?? '') === 'intern' ? 'selected' : '' ?>>Intern</option>
        </select>
        <?php if (isset($errors['designationError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['designationError']); ?></p>
        <?php endif; ?>
      </div>

      <div class="label-input">
        <label for="is-admin">Is Admin?</label>
        <select id="is-admin" name="is-admin" required>
          <option value="" disabled <?= !isset($formData['is-admin']) ? 'selected' : '' ?>>Select Admin Status</option>
          <option value="1" <?= ($formData['is-admin'] ?? '') == '1' ? 'selected' : '' ?>>Yes</option>
          <option value="0" <?= ($formData['is-admin'] ?? '') == '0' ? 'selected' : '' ?>>No</option>
        </select>
        <?php if (isset($errors['is-adminError'])): ?>
          <p class="error-text"><?php echo htmlspecialchars($errors['is-adminError']); ?></p>
        <?php endif; ?>
      </div>

      <div class="submit-reset">
        <button type="reset">Clear</button>
        <button type="submit">Save</button>
      </div>
    </form>
  </div>
</div>