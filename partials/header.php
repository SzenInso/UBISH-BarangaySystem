<!-- reusable header -->
<header class="main-header">
    <div class="header-container">
        <div class="logo">
            <img src="<?= BASE_URL ?>assets/img/GreenwaterLogo.jpg" alt="Greenwater Village Logo" />
            <h1><span>Greenwater</span> <span>Village</span></h1>
        </div>
        <button class="hamburger" id="hamburger">&#9776;</button> <!-- Hamburger Icon -->

        <nav class="nav" id="nav">
            <ul class="nav-links">
                <li><a href="<?= BASE_URL ?>index.php">Home</a></li>
                <li><a href="<?= BASE_URL ?>about.php">About Us</a></li>
                <li class="dropdown">
                    <a href="<?= BASE_URL ?>pages/services/service.php">Services</a>
                    <ul class="dropdown-content">
                        <li><a href="<?= BASE_URL ?>pages/services/residencyCert.php">Certificate of Residency</a></li>
                        <li><a href="<?= BASE_URL ?>pages/services/clearanceCert.php">Barangay Clearance</a></li>
                        <!-- <li><a href="#">Good Moral</a></li> -->
                    </ul>
                </li>
                <!-- <li><a href="#">Featured Profiles</a></li> -->
                <li><a href="<?= BASE_URL ?>pages/account/login.php">Employee Portal</a></li>
            </ul>
        </nav>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const hamburger = document.querySelector('.hamburger');
  const nav = document.querySelector('.nav');
  const dropdown = document.querySelector('.dropdown > a');
  const dropdownParent = document.querySelector('.dropdown');

  hamburger.addEventListener('click', () => {
    nav.classList.toggle('open');
  });

  // Toggle dropdown on mobile tap
  dropdown.addEventListener('click', (e) => {
    // Only act on small screens
    if (window.innerWidth <= 768) {
      e.preventDefault(); 

      dropdownParent.classList.toggle('open');
    }
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
      nav.classList.remove('open');
      dropdownParent.classList.remove('open');
    }
  });
});
</script>


<style>
    /* ===== Header & Navigation Styles ===== */
    .nav-links,
    .nav-links li {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    /* Header container */
    .main-header {
        background-color: #2f4f4f; 
        color: white;
        padding: 0.75rem 1.5rem;
        position: relative;
        z-index: 999;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Logo */
    .logo {
        display: flex;
        align-items: center;
        gap: 10px;
        user-select: none;
    }

    .logo img {
        height: 70px;
        width: 70px;
        object-fit: cover;
        border-radius: 50%;
    }

    .logo h1 {
        font-size: 1.4rem; 
        font-weight: 600;
        margin: 0;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .logo h1 span {
        display: block;
        font-size: 1.3rem;
        line-height: 1.1;
    }

    /* Navigation */
    .nav {
        transition: max-height 0.3s ease-out;
    }

    .nav-links {
        display: flex;
        gap: 1.5rem;
    }

    .nav-links a {
        text-decoration: none;
        color: white;
        padding: 0.5rem 0;
        display: block;
        font-weight: 600;
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .nav-links a:hover,
    .nav-links a:focus {
        color: #a6d8d8; /* lighter teal on hover */
        outline: none;
    }

    /* Dropdown */
    .dropdown {
        position: relative;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #406c6c;
        padding: 0.5rem 0;
        top: 100%;
        left: 0;
        border-radius: 0 0 5px 5px;
        min-width: 180px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        z-index: 1000;
    }

    .dropdown-content a {
        padding: 0.4rem 1.2rem;
        color: white;
        display: block;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
        transition: background-color 0.25s ease;
    }

    .dropdown-content a:hover,
    .dropdown-content a:focus {
        background-color: #2f4f4f;
        outline: none;
    }

    /* Show dropdown on hover */
    .dropdown:hover .dropdown-content,
    .dropdown:focus-within .dropdown-content {
        display: block;
    }

    /* Hamburger Button */
    .hamburger {
        display: none;
        background: none;
        font-size: 1.8rem;
        border: none;
        color: white;
        cursor: pointer;
        user-select: none;
        padding: 0;
        transition: color 0.3s ease;
    }

    .hamburger:hover,
    .hamburger:focus {
        color: #a6d8d8;
        outline: none;
    }

    /* ===== Responsive Styles ===== */
    @media (max-width: 768px) {
        .hamburger {
            display: block;
        }

        /* Hide nav by default on mobile */
        .nav {
            width: 100%;
            overflow: hidden;
            max-height: 0;
            background-color: #2f4f4f;
            position: absolute;
            top: 100%;
            left: 0;
            transition: max-height 0.4s ease-in-out;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        /* When nav is toggled open */
        .nav.open {
            max-height: 500px; /* enough height for the menu */
        }

        /* Stack nav links vertically */
        .nav-links {
            flex-direction: column;
            align-items: flex-start;
            padding: 1rem 1.5rem;
            gap: 0.75rem;
        }

        .nav-links li {
            width: 100%;
        }

        /* Style links full width with bottom border */
        .nav-links a {
            width: 100%;
            padding: 0.75rem 0;
            border-bottom: 1px solid #406c6c;
            font-size: 1.1rem;
        }

        /* Dropdown adjustments for mobile */
        .dropdown-content {
            position: relative;
            background-color: #3a6d6d;
            border-radius: 0;
            box-shadow: none;
            padding-left: 1.2rem;
            margin-top: 0.25rem;
            display: none; /* hide initially */
        }

        /* Show dropdown on tap (handled by JS or :focus-within) */
        .dropdown.open .dropdown-content {
            display: block;
        }

        /* Make dropdown links a bit bigger */
        .dropdown-content a {
            padding: 0.5rem 0;
            font-size: 1rem;
            border-bottom: 1px solid #2f4f4f;
        }

        /* Remove last border */
        .dropdown-content a:last-child {
            border-bottom: none;
        }
    }
</style>