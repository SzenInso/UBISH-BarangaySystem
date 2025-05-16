<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - GreenWater Village</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 180px;
      box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
      z-index: 1;
    }

    .dropdown-content a {
      color: black;
      padding: 10px 16px;
      text-decoration: none;
      display: block;
    }

    .dropdown-content a:hover {
      background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    .dropdown:hover .dropbtn {
      background-color: #ddd;
    }
  </style>
</head>
<body>

  <div class="header">
    <div class="logo-section">
      <div style="display: flex; align-items: center;">
        <img src="logo.png" alt="logo">
        <div class="text">UBISH</div>
      </div>
    </div>

    <nav>
      <a href="index.html">Home</a>
      <div class="dropdown">
        <a href="about.html" class="dropbtn">About Us</a>
        <div class="dropdown-content">
          <a href="#vision">Vision</a>
          <a href="#mission">Mission</a>
          <a href="#goals">Goals</a>
          <a href="#history">History</a>
          <a href="#legal-basis">Legal Basis</a>
        </div>
      </div>
      <a href="#">Events</a>
      <a href="#">Demographics</a>
      <button type="button">Log in</button>
    </nav>
  </div>

  <hr>

  <div class="main-content">

    <div id="vision" class="section">
      <h2>Our Vision</h2>
      <p>"By 2035, Greenwater Village Barangay aims to become a thriving, faith-driven community that fosters resilience, sustainability, and equal opportunities for all, while preserving our cultural heritage and promoting environmental stewardship, with globally competitive and strategic leadership."</p>
    </div>

    <div id="mission" class="section">
      <h2>Our Mission</h2>
      <p>"To serve and empower our community, protect our environment, and promote the well-being of Greenwater Village residents through quality services, sustainable practices, and community engagement."</p>
    </div>

    <div id="goals" class="section">
      <h2>Our Goals</h2>
      <p><b>G</b> God Fearing</p>
      <p><b>R</b> Respectfulness</p>
      <p><b>E</b> Empowerment</p>
      <p><b>E</b> Equality</p>
      <p><b>N</b> Novelty</p>
      <br>
      <p><b>W</b> Willingness</p>
      <p><b>A</b> Acceptance</p>
      <p><b>T</b> Transparency</p>
      <p><b>E</b> Environment Safety</p>
      <p><b>R</b> Responsive</p>
    </div>

    <div id="history" class="section">
      <h2>History of Greenwater</h2>
      <p>The neighborhood association chose GREENWATER VILLAGE as the official name of the newly created barrio because of the presence of spring water that appeared green due to abundant moss. Additionally, the words "green" and "water" symbolically go hand-in-hand—green plants help produce water, while water keeps plants fresh and green.</p>
    </div>

    <div id="legal-basis" class="section">
      <h2>Legal Basis of Barangay Existence</h2>
      <p>The Barangay came into existence on August 6, 1971, during the term of Mayor Luis Lardizabal. With guidance and support from the Association of Barrio Council headed by Mr. Federico Librado, Mr. Lawrence Baon was appointed as Barrio Captain to oversee the newly formed barrio until a formal election could be held.</p>
      <p>The first barrio election was held on January 9, 1972. Later, the term "Barrio" was officially changed to "Barangay" by law, a name that continues to be used today.</p>
    </div>
    
  </div>

  <footer>
    <div class="footer-section">
      <div class="footer-logo">
        <img src="logo.png" alt="logo">
        <span>GreenWater Village</span>
      </div>
    </div>

    <div class="footer-section">
      <h3>Links</h3>
      <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="about.html">About Us</a></li>
        <li><a href="#">Blog</a></li>
        <li><a href="#">Icons</a></li>
      </ul>
    </div>

    <div class="footer-section">
      <h3>Projects</h3>
      <ul>
        <li><a href="#">Content</a></li>
        <li><a href="#">Content</a></li>
      </ul>
    </div>

    <div class="footer-section">
      <h3>Contact Us</h3>
      <ul>
        <li>Phone: (***) ***-****</li>
      </ul>
    </div>
  </footer>

</body>
</html>
