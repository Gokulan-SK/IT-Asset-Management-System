<header class="global-header">
    <div class="icon-header">
        <img src="<?= BASE_URL ?>public/img/laptop.png" alt="Laptop Icon" />
        <h4>
            <a href="<?= BASE_URL ?>dashboard"> Asset Management System </a>
        </h4>
        <button type="menu" class="menu-symbol">☰</button>
    </div>
    <div class="header-actions">
        <div class="notification-bell">
            <a href="#">
                <img src="<?= BASE_URL ?>public/img/notification-bell.png" alt="Notifications" />
            </a>
        </div>
        <div class="user-info" id="user-info">
            <a href="#">
                <img src="<?= BASE_URL ?>public/img/user-icon.png" alt="User Icon" class="user-icon" />
                <span
                    class="user-name"><?= isset($_SESSION["user"]["firstName"]) ? $_SESSION["user"]["firstName"] : "User"; ?></span>
                <img src="<?= BASE_URL ?>public/img/down-arrow.png" alt="" id="down-arrow" />
            </a>
            <div class="user-dropdown hidden" id="user-dropdown">
                <a href="#">View Profile</a>
                <a href="<?= BASE_URL ?>logout">Logout</a>
            </div>
        </div>
    </div>
</header>