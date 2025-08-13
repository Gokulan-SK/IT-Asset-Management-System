<?php
include BASE_PATH . "views/layouts/components/quick-access.php"; ?>

<?php
$ledgers = $ledgers ?? [];
$totalRecordsCount = $totalRecordsCount ?? 0;
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$paginationError = $paginationError ?? null;
$search = $search ?? '';
$statusFilter = $statusFilter ?? '';
$sort = $sort ?? 'ledger_id';
$order = $order ?? 'DESC';
$limit = $limit ?? 10;
?>
<div class="content-frame">
  <div class="table-container">
    <div class="table-header">
      <div class="table-heading">
        <h3>Asset Ledger</h3>
        <span class="record-count">(<?= $totalRecordsCount ?> records found)</span>
      </div>
      <div class="table-actions">
        <div class="search-filter-row">
          <div class="search-bar">
            <input type="text" placeholder="Search by Asset ID, Employee ID, Name..." name="table-search"
              id="table-search" value="<?= htmlspecialchars($search) ?>" />
            <button type="button" id="clear-search" class="clear-btn" title="Clear search">×</button>
          </div>
          <div class="filter-bar">
            <select id="status-filter">
              <option value="">All Status</option>
              <option value="assigned" <?= $statusFilter === 'assigned' ? 'selected' : '' ?>>Currently Assigned</option>
              <option value="available" <?= $statusFilter === 'available' ? 'selected' : '' ?>>Returned</option>
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
            <th class="sortable <?= $sort === 'ledger_id' ? 'sorted-' . strtolower($order) : '' ?>"
              data-sort="ledger_id">
              LEDGER ID
              <span class="sort-indicator">
                <?= $sort === 'ledger_id' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'asset_id' ? 'sorted-' . strtolower($order) : '' ?>" data-sort="asset_id">
              ASSET ID
              <span class="sort-indicator">
                <?= $sort === 'asset_id' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'name' ? 'sorted-' . strtolower($order) : '' ?>" data-sort="name">
              ASSET NAME
              <span class="sort-indicator">
                <?= $sort === 'name' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'emp_id' ? 'sorted-' . strtolower($order) : '' ?>" data-sort="emp_id">
              EMPLOYEE ID
              <span class="sort-indicator">
                <?= $sort === 'emp_id' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'first_name' ? 'sorted-' . strtolower($order) : '' ?>"
              data-sort="first_name">
              EMPLOYEE NAME
              <span class="sort-indicator">
                <?= $sort === 'first_name' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'check_out_date' ? 'sorted-' . strtolower($order) : '' ?>"
              data-sort="check_out_date">
              CHECK-OUT DATE
              <span class="sort-indicator">
                <?= $sort === 'check_out_date' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th class="sortable <?= $sort === 'check_in_date' ? 'sorted-' . strtolower($order) : '' ?>"
              data-sort="check_in_date">
              CHECK-IN DATE
              <span class="sort-indicator">
                <?= $sort === 'check_in_date' ? ($order === 'ASC' ? '▲' : '▼') : '↕' ?>
              </span>
            </th>
            <th>ACTIONS</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($ledgers)): ?>
            <?php foreach ($ledgers as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['ledger_id']) ?></td>
                <td><?= htmlspecialchars($row['asset_id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['emp_id']) ?></td>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['check_out_date']) ?></td>
                <td><?= $row['check_in_date'] ? htmlspecialchars($row['check_in_date']) : '-' ?></td>
                <td>
                  <?php if ($row['check_in_date'] === null): ?>
                    <a href="<?= BASE_URL ?>asset-ledger/check-in?ledger_id=<?= urlencode($row['ledger_id']) ?>"
                      class="btn-link">
                      <button class="check-in-button" title="Check-in Asset">
                        Check-in
                      </button>
                    </a>
                  <?php else: ?>
                    <span class="status-checked-in">Checked-in</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" style="text-align:center;">
                <?php if (!empty($search) || !empty($statusFilter)): ?>
                  No ledger records found matching your criteria. <button type="button" id="reset-filters-inline"
                    class="reset-link">Clear filters</button>
                <?php else: ?>
                  No asset ledger records found.
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
          <button class="<?= $currentPage <= 1 ? 'btn disabled' : 'btn-primary' ?>" <?php echo ($currentPage <= 1 ? 'disabled' : '') ?> onclick="window.AssetLedgerManager.changePage(<?= $currentPage - 1 ?>)">
            ← Previous
          </button>
          <button class="<?= $currentPage >= $totalPages ? 'btn disabled' : 'btn-primary ' ?>" <?= $currentPage >= $totalPages ? 'disabled' : '' ?> onclick="window.AssetLedgerManager.changePage(<?= $currentPage + 1 ?>)">
            Next →
          </button>
        </div>
      </div>
    </div>
  </div>
</div>