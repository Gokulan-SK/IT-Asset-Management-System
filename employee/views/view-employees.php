<?php
include BASE_PATH . "views/layouts/components/quick-access.php"; ?>

<?php
$employees = $employees ?? [];
$totalRecordsCount = $totalRecordsCount ?? 0;
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$paginationError = $paginationError ?? null;
?>
<div class="content-frame">
  <div class="table-container">
    <div class="table-header">
      <div class="table-heading">
        <h3>Employees</h3>
      </div>
      <div class="table-actions">
        <div class="search-bar">
          <input type="text" placeholder="Search Employee" name="table-search" id="table-search" />
        </div>
        <div class="filter-bar">
          <select id="status-filter">
            <option value="all">All</option>
            <option value="dev">Dev</option>
            <option value="designer">Designer</option>
            <option value="hr">HR</option>
            <option value="manager">Manager</option>
            <option value="service-manager">Service Manager</option>
          </select>
        </div>
      </div>
    </div>

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
    <div class="table">
      <table>
        <thead>
          <tr>
            <th id="table-primary-id">EMPLOYEE ID</th>
            <th>NAME</th>
            <th>DESIGNATION</th>
            <th>MOBILE NO</th>
            <th>EMAIL ID</th>
            <th>ACTIONS</th>
          </tr>
        </thead>
        <tbody>

          <?php if (!empty($employees)): ?>
            <?php foreach ($employees as $emp): ?>
              <tr>
                <td><?= htmlspecialchars($emp["emp_id"]); ?></td>
                <td><?= htmlspecialchars($emp["name"]); ?></td>
                <td><?= htmlspecialchars($emp["designation"]); ?></td>
                <td><?= htmlspecialchars($emp["phone"]); ?></td>
                <td><?= htmlspecialchars($emp["email"]); ?></td>
                <td>
                  <a href="<?= BASE_URL ?>employee/update?id=<?= $emp['emp_id']; ?>"><button
                      class="edit-button">Edit</button></a>
                  <button class="delete-button" data-id="<?= $emp['emp_id']; ?>">Delete</button>

                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" style="text-align:center;">No employee records found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="table-footer">
      <?php if (!empty($paginationError)): ?>
        <div class="error-message">
          <p><?= htmlspecialchars($paginationError); ?></p>
        </div>
      <?php endif; ?>
      <div class="pagination">
        <span>
          <?php
          $limit = 10;
          $offset = ($currentPage - 1) * $limit;

          if ($totalRecordsCount === 0) {
            echo "0-0 of 0";
          } else {
            $from = $offset + 1;
            $to = min($offset + $limit, $totalRecordsCount);
            echo "$from-$to of $totalRecordsCount";
          }
          ?>
        </span>
        <div class="pagination-buttons">
          <button class="<?= $currentPage <= 1 ? 'btn disabled' : 'btn-primary' ?>" <?php echo ($currentPage <= 1 ? 'disabled' : '') ?>
            onclick="window.location.href='<?= BASE_URL ?>employee/view?page=<?= $currentPage - 1 ?>'">←
            Previous</button>
          <button class="<?= $currentPage >= $totalPages ? 'btn disabled' : 'btn-primary ' ?>" <?= $currentPage >= $totalPages ? 'disabled' : '' ?>
            onclick="window.location.href='<?= BASE_URL ?>employee/view?page=<?= $currentPage + 1 ?>'">Next
            →</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Delete confirmation popup -->
<div class="modal" id="delete-modal">
  <form id="delete-form" class="modal-content" method="POST" action="">
    <input type="hidden" name="id" id="delete-item-id" />
    <span class="modal-closebtn">&times;</span>
    <h2>Delete Employee</h2>
    <p>Are you sure you want to delete this employee?</p>
    <div class="button-group">
      <button type="button" class="cancel-button">Cancel</button>
      <button type="submit" class="confirm-button">Confirm</button>
    </div>
  </form>
</div>