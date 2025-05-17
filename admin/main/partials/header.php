<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<header>
    <div class="navigation">
        <div class="logo">
            <img src="<?= BASE_URL ?>assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
            <h1>UBISH</h1>
        </div>
        <form method="POST">
            <nav>
                <ul>
                    <li>
                        <button class="logout" style="cursor: pointer;" name="logout">Log Out</button>
                    </li>
                </ul>
            </nav>
        </form>
    </div>
    <hr>
</header>
        
