<?php
include BASE_PATH . "views/layouts/components/quick-access.php"; ?>

<?php
$employees = $employees ?? [];
$totalRecordsCount = $totalRecordsCount ?? 0;
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$paginationError = $paginationError ?? null;
$designations = $designations ?? [];
$search = $search ?? '';
$filter = $filter ?? '';
$sort = $sort ?? 'emp_id';
$order = $order ?? 'ASC';
?>
<div class="content-frame">
  <div class="table-container">
    <div class="table-header">
      <div class="table-heading">
        <h3>Employees</h3>
        <span class="record-count">(<?= $totalRecordsCount ?> records found)</span>
      </div>
      <div class="table-actions">
        <div class="search-filter-row">
          <div class="search-bar">
            <input type="text" placeholder="Search employees..." name="table-search" id="table-search" 
                   value="<?= htmlspecialchars($search) ?>" />
            <button type="button" id="clear-search" class="clear-btn" title="Clear search">×</button>
          </div>
          <div class="filter-bar">
            <select id="designation-filter">
              <option value="">All Designations</option>
              <?php foreach ($designations as $designation): ?>
                <option value="<?= htmlspecialchars($designation) ?>" 
                        <?= $filter === $designation ? 'selected' : '' ?>>
                  <?= htmlspecialchars($designation) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="export-actions">
            <button type="button" id="export-csv" class="btn-secondary" title="Export to CSV">
              📊 Export CSV
            </button>
            <button type="button" id="reset-filters" class="btn-secondary" title="Reset all filters">
              🔄 Reset
            </button>
          </div>
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
            <th class="sortable <?= $sort === 'emp_id' ? 'sorted-' . strtolower($order) : '' ?>" 
                data-sort="emp_id">
              EMPLOYEE ID
              <span class="sort-indicator">
                <?= $sort === 'emp_id' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'first_name' ? 'sorted-' . strtolower($order) : '' ?>" 
                data-sort="first_name">
              FIRST NAME
              <span class="sort-indicator">
                <?= $sort === 'first_name' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'last_name' ? 'sorted-' . strtolower($order) : '' ?>" 
                data-sort="last_name">
              LAST NAME
              <span class="sort-indicator">
                <?= $sort === 'last_name' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'designation' ? 'sorted-' . strtolower($order) : '' ?>" 
                data-sort="designation">
              DESIGNATION
              <span class="sort-indicator">
                <?= $sort === 'designation' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'phone' ? 'sorted-' . strtolower($order) : '' ?>" 
                data-sort="phone">
              MOBILE NO
              <span class="sort-indicator">
                <?= $sort === 'phone' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'email' ? 'sorted-' . strtolower($order) : '' ?>" 
                data-sort="email">
              EMAIL ID
              <span class="sort-indicator">
                <?= $sort === 'email' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th>ACTIONS</th>
          </tr>
        </thead>
        <tbody>

          <?php if (!empty($employees)): ?>
            <?php foreach ($employees as $emp): ?>
              <tr>
                <td><?= htmlspecialchars($emp["emp_id"]); ?></td>
                <td><?= htmlspecialchars($emp["first_name"]); ?></td>
                <td><?= htmlspecialchars($emp["last_name"]); ?></td>
                <td><?= htmlspecialchars($emp["designation"]); ?></td>
                <td><?= htmlspecialchars($emp["phone"]); ?></td>
                <td><?= htmlspecialchars($emp["email"]); ?></td>
                <td>
                  <a href="<?= BASE_URL ?>employee/update?id=<?= $emp['emp_id']; ?>" class="btn-link">
                    <button class="edit-button" title="Edit Employee">
                      <img src="<?= BASE_URL ?>public/img/edit-icon.png" alt="Edit" />
                    </button>
                  </a>
                  <button class="delete-button" data-id="<?= $emp['emp_id']; ?>" title="Delete Employee">
                    <img src="<?= BASE_URL ?>public/img/delete-icon.png" alt="Delete" />
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" style="text-align:center;">No employee records found.</td>
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
          <button class="<?= $currentPage <= 1 ? 'btn disabled' : 'btn-primary' ?>" 
                  <?php echo ($currentPage <= 1 ? 'disabled' : '') ?>
                  onclick="navigateToPage(<?= $currentPage - 1 ?>)">
            ← Previous
          </button>
          <button class="<?= $currentPage >= $totalPages ? 'btn disabled' : 'btn-primary ' ?>" 
                  <?= $currentPage >= $totalPages ? 'disabled' : '' ?>
                  onclick="navigateToPage(<?= $currentPage + 1 ?>)">
            Next →
          </button>
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