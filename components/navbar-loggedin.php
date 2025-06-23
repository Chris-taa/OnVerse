<?php
// navbar.php

@session_start();

// If not logged in, redirect to login.html
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
?>

<nav class="navbar">
    <a href="../HTML/landing.php">
        <img src="../IMG/judul.png" alt="Judul" class="navbar-logo" id="judul"/>
    </a>
    <div class="navbar-search">
        <form action="../components/search.php" method="get">
            <input type="text" name="q" placeholder="Cari..." />
            <button type="submit"><i data-feather="search"></i></button>
        </form>
    </div>

    <div class="navbar-extra">
        <a href="#toggle" class="button" id="toggle-button">
            <i data-feather="toggle-left"></i>
        </a>

        <div class="user-profile">
            <a href="../HTML/ProfilePage.php" class="profile-link">
                <img src="../IMG/cover.jpg" alt="Profile" class="profile-pic" />
                <span class="username" id="username-display">
                    <?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest'; ?>
                </span>
            </a>
        </div>

        <a href="#" id="hambuger-menu">
            <i data-feather="menu" class="humbuger"></i>
        </a>
    </div>
</nav>

<style>
    .navbar {
        height: 80px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .navbar .navbar-search {
        flex-grow: 1;
        margin-left: 45px;
        margin-right: 20px;
        max-width: 400px;
    }

    .navbar .navbar-search form {
        display: flex;
        align-items: center;
        width: 100%;
        background-color: #fff;
        border: 1px solid #c5c5c5;
        border-radius: 50px;
        padding: 6px 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .navbar .navbar-search input {
        flex-grow: 1;
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.95rem;
        color: #333;
        padding-left: 10px;
        order: 2;
    }

    .navbar .navbar-search input::placeholder {
        color: #888;
    }

    .navbar .navbar-search button {
        border: none;
        background: transparent;
        padding: 0;
        margin: 0;
        cursor: pointer;
        color: #555;
        display: flex;
        align-items: center;
        order: 1;
    }

    .navbar .navbar-search button svg {
        width: 20px;
        height: 20px;
    }

    .navbar-search input {
        padding: 6px 10px;
        font-size: 14px;
    }

    .navbar-search button {
        background: none;
        border: none;
        cursor: pointer;
    }

    .user-profile {
        display: flex;
        align-items: center;
    }

    .profile-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit;
    }

    .profile-pic {
        margin-right: 10px;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
    }

    .username {
        font-weight: 600;
        font-size: 1rem;
        color: black;
    }

    .navbar-extra {
        display: flex;
        align-items: center;
    }
</style>