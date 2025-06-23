<aside class="sidebar">
    <div class="sidebar-logo logo-container">
        <img src="<?php echo BASE_URL; ?>/assets/img/logoPutihPanjang.png" alt="OnVerse Logo">
    </div>
    <nav class="sidebar-nav">
        <a href="<?php echo BASE_URL; ?>/index.php"
            class="nav-item <?php echo ($active_page == 'dashboard') ? 'active' : ''; ?>">
            <i class="fa-solid fa-table-columns"></i>
            <span>Dashboard</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/manage_user.php"
            class="nav-item <?php echo ($active_page == 'users') ? 'active' : ''; ?>">
            <i class="fa-solid fa-users-gear"></i>
            <span>Manage Users</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/verify_creator.php"
            class="nav-item <?php echo ($active_page == 'verify') ? 'active' : ''; ?>">
            <i class="fa-solid fa-user-check"></i>
            <span>Verify Creators</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/manage_content.php"
            class="nav-item <?php echo ($active_page == 'content') ? 'active' : ''; ?>">
            <i class="fa-solid fa-file-pen"></i>
            <span>Manage Contents</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/manage_community.php"
            class="nav-item <?php echo ($active_page == 'community') ? 'active' : ''; ?>">
            <i class="fa-solid fa-comments"></i>
            <span>Manage Community</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/manage_reports.php"
            class="nav-item <?php echo ($active_page == 'reports') ? 'active' : ''; ?>">
            <i class="fa-solid fa-flag"></i>
            <span>Manage Reports</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/transaction.php"
            class="nav-item <?php echo ($active_page == 'transactions') ? 'active' : ''; ?>">
            <i class="fa-solid fa-receipt"></i>
            <span>Transactions</span>
        </a>
    </nav>
</aside>