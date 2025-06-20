<?php include BASE_PATH . "views/layout/components/head.php"; ?>
<div class="content-frame">
  <div class="table-container">
    <div class="table-header">
      <div class="table-heading">
        <h3>Asset Allocation History</h3>
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

    <div class="table">
      <table>
        <thead>
          <tr>
            <th>Asset ID</th>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Allocation Date</th>
            <th>Return Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>EMP123</td>
            <td>John Smith</td>
            <td>2025-06-01</td>
            <td>2025-06-30</td>
            <td>Assigned</td>
            <td>
              <button class="btn-delete">Return</button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>EMP456</td>
            <td>Jane Doe</td>
            <td>2025-06-05</td>
            <td>2025-06-23</td>
            <td>Available</td>
            <td>
              <button class="btn-primary">Assign</button>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td>EMP789</td>
            <td>Michael Johnson</td>
            <td>2025-06-10</td>
            <td>2025-06-20</td>
            <td>Under Maintenance</td>
            <td>
              <button class="btn-primary">Assign</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>