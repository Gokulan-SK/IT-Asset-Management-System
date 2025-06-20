<?php include BASE_PATH . "views/layout/components/quick-access.php"; ?>
<div class="content-frame">
  <div class="table-container">
    <div class="table-header">
      <div class="table-heading">
        <h3>Assets</h3>
      </div>
      <div class="table-actions">
        <div class="search-bar">
          <input type="text" placeholder="Search by name, category, status..." name="table-search" id="table-search" />
        </div>
        <div class="filter-bar">
          <select id="status-filter">
            <option value="all">All</option>
            <option value="available">Available</option>
            <option value="assigned">Assigned</option>
            <option value="under-maintenance">
              Under Maintenance
            </option>
            <option value="disposed">Disposed</option>
            <option value="new">New</option>
            <option value="good">Good</option>
            <option value="repair-needed">Repair Needed</option>
            <option value="damaged">Damaged</option>
          </select>
        </div>
      </div>
    </div>
    <div class="table">
      <table>
        <thead>
          <tr>
            <th id="table-primary-id">Asset ID</th>
            <th>Asset Name</th>
            <th>Asset Category</th>
            <!-- <th>Asset Condition</th> -->
            <th>Status</th>
            <th>Assigned To</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Dell XPS 13</td>
            <td>Laptop</td>
            <!-- <td>Good</td> -->
            <td>Assigned</td>
            <td>John Doe</td>
            <td>
              <a href="../views/asset/add-asset.php"><button class="edit-button">Edit</button></a>
              <button class="delete-button">Delete</button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>HP EliteBook 840</td>
            <td>Laptop</td>
            <!-- <td>New</td> -->
            <td>Available</td>
            <td>-</td>
            <td>
              <a href="../views/asset/add-asset.php"><button class="edit-button">Edit</button></a>
              <button class="delete-button">Delete</button>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td>Apple MacBook Pro</td>
            <td>Laptop</td>
            <!-- <td>Repair Needed</td> -->
            <td>Available</td>
            <td>-</td>
            <td>
              <a href="../views/asset/add-asset.php"><button class="edit-button">Edit</button></a>
              <button class="delete-button">Delete</button>
            </td>
          </tr>
          <tr>
            <td>4</td>
            <td>Samsung Galaxy Tab S7</td>
            <td>Tablet</td>
            <!-- <td>Good</td> -->
            <td>Assigned</td>
            <td>Jane Smith</td>
            <td>
              <a href="../views/asset/add-asset.php"><button class="edit-button">Edit</button></a>
              <button class="delete-button">Delete</button>
            </td>
          </tr>
          <tr>
            <td>5</td>
            <td>Lenovo ThinkPad X1 Carbon</td>
            <td>Laptop</td>
            <!-- <td>New</td> -->
            <td>Available</td>
            <td>-</td>
            <td>
              <a href="../views/asset/add-asset.php"><button class="edit-button">Edit</button></a>
              <button class="delete-button">Delete</button>
            </td>
          </tr>
          <tr>
            <td>6</td>
            <td>Asus ZenBook 14</td>
            <td>Laptop</td>
            <!-- <td>Good</td> -->
            <td>Assigned</td>
            <td>Michael Brown</td>
            <td>
              <a href="../views/asset/add-asset.php"><button class="edit-button">Edit</button></a>
              <button class="delete-button">Delete</button>
            </td>
          </tr>
          <tr>
            <td>7</td>
            <td>Microsoft Surface Pro 7</td>
            <td>Tablet</td>
            <!-- <td>New</td> -->
            <td>Available</td>
            <td>-</td>
            <td>
              <a href="../views/asset/add-asset.php"><button class="edit-button">Edit</button></a>
              <button class="delete-button">Delete</button>
            </td>
          </tr>
          <tr>
            <td>8</td>
            <td>Google Pixelbook Go</td>
            <td>Laptop</td>
            <!-- <td>Good</td> -->
            <td>Under Maintenance</td>
            <td>-</td>
            <td>
              <a href="../views/asset/add-asset.php"><button class="edit-button">Edit</button></a>
              <button class="delete-button">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="table-footer">
      <div class="pagination">
        <span>Rows per page: 10</span>
        <span>1-10 of 87</span>
        <div class="pagination-buttons">
          <button class="btn disabled" disabled>← Previous</button>
          <button class="btn-primary">Next →</button>
        </div>
      </div>
    </div>
  </div>
</div>