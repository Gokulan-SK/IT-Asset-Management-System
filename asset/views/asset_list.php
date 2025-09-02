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
              <option value="in_use" <?= $statusFilter === 'in_use' ? 'selected' : '' ?>>In Use</option>
              <option value="under_maintenance" <?= $statusFilter === 'under_maintenance' ? 'selected' : '' ?>>Under Maintenance</option>
              <option value="retired" <?= $statusFilter === 'retired' ? 'selected' : '' ?>>Retired</option>
              <option value="disposed" <?= $statusFilter === 'disposed' ? 'selected' : '' ?>>Disposed</option>
              <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active</option>
              <option value="expired" <?= $statusFilter === 'expired' ? 'selected' : '' ?>>Expired</option>
            </select>
            <select id="category-filter">
              <option value="">All Categories</option>
              <option value="hardware" <?= $categoryFilter === 'hardware' ? 'selected' : '' ?>>Hardware</option>
              <option value="software" <?= $categoryFilter === 'software' ? 'selected' : '' ?>>Software</option>
              <option value="office_equipment" <?= $categoryFilter === 'office_equipment' ? 'selected' : '' ?>>Office Equipment</option>
              <option value="other" <?= $categoryFilter === 'other' ? 'selected' : '' ?>>Other</option>
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
                <td>
                  <a href="<?= BASE_URL ?>asset/update?id=<?= $asset['asset_id']; ?>" class="btn-link">
                    <button class="edit-button" title="Edit Asset">
                      <img src="<?= BASE_URL ?>public/img/edit-icon.png" alt="Edit" />
                    </button>
                  </a>
                  <button class="delete-button" data-id="<?= $asset['asset_id']; ?>" title="Delete Asset">
                    <img src="<?= BASE_URL ?>public/img/delete-icon.png" alt="Delete" />
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
        <div class="pagination-info">
          <span class="records-info">
            <?php
            if ($totalRecordsCount === 0) {
              echo "0-0 of 0";
            } else {
              $from = (($currentPage - 1) * $limit) + 1;
              $to = min($currentPage * $limit, $totalRecordsCount);
              echo "$from-$to of $totalRecordsCount";
            }
            ?>
          </span>
          <div class="page-size-selector">
            <label for="page-size">Show:</label>
            <select id="page-size" name="limit">
              <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
              <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
              <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
              <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
            </select>
            <span>per page</span>
          </div>
        </div> 
        
        <div class="pagination-controls">
          <div class="page-info">
            Page <?= $currentPage ?> of <?= $totalPages ?>
          </div>
          <div class="pagination-buttons">
            <button 
            class="<?= $currentPage <= 1 ? 'btn disabled' : 'btn-primary' ?>" 
            <?= $currentPage <= 1 ? 'disabled' : '' ?> 
            onclick="window.AssetListManager && window.AssetListManager.changePage(<?= $currentPage - 1 ?>)"
            title="Previous page">
            ← Previous
          </button>
            
            <div class="page-numbers">
              <?php
              // Calculate page range to show
              $start = max(1, $currentPage - 2);
              $end = min($totalPages, $currentPage + 2);
              
              // Show first page if not in range
              if ($start > 1): ?>
                <button 
                  class="btn-page <?= $currentPage == 1 ? 'active' : '' ?>" 
                  onclick="window.AssetListManager && window.AssetListManager.changePage(1)">1</button>
                <?php if ($start > 2): ?>
                  <span class="pagination-ellipsis">...</span>
                <?php endif;
              endif;
              
              // Show page numbers in range
              for ($i = $start; $i <= $end; $i++): ?>
                <button 
                  class="btn-page <?= $currentPage == $i ? 'active' : '' ?>" 
                  onclick="window.AssetListManager && window.AssetListManager.changePage(<?= $i ?>)"><?= $i ?></button>
              <?php endfor;
              
              // Show last page if not in range
              if ($end < $totalPages): 
                if ($end < $totalPages - 1): ?>
                  <span class="pagination-ellipsis">...</span>
                <?php endif; ?>
                <button 
                  class="btn-page <?= $currentPage == $totalPages ? 'active' : '' ?>" 
                  onclick="window.AssetListManager && window.AssetListManager.changePage(<?= $totalPages ?>)"><?= $totalPages ?></button>
              <?php endif; ?>
            </div>
            
            <button 
              class="<?= $currentPage >= $totalPages ? 'btn disabled' : 'btn-primary' ?>" 
              <?= $currentPage >= $totalPages ? 'disabled' : '' ?> 
              onclick="window.AssetListManager && window.AssetListManager.changePage(<?= $currentPage + 1 ?>)"
              title="Next page">
              Next →
            </button>
          </div>
          
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