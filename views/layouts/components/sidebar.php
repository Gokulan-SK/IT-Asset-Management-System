<aside class="sidebar">
    <nav class="sidebar-menu">
        <h3>Menu</h3>
        <ul class="menu-list">
            <li>
                <a href="<?= BASE_URL ?>dashboard">Dashboard</a>
            </li>
            <li class="has-submenu">
                <a href="#">Employee Management ▾</a>
                <ul class="submenu hidden">
                    <li>
                        <a href="<?= BASE_URL ?>employee/add">Add Employee</a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>employee/view">View Employees</a>
                    </li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#">Asset Management ▾</a>
                <ul class="submenu hidden">
                    <li>
                        <a href="<?= BASE_URL ?>asset/add">Add Asset</a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>asset/view">View Assets</a>
                    </li>

                </ul>
            </li>
            <li class="has-submenu">
                <a href="#">Asset Ledger ▾</a>
                <ul class="submenu hidden">
                    <li>
                        <a href="<?= BASE_URL ?>asset-ledger/check-out">Check-Out Asset</a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>asset-ledger/view">Asset History</a>
                    </li>
            </li>
        </ul>
    </nav>
</aside>