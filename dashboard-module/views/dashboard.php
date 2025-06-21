<div class="content-frame">
  <div class="content-header">
    <h3>Dashboard</h3>
    <p>
      Welcome back, Admin. Here's a quick overview of your assets.
    </p>
  </div>

  <div class="dashboard-cards">
    <div class="card">
      <div class="card-title">Total Assets</div>
      <div class="card-value">134</div>
      <div class="card-subtext">+12 this month</div>
    </div>
    <div class="card">
      <div class="card-title">Assigned</div>
      <div class="card-value">58</div>
      <div class="card-subtext">-3 from last week</div>
    </div>
    <div class="card">
      <div class="card-title">Available</div>
      <div class="card-value">61</div>
      <div class="card-subtext">+7 updated</div>
    </div>
    <div class="card">
      <div class="card-title">Under Maintenance</div>
      <div class="card-value">15</div>
      <div class="card-subtext">Needs attention</div>
    </div>
  </div>

  <div class="dashboard-actions">
    <a href="<?= BASE_URL ?>asset/add" class="btn-primary">Add New Asset</a>
    <!-- <button class="btn-primary">View Reports</button> -->
  </div>

  <div class="dashboard-table">
    <div class="table-container">
      <div class="table-header">
        <h6>Recent Activity</h6>
      </div>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Asset Name</th>
            <th>Status</th>
            <th>Assigned To</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Lenovo ThinkPad</td>
            <td>Assigned</td>
            <td>John Doe</td>
            <td>2025-06-15</td>
          </tr>
          <tr>
            <td>2</td>
            <td>Dell Monitor</td>
            <td>Available</td>
            <td>-</td>
            <td>2025-06-12</td>
          </tr>
          <tr>
            <td>3</td>
            <td>HP Laptop</td>
            <td>Under Maintenance</td>
            <td>-</td>
            <td>2025-06-10</td>
          </tr>
          <tr>
            <td>4</td>
            <td>Apple MacBook Pro</td>
            <td>Assigned</td>
            <td>Jane Smith</td>
            <td>2025-06-08</td>
          </tr>
          <tr>
            <td>5</td>
            <td>Samsung Galaxy Tab</td>
            <td>Available</td>
            <td>-</td>
            <td>2025-06-05</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>