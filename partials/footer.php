<style>
    .site-footer {
        background-color: #2f4f4f;
        color: #ffffff;
        text-align: center;
        padding: 1rem 0;
        font-size: 0.9rem;
        position: relative;
        z-index: 1;
    }

    .site-footer p {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
    }

    /* University of Baguio Watermark */
    .watermark-logo {
        position: fixed;
        bottom: 12px;
        right: 12px;
        background-color: rgba(255, 255, 255, 0.9);
        padding: 8px 12px;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Segoe UI', sans-serif;
        z-index: 999;
        max-width: 90%;
        text-decoration: none; /* Removes underline from link */
        animation: fadeSlideUp 0.8s ease-out both;
    }

    /* Fade-in and slight slide-up animation */
    @keyframes fadeSlideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
    }

    .watermark-logo img {
        width: 34px;
        height: auto;
        flex-shrink: 0;
    }

    .watermark-text span {
        display: block;
        font-size: 0.8rem;
        line-height: 1.2;
        color: #333;
    }

    @media (max-width: 480px) {
    .watermark-logo {
        align-items: flex-start;
        bottom: 8px;
        right: 8px;
        padding: 6px 10px;
    }

    .watermark-logo img {
        width: 30px;
    }

    .watermark-text span {
        font-size: 0.75rem;
        text-align: left;
    }
    }

    @media print {
    .watermark-logo {
        display: none;
    }
    }
    .watermark-logo:hover {
    box-shadow: 0 0 12px rgba(128, 128, 128, 0.7), 0 0 8px rgba(128, 128, 128, 0.5);
    transform: scale(1.02);
    transition: all 0.3s ease;
    }

</style>

<a href="https://ubaguio.edu/" target="_blank" class="watermark-logo">
  <img src="<?= BASE_URL ?>assets/img/UB_Logo.png" alt="University of Baguio Logo" />
  <div class="watermark-text">
    <strong>
      <span>Developed by University of Baguio</span>
      <span>SIT Students</span>
    </strong>
  </div>
</a>


<footer class="site-footer">
  <div class="footer-content">
    <p>&copy; <?php echo date('Y'); ?> | Unified Barangay Information Service Hub</p>
  </div>
</footer>
