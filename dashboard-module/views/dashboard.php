<div class="content-frame">
  <div class="content-header">
    <h3>Dashboard</h3>
  </div>



  <!-- Key Metrics Cards -->
  <div class="metrics-grid">
    <div class="metric-card primary">
      <div class="metric-icon">
        <i class="fas fa-boxes"></i>
      </div>
      <div class="metric-content">
        <div class="metric-value"><?= number_format($assetSummary['total_assets'] ?? 0) ?></div>
        <div class="metric-label">Total Assets</div>
        <div class="metric-subtext">Value: $<?= number_format($assetValueSummary['total_value'] ?? 0, 2) ?></div>
      </div>
      <div class="metric-trend">
        <span class="trend-indicator positive"><i class="fas fa-arrow-up"></i></span>
      </div>
    </div>

    <div class="metric-card success">
      <div class="metric-icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="metric-content">
        <div class="metric-value"><?= number_format($assetSummary['available_assets'] ?? 0) ?></div>
        <div class="metric-label">Available Assets</div>
        <div class="metric-subtext">
          <?= round((($assetSummary['available_assets'] ?? 0) / max($assetSummary['total_assets'], 1)) * 100, 1) ?>% of
          total
        </div>
      </div>
      <div class="metric-trend">
        <span class="trend-indicator neutral"><i class="fas fa-arrow-right"></i></span>
      </div>
    </div>

    <div class="metric-card warning">
      <div class="metric-icon">
        <i class="fas fa-cogs"></i>
      </div>
      <div class="metric-content">
        <div class="metric-value"><?= number_format($assetSummary['in_use_assets'] ?? 0) ?></div>
        <div class="metric-label">Assets In Use</div>
        <div class="metric-subtext"><?= number_format($ledgerSummary['active_allocations'] ?? 0) ?> active allocations
        </div>
      </div>
      <div class="metric-trend">
        <span class="trend-indicator positive"><i class="fas fa-arrow-up"></i></span>
      </div>
    </div>

    <div class="metric-card danger">
      <div class="metric-icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <div class="metric-content">
        <div class="metric-value"><?= number_format($assetSummary['maintenance_assets'] ?? 0) ?></div>
        <div class="metric-label">Under Maintenance</div>
        <div class="metric-subtext">Needs attention</div>
      </div>
      <div class="metric-trend">
        <span class="trend-indicator negative"><i class="fas fa-arrow-down"></i></span>
      </div>
    </div>

    <div class="metric-card info">
      <div class="metric-icon">
        <i class="fas fa-users"></i>
      </div>
      <div class="metric-content">
        <div class="metric-value"><?= number_format($employeeSummary['total_employees'] ?? 0) ?></div>
        <div class="metric-label">Total Employees</div>
        <div class="metric-subtext"><?= number_format($employeeSummary['admin_employees'] ?? 0) ?> administrators</div>
      </div>
      <div class="metric-trend">
        <span class="trend-indicator positive"><i class="fas fa-arrow-up"></i></span>
      </div>
    </div>

    <div class="metric-card accent">
      <div class="metric-icon">
        <i class="fas fa-chart-line"></i>
      </div>
      <div class="metric-content">
        <div class="metric-value"><?= number_format($utilizationRate['utilization_percentage'] ?? 0, 1) ?>%</div>
        <div class="metric-label">Utilization Rate</div>
        <div class="metric-subtext">Asset efficiency</div>
      </div>
      <div class="metric-trend">
        <span
          class="trend-indicator <?= ($utilizationRate['utilization_percentage'] ?? 0) > 70 ? 'positive' : 'neutral' ?>">
          <?= ($utilizationRate['utilization_percentage'] ?? 0) > 70 ? '<i class="fas fa-arrow-up"></i>' : '<i class="fas fa-arrow-right"></i>' ?>
        </span>
      </div>
    </div>
  </div>

  <?php
  $hasData = ($assetSummary['total_assets'] ?? 0) > 0 || ($employeeSummary['total_employees'] ?? 0) > 0;
  if ($hasData): ?>

    <!-- Analytics Dashboard -->
    <div class="analytics-dashboard">
      <!-- Primary Charts Row -->
      <div class="charts-row primary-charts">
        <div class="chart-container large">
          <div class="chart-header">
            <h4><i class="fas fa-chart-pie"></i> Asset Status Distribution</h4>
          </div>
          <div class="chart-wrapper">
            <canvas id="assetStatusChart"></canvas>
          </div>
          <div class="chart-stats">
            <div class="stat-item">
              <span class="stat-label">Most Common:</span>
              <span class="stat-value" id="mostCommonStatus">-</span>
            </div>
            <div class="stat-item">
              <span class="stat-label">Total Value:</span>
              <span class="stat-value">$<?= number_format($assetValueSummary['total_value'] ?? 0, 2) ?></span>
            </div>
          </div>
        </div>

        <div class="chart-container large">
          <div class="chart-header">
            <h4><i class="fas fa-tags"></i> Assets by Category</h4>
          </div>
          <div class="chart-wrapper">
            <canvas id="categoryChart"></canvas>
          </div>
          <div class="chart-stats">
            <div class="stat-item">
              <span class="stat-label">Categories:</span>
              <span class="stat-value"><?= count($assetsByCategory) ?></span>
            </div>
            <div class="stat-item">
              <span class="stat-label">Most Popular:</span>
              <span
                class="stat-value"><?= !empty($assetsByCategory) ? htmlspecialchars($assetsByCategory[0]['category']) : 'N/A' ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Secondary Charts Row -->
      <div class="charts-row secondary-charts">
        <div class="chart-container medium">
          <div class="chart-header">
            <h4><i class="fas fa-user-friends"></i> Employee Distribution</h4>
          </div>
          <div class="chart-wrapper">
            <canvas id="employeeChart"></canvas>
          </div>
        </div>

        <div class="chart-container medium">
          <div class="chart-header">
            <h4><i class="fas fa-chart-line"></i> Monthly Allocation Trend</h4>
          </div>
          <div class="chart-wrapper">
            <canvas id="trendChart"></canvas>
          </div>
        </div>
      </div>
    </div>



    <!-- Smart Insights Section -->
    <div class="insights-section">
      <div class="insights-card">
        <div class="insights-header">
          <h4><i class="fas fa-lightbulb"></i> Smart Insights</h4>
        </div>
        <div class="insights-content">
          <?php
          $insights = [];

          // Generate utilization insight
          $utilization = $utilizationRate['utilization_percentage'] ?? 0;
          if ($utilization > 80) {
            $insights[] = ['type' => 'warning', 'text' => "High asset utilization at {$utilization}% - consider expanding your asset inventory."];
          } elseif ($utilization < 30) {
            $insights[] = ['type' => 'info', 'text' => "Low asset utilization at {$utilization}% - you may have underutilized resources."];
          } else {
            $insights[] = ['type' => 'success', 'text' => "Good asset utilization at {$utilization}% - your resources are well-balanced."];
          }

          // Generate category insight
          if (!empty($topCategoriesByUsage)) {
            $topCategory = $topCategoriesByUsage[0];
            $insights[] = ['type' => 'info', 'text' => "Most popular category: {$topCategory['category']} with {$topCategory['total_allocations']} allocations."];
          }

          // Generate warranty insight
          if (!empty($warrantyExpiring)) {
            $count = count($warrantyExpiring);
            $insights[] = ['type' => 'warning', 'text' => "{$count} assets have warranties expiring in the next 30 days."];
          }

          // Generate aging assets insight
          if (!empty($agingAssets)) {
            $count = count($agingAssets);
            $insights[] = ['type' => 'info', 'text' => "{$count} assets are older than 3 years and may need replacement planning."];
          }

          // Generate trend insight
          if (!empty($monthlyTrend) && count($monthlyTrend) >= 2) {
            $latest = end($monthlyTrend);
            $previous = prev($monthlyTrend);
            $change = $latest['allocations'] - $previous['allocations'];
            $direction = $change > 0 ? 'increased' : ($change < 0 ? 'decreased' : 'remained stable');
            $percentage = $previous['allocations'] > 0 ? abs(round(($change / $previous['allocations']) * 100)) : 0;

            if ($change != 0) {
              $insights[] = ['type' => 'info', 'text' => "Asset allocations {$direction} by {$percentage}% this month compared to last month."];
            }
          }
          ?>

          <?php if (empty($insights)): ?>
            <div class="insight-item info">
              <span class="insight-icon"><i class="fas fa-chart-bar"></i></span>
              <span class="insight-text">Add more assets and employee data to see personalized insights.</span>
            </div>
          <?php else: ?>
            <?php foreach ($insights as $insight): ?>
              <div class="insight-item <?= $insight['type'] ?>">
                <span class="insight-icon">
                  <?php if ($insight['type'] === 'warning'): ?>
                    <i class="fas fa-exclamation-triangle"></i>
                  <?php elseif ($insight['type'] === 'success'): ?>
                    <i class="fas fa-check-circle"></i>
                  <?php else: ?>
                    <i class="fas fa-info-circle"></i>
                  <?php endif; ?>
                </span>
                <span class="insight-text"><?= $insight['text'] ?></span>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php else: ?>
    <!-- Empty State -->
    <div class="empty-state">
      <div class="empty-state-content">
        <h4><i class="fas fa-chart-area"></i> Welcome to Your Dashboard!</h4>
        <p>To see comprehensive analytics and insights, start by adding some data:</p>
        <div class="empty-state-actions">
          <a href="<?= BASE_URL ?>asset/add" class="btn-primary">Add Your First Asset</a>
          <a href="<?= BASE_URL ?>employee/add" class="btn-primary">Add Your First Employee</a>
        </div>
        <small>Once you have assets and employees, you can start tracking allocations in the Asset Ledger.</small>
      </div>
    </div>
  <?php endif; ?>



  <!-- Data Analytics Tables -->
  <div class="analytics-tables">
    <!-- Warranty Expiration Alert -->
    <div class="analytics-table priority">
      <div class="table-header">
        <div class="table-title">
          <h5><i class="fas fa-exclamation-triangle"></i> Upcoming Warranty Expirations (90 Days)</h5>
          <span class="table-count"><?= count($warrantyExpiring) ?> assets</span>
        </div>
        <div class="table-actions">
          <button class="btn-secondary table-export" data-table="warranty-table" data-filename="warranty_expiring">
            <i class="fas fa-file-excel"></i> Export Excel
          </button>
          <a href="<?= BASE_URL ?>asset/view" class="btn-secondary">View All</a>
        </div>
      </div>
      <div class="table-wrapper">
        <table id="warranty-table" class="data-table">
          <thead>
            <tr>
              <th>Asset Name</th>
              <th>Category</th>
              <th>Purchase Date</th>
              <th>Warranty Period</th>
              <th>Days Remaining</th>
              <th>Value</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($warrantyExpiring)): ?>
              <tr>
                <td colspan="7" class="no-data"><i class="fas fa-check-circle"></i> No warranties expiring in the next 90
                  days</td>
              </tr>
            <?php else: ?>
              <?php foreach ($warrantyExpiring as $asset): ?>
                <tr
                  class="<?= $asset['days_remaining'] <= 30 ? 'urgent' : ($asset['days_remaining'] <= 60 ? 'warning' : 'normal') ?>">
                  <td class="asset-name"><?= htmlspecialchars($asset['asset_name']) ?></td>
                  <td><?= htmlspecialchars($asset['category']) ?></td>
                  <td><?= date('M d, Y', strtotime($asset['purchase_date'])) ?></td>
                  <td><?= $asset['warranty_period'] ?> months</td>
                  <td>
                    <span
                      class="days-badge <?= $asset['days_remaining'] <= 30 ? 'urgent' : ($asset['days_remaining'] <= 60 ? 'warning' : 'normal') ?>">
                      <?= $asset['days_remaining'] ?> days
                    </span>
                  </td>
                  <td class="value-cell">$<?= number_format($asset['unit_price'] ?? 0, 2) ?></td>
                  <td>
                    <span class="status-badge available">Active</span>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Aging Assets Report -->
    <div class="analytics-table secondary">
      <div class="table-header">
        <div class="table-title">
          <h5><i class="fas fa-calendar-alt"></i> Aging Assets Report (3+ Years)</h5>
          <span class="table-count"><?= count($agingAssets) ?> assets</span>
        </div>
        <div class="table-actions">
          <button class="btn-secondary table-export" data-table="aging-table" data-filename="aging_assets">
            <i class="fas fa-file-excel"></i> Export Excel
          </button>
          <a href="<?= BASE_URL ?>asset/view" class="btn-secondary">View All</a>
        </div>
      </div>
      <div class="table-wrapper">
        <table id="aging-table" class="data-table">
          <thead>
            <tr>
              <th>Asset Name</th>
              <th>Category</th>
              <th>Purchase Date</th>
              <th>Age (Years)</th>
              <th>Status</th>
              <th>Value</th>
              <th>Replacement Priority</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($agingAssets)): ?>
              <tr>
                <td colspan="7" class="no-data"><i class="fas fa-check-circle"></i> No assets older than 3 years</td>
              </tr>
            <?php else: ?>
              <?php foreach ($agingAssets as $index => $asset): ?>
                <tr>
                  <td class="asset-name"><?= htmlspecialchars($asset['asset_name']) ?></td>
                  <td><?= htmlspecialchars($asset['category']) ?></td>
                  <td><?= date('M d, Y', strtotime($asset['purchase_date'])) ?></td>
                  <td>
                    <span class="age-badge <?= $asset['age_years'] >= 5 ? 'critical' : 'aging' ?>">
                      <?= $asset['age_years'] ?> years
                    </span>
                  </td>
                  <td>
                    <span
                      class="status-badge <?= strtolower(str_replace(['-', ' '], ['_', '_'], $asset['asset_status'])) ?>">
                      <?= ucfirst(str_replace('-', ' ', $asset['asset_status'])) ?>
                    </span>
                  </td>
                  <td class="value-cell">$<?= number_format($asset['unit_price'] ?? 0, 2) ?></td>
                  <td>
                    <span class="priority-badge <?= $asset['age_years'] >= 5 ? 'high' : 'medium' ?>">
                      <?= $asset['age_years'] >= 5 ? 'High' : 'Medium' ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Asset Utilization Analytics -->
    <div class="analytics-table tertiary">
      <div class="table-header">
        <div class="table-title">
          <h5><i class="fas fa-chart-line"></i> Asset Utilization Analytics</h5>
          <span class="table-count">Top <?= min(count($assetUtilization), 10) ?> assets</span>
        </div>
        <div class="table-actions">
          <button class="btn-secondary table-export" data-table="utilization-table" data-filename="asset_utilization">
            <i class="fas fa-file-excel"></i> Export Excel
          </button>
          <a href="<?= BASE_URL ?>asset/view" class="btn-secondary">View All</a>
        </div>
      </div>
      <div class="table-wrapper">
        <table id="utilization-table" class="data-table">
          <thead>
            <tr>
              <th>Rank</th>
              <th>Asset Name</th>
              <th>Category</th>
              <th>Total Allocations</th>
              <th>Last Allocated</th>
              <th>Utilization Score</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($assetUtilization)): ?>
              <tr>
                <td colspan="6" class="no-data">No utilization data available</td>
              </tr>
            <?php else: ?>
              <?php foreach (array_slice($assetUtilization, 0, 10) as $index => $asset): ?>
                <tr>
                  <td class="rank-cell">
                    <span class="rank-badge rank-<?= $index < 3 ? ($index + 1) : 'other' ?>">#<?= $index + 1 ?></span>
                  </td>
                  <td class="asset-name"><?= htmlspecialchars($asset['asset_name']) ?></td>
                  <td><?= htmlspecialchars($asset['category']) ?></td>
                  <td>
                    <span class="allocation-count"><?= $asset['allocation_count'] ?> times</span>
                  </td>
                  <td>
                    <?= $asset['last_allocated'] ? date('M d, Y', strtotime($asset['last_allocated'])) : 'Never' ?>
                  </td>
                  <td>
                    <div class="utilization-score">
                      <div class="score-bar">
                        <div class="score-fill"
                          style="width: <?= min(($asset['allocation_count'] / max(array_column($assetUtilization, 'allocation_count'))) * 100, 100) ?>%">
                        </div>
                      </div>
                      <span class="score-text"><?= $asset['allocation_count'] ?></span>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>


</div>

<!-- Chart Data for JavaScript -->
<script>
  window.dashboardData = {
    assetStatus: {
      labels: ['Available', 'In Use', 'Maintenance', 'Retired'],
      data: [
        <?= $assetSummary['available_assets'] ?? 0 ?>,
        <?= $assetSummary['in_use_assets'] ?? 0 ?>,
        <?= $assetSummary['maintenance_assets'] ?? 0 ?>,
        <?= $assetSummary['retired_assets'] ?? 0 ?>
      ]
    },
    assetCategories: {
      labels: <?= json_encode(array_column($assetsByCategory, 'category')) ?>,
      data: <?= json_encode(array_column($assetsByCategory, 'count')) ?>
    },
    employeeRoles: {
      labels: <?= json_encode(array_column($employeesByDesignation, 'designation')) ?>,
      data: <?= json_encode(array_column($employeesByDesignation, 'count')) ?>
    },
    monthlyTrend: {
      labels: <?= json_encode(array_map(function ($item) {
        return date('M Y', strtotime($item['month'] . '-01'));
      }, $monthlyTrend)) ?>,
      data: <?= json_encode(array_column($monthlyTrend, 'allocations')) ?>
    }
  };
</script>