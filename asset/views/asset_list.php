<?php
include BASE_PATH . "views/layouts/components/quick-access.php"; ?>

<?php
$assets = $assets ?? [];
$totalRecordsCount = $totalRecordsCount ?? 0;
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$paginationError = $paginationError ?? null;
$search = $search ?? '';
$statusFilter = $statusFilter ?? '';
$categoryFilter = $categoryFilter ?? '';
$sort = $sort ?? 'asset_id';
$order = $order ?? 'ASC';
$limit = $limit ?? 10;
?>
<div class="content-frame">
  <div class="table-container">
    <div class="table-header">
      <div class="table-heading">
        <h3>Assets</h3>
        <span class="record-count">(<?= $totalRecordsCount ?> records found)</span>
      </div>
      <div class="table-actions">
        <div class="search-filter-row">
          <div class="search-bar">
            <input type="text" placeholder="Search assets..." name="table-search" id="table-search"
              value="<?= htmlspecialchars($search) ?>" />
            <button type="button" id="clear-search" class="clear-btn" title="Clear search">×</button>
          </div>
          <div class="filter-bar">
            <select id="status-filter">
              <option value="">All Statuses</option>
              <option value="available" <?= $statusFilter === 'available' ? 'selected' : '' ?>>Available</option>
              <option value="assigned" <?= $statusFilter === 'assigned' ? 'selected' : '' ?>>Assigned</option>
              <option value="under-maintenance" <?= $statusFilter === 'under-maintenance' ? 'selected' : '' ?>>Under
                Maintenance</option>
              <option value="disposed" <?= $statusFilter === 'disposed' ? 'selected' : '' ?>>Disposed</option>
              <option value="new" <?= $statusFilter === 'new' ? 'selected' : '' ?>>New</option>
              <option value="good" <?= $statusFilter === 'good' ? 'selected' : '' ?>>Good</option>
              <option value="repair-needed" <?= $statusFilter === 'repair-needed' ? 'selected' : '' ?>>Repair Needed
              </option>
              <option value="damaged" <?= $statusFilter === 'damaged' ? 'selected' : '' ?>>Damaged</option>
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
            <th class="sortable <?= $sort === 'asset_id' ? 'sorted-' . strtolower($order) : '' ?>" data-sort="asset_id">
              ASSET ID
              <span class="sort-indicator">
                <?= $sort === 'asset_id' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'asset_name' ? 'sorted-' . strtolower($order) : '' ?>"
              data-sort="asset_name">
              ASSET NAME
              <span class="sort-indicator">
                <?= $sort === 'asset_name' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'category' ? 'sorted-' . strtolower($order) : '' ?>" data-sort="category">
              CATEGORY
              <span class="sort-indicator">
                <?= $sort === 'category' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'subcategory' ? 'sorted-' . strtolower($order) : '' ?>"
              data-sort="subcategory">
              SUBCATEGORY
              <span class="sort-indicator">
                <?= $sort === 'subcategory' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'asset_status' ? 'sorted-' . strtolower($order) : '' ?>"
              data-sort="asset_status">
              STATUS
              <span class="sort-indicator">
                <?= $sort === 'asset_status' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'purchase_date' ? 'sorted-' . strtolower($order) : '' ?>"
              data-sort="purchase_date">
              PURCHASE DATE
              <span class="sort-indicator">
                <?= $sort === 'purchase_date' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th>ASSIGNED TO</th>
            <th>ACTIONS</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($assets)): ?>
            <?php foreach ($assets as $asset): ?>
              <tr>
                <td><?= htmlspecialchars($asset["asset_id"]); ?></td>
                <td><?= htmlspecialchars($asset["asset_name"]); ?></td>
                <td><?= htmlspecialchars($asset["category"]); ?></td>
                <td><?= htmlspecialchars($asset["subcategory"] ?? "-"); ?></td>
                <td><?= htmlspecialchars($asset["asset_status"]); ?></td>
                <td><?= htmlspecialchars($asset["purchase_date"] ?? "-"); ?></td>
                <td><?= htmlspecialchars($asset["employee_name"] ?? "Not Assigned") ?></td>
                <td class="action-buttons">
                  <a href="<?= BASE_URL ?>asset/update?id=<?= $asset['asset_id']; ?>" class="edit-btn" title="Edit Asset">
                    <img src="<?= BASE_URL ?>public/img/edit-icon.png" alt="Edit" width="16" height="16">
                  </a>
                  <button class="delete-btn" data-id="<?= $asset['asset_id']; ?>" title="Delete Asset">
                    <img src="<?= BASE_URL ?>public/img/delete-icon.png" alt="Delete" width="16" height="16">
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" style="text-align:center;">
                <?php if (!empty($search) || !empty($statusFilter) || !empty($categoryFilter)): ?>
                  No assets found matching your criteria. <button type="button" id="reset-filters-inline"
                    class="reset-link">Clear filters</button>
                <?php else: ?>
                  No asset records found.
                <?php endif; ?>
              </td>
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
          <button class="<?= $currentPage <= 1 ? 'btn disabled' : 'btn-primary' ?>" <?php echo ($currentPage <= 1 ? 'disabled' : '') ?> onclick="window.AssetListManager.changePage(<?= $currentPage - 1 ?>)">
            ← Previous
          </button>
          <button class="<?= $currentPage >= $totalPages ? 'btn disabled' : 'btn-primary ' ?>" <?= $currentPage >= $totalPages ? 'disabled' : '' ?> onclick="window.AssetListManager.changePage(<?= $currentPage + 1 ?>)">
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
    <h2>Delete Asset</h2>
    <p>Are you sure you want to delete this asset?</p>
    <div class="button-group">
      <button type="button" class="cancel-button">Cancel</button>
      <button type="submit" class="confirm-button">Confirm</button>
    </div>
  </form>
</div>