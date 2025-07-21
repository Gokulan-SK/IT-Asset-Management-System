<?php
include BASE_PATH . "views/layouts/components/quick-access.php";

$assets = $assets ?? [];
$totalRecordsCount = $totalRecordsCount ?? 0;
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$paginationError = $paginationError ?? null;
?>
<div class="content-frame">
  <div class="table-container">
    <div class="table-header">
      <div class="table-heading">
        <h3>Assets</h3>
      </div>
      <div class="table-actions">
        <div class="search-bar">
          <input type="text" placeholder="Search by name, category, status..." name="table-search" id="table-search"
            disabled />
        </div>
        <div class="filter-bar">
          <select id="status-filter" disabled>
            <option value="all">All</option>
            <option value="available">Available</option>
            <option value="assigned">Assigned</option>
            <option value="under-maintenance">Under Maintenance</option>
            <option value="disposed">Disposed</option>
            <option value="new">New</option>
            <option value="good">Good</option>
            <option value="repair-needed">Repair Needed</option>
            <option value="damaged">Damaged</option>
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
            <th id="table-primary-id">Asset ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Subcategory</th>
            <th>Status</th>
            <th>Assigned To</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($assets)): ?>
            <?php foreach ($assets as $asset): ?>
              <tr>
                <td><?= htmlspecialchars($asset["asset_id"]); ?></td>
                <td><?= htmlspecialchars($asset["asset_name"]); ?></td>
                <td><?= htmlspecialchars($asset["category"] ?? $asset["subcategory"]); ?></td>
                <td><?= htmlspecialchars($asset["subcategory"] ?? "-"); ?></td>
                <td><?= htmlspecialchars($asset["asset_status"] ?? "-"); ?></td>
                <td><?= htmlspecialchars($asset["employee_name"] ??
                  "-") ?></td>
                <td>
                  <a href="<?= BASE_URL ?>asset/update?id=<?= $asset['asset_id']; ?>">
                    <button class="edit-button">Edit</button>
                  </a>
                  <button class="delete-button" data-id="<?= $asset['asset_id']; ?>">Delete</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" style="text-align:center;">No asset records found.</td>
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
            onclick="window.location.href='<?= BASE_URL ?>asset/view?page=<?= $currentPage - 1 ?>'" <?= $currentPage <= 1 ? 'disabled' : '' ?>>
            <- Previous </button>
              <button class="<?= $currentPage >= $totalPages ? 'btn disabled' : 'btn-primary' ?>" <?= ($currentPage >= $totalPages ? 'disabled' : '') ?>
                onclick="window.location.href='<?= BASE_URL ?>asset/view?page=<?= $currentPage + 1 ?>'">Next →</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Delete confirmation modal -->
<div class="modal" id="delete-modal">
  <form id="delete-form" class="modal-content" method="POST">
    <input type="hidden" name="id" id="delete-item-id" />
    <span class="modal-closebtn">&times;</span>
    <h2>Delete Asset</h2>
    <p>Are you sure you want to delete this asset?</p>
    <div class="button-group">
      <button type="button" class="cancel-button">Cancel</button>
      <button type="submit" class="confirm-button">Confirm</button>
    </div>
  </form>
</div>