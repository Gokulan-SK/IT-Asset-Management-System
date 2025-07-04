<?php
include BASE_PATH . "views/layouts/components/quick-access.php"; ?>

<?php
$ledgers = $ledgers ?? [];
$totalRecordsCount = $totalRecordsCount ?? 0;
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$paginationError = $paginationError ?? null;
?>
<div class="content-frame">
  <div class="table-container">
    <div class="table-header">
      <div class="table-heading">
        <h3>Asset Ledger</h3>
      </div>
      <div class="table-actions">
        <div class="search-bar">
          <input type="text" placeholder="Search by Asset ID, Employee ID..." name="table-search"
            id="allocation-search" />
        </div>
        <div class="filter-bar">
          <select id="status-filter">
            <option value="all">All</option>
            <option value="assigned">Assigned</option>
            <option value="available">Available</option>
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
            <th>Ledger ID</th>
            <th>Asset ID</th>
            <th>Asset Name</th>
            <th>Employee ID</th>
            <th>Employee First Name</th>
            <th>Check-out Date</th>
            <th>Check-in Date</th>
            <th>Action</th>
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
                <td><?= htmlspecialchars($row['first_name']) ?></td>
                <td><?= htmlspecialchars($row['check_out_date']) ?></td>
                <td><?= $row['check_in_date'] ? htmlspecialchars($row['check_in_date']) : '-' ?></td>
                <td>
                  <?php if ($row['check_in_date'] === null): ?>
                    <a href="<?= BASE_URL ?>asset-ledger/check-in?ledger_id=<?= urlencode($row['ledger_id']) ?>"
                      class="btn-delete">Check-in</a>
                  <?php else: ?>
                    <span class="btn-disabled">Checked-in</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" style="text-align:center;">No asset ledger records found.</td>
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
            onclick="window.location.href='<?= BASE_URL ?>asset-ledger/view?page=<?= $currentPage - 1 ?>'">←
            Previous</button>
          <button class="<?= $currentPage >= $totalPages ? 'btn disabled' : 'btn-primary ' ?>" <?= $currentPage >= $totalPages ? 'disabled' : '' ?>
            onclick="window.location.href='<?= BASE_URL ?>asset-ledger/view?page=<?= $currentPage + 1 ?>'">Next
            →</button>
        </div>
      </div>
    </div>
  </div>
</div>