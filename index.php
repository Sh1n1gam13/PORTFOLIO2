<?php
// Database configuration
$host = "localhost";
$username = "BrenTzy";
$password = "morta123";
$database = "portfolio_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch active projects
$projects_query = "SELECT * FROM projects WHERE status = 'active' ORDER BY created_date DESC";
$projects_result = $conn->query($projects_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brent Warren M. Morta - Portfolio</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #6A0DAD 0%, #4B0082 50%, #2E0052 100%);
            --dark-bg: #0A0A0F;
            --darker-bg: #050508;
            --card-bg: rgba(106, 13, 173, 0.1);
            --card-border: rgba(106, 13, 173, 0.2);
            --text-primary: #E5E5E5;
            --text-secondary: #B0B0B0;
            --accent-violet: #6A0DAD;
            --accent-blue: #4B0082;
            --glow-violet: rgba(106, 13, 173, 0.4);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--dark-bg);
            overflow-x: hidden;
        }

        /* Animated background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(106, 13, 173, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(75, 0, 130, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(46, 0, 82, 0.08) 0%, transparent 50%);
            z-index: -1;
            animation: backgroundPulse 10s ease-in-out infinite;
        }

        @keyframes backgroundPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header with glassmorphism effect */
        header {
            background: url('ban2.jpg') center/cover, var(--primary-gradient);
            color: white;
            padding: 3rem 0;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
            animation: bannerMove 20s linear infinite alternate;
        }
        @keyframes bannerMove {
    0% {
        background-position: 0% 50%, 50% 50%;
    }
    100% {
        background-position: 100% 50%, 50% 50%;
    }
}

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 30% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 80%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            z-index: 1;
        }

        .header-content {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        /* Modern professional profile image */
        .profile-img {
            width: 280px;
            height: 350px;
            border-radius: 20px;
            background: url('pic2.jpg') center/cover;
            border: 3px solid rgba(255,255,255,0.15);
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 
                0 20px 60px rgba(106, 13, 173, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .profile-img::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.1) 0%, 
                rgba(255, 255, 255, 0.05) 50%, 
                rgba(106, 13, 173, 0.1) 100%);
            opacity: 0;
            transition: all 0.4s ease;
        }

        .profile-img::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, 
                transparent, 
                rgba(255, 255, 255, 0.1), 
                transparent);
            transform: rotate(45deg) translateX(-100%);
            transition: all 0.6s ease;
        }

        .profile-img:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 
                0 30px 80px rgba(106, 13, 173, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .profile-img:hover::before {
            opacity: 1;
        }

        .profile-img:hover::after {
            transform: rotate(45deg) translateX(100%);
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        /* Profile popup modal */
        .profile-popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s ease;
        }

        .profile-popup.active {
            opacity: 1;
            visibility: visible;
        }

        .popup-content {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 60px rgba(139, 92, 246, 0.3);
            transform: scale(0.8);
            transition: all 0.4s ease;
            max-width: 90vw;
            max-height: 90vh;
        }

        .profile-popup.active .popup-content {
            transform: scale(1);
        }

        .popup-image {
            width: 300px;
            height: 375px;
            border-radius: 20px;
            background: url('pic1.jpg') center/cover;
            margin: 0 auto 1rem;
            box-shadow: 0 20px 60px var(--glow-violet);
        }

        .close-popup {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .close-popup:hover {
            background: rgba(139, 92, 246, 0.2);
            transform: rotate(90deg);
        }

        .header-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-left: 2rem;
        }

        .header-text h1 {
            font-size: 4rem;
            margin-bottom: 0.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, #E5E5E5 50%, #B0B0B0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .header-text p {
            font-size: 1.6rem;
            opacity: 0.9;
            margin-bottom: 1.5rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.8);
        }

        .header-text .subtitle {
            font-size: 1.1rem;
            opacity: 0.7;
            font-weight: 300;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Navigation with glassmorphism */
        nav {
            background: rgba(10, 10, 15, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--card-border);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(106, 13, 173, 0.1);
        }

        nav ul {
            display: flex;
            list-style: none;
            justify-content: center;
            gap: 1rem;
        }

        nav a {
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 500;
            padding: 0.8rem 1.8rem;
            border-radius: 30px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
        }

        nav a::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--primary-gradient);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: -1;
        }

        nav a:hover {
            color: white;
            border-color: var(--accent-violet);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(106, 13, 173, 0.3);
        }

        nav a:hover::before {
            opacity: 1;
        }

        nav a.active {
            color: white;
            border-color: var(--accent-violet);
            background: var(--primary-gradient);
            box-shadow: 0 5px 20px rgba(106, 13, 173, 0.4);
        }

        /* Sections with enhanced styling */
        section {
            padding: 5rem 0;
            position: relative;
        }

        section:nth-child(even) {
            background: rgba(139, 92, 246, 0.02);
        }

        .section-title {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 3rem;
            color: var(--text-primary);
            position: relative;
            font-weight: 700;
        }

        .section-title::after {
            content: '';
            width: 80px;
            height: 4px;
            background: var(--primary-gradient);
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
            box-shadow: 0 0 20px var(--glow-violet);
        }

        /* Enhanced skills section */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .skill-category {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            padding: 2.5rem;
            border-radius: 20px;
            backdrop-filter: blur(20px);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .skill-category::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: var(--primary-gradient);
            transition: all 0.4s ease;
        }

        .skill-category:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(139, 92, 246, 0.2);
            border-color: var(--accent-violet);
        }

        .skill-category:hover::before {
            left: 0;
        }

        .skill-category h3 {
            color: var(--accent-violet);
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
            font-weight: 600;
        }

        .skill-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
        }

        .skill-tag {
            background: var(--primary-gradient);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
            transition: all 0.3s ease;
        }

        .skill-tag:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.5);
        }

        /* Enhanced portfolio section */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 2.5rem;
            margin-top: 3rem;
        }

        .portfolio-item {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            overflow: hidden;
            backdrop-filter: blur(20px);
            transition: all 0.4s ease;
            position: relative;
        }

        .portfolio-item:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(139, 92, 246, 0.3);
            border-color: var(--accent-violet);
        }

        .portfolio-img {
            width: 100%;
            height: 220px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .portfolio-img::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .portfolio-item:hover .portfolio-img::before {
            opacity: 1;
        }

        .portfolio-content {
            padding: 2rem;
        }

        .portfolio-content h3 {
            color: var(--text-primary);
            margin-bottom: 0.8rem;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .portfolio-content p {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .portfolio-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .portfolio-tag {
            background: rgba(139, 92, 246, 0.1);
            color: var(--accent-violet);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            border: 1px solid rgba(139, 92, 246, 0.2);
            font-weight: 500;
        }

        /* Enhanced about section */
        .about-content {
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
            font-size: 1.2rem;
            line-height: 1.8;
            color: var(--text-secondary);
        }

        /* Enhanced contact section */
        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .contact-item {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            padding: 2.5rem;
            border-radius: 20px;
            text-align: center;
            backdrop-filter: blur(20px);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .contact-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transition: all 0.4s ease;
        }

        .contact-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(139, 92, 246, 0.2);
        }

        .contact-item:hover::before {
            transform: scaleX(1);
        }

        .contact-item h3 {
            color: var(--accent-violet);
            margin-bottom: 1rem;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .contact-item p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        /* Admin button styling */
        .admin-btn {
            background: var(--primary-gradient);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.4s ease;
            box-shadow: 0 5px 20px rgba(139, 92, 246, 0.3);
            margin: 2rem auto;
            text-align: center;
        }

        .admin-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(139, 92, 246, 0.5);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .header-content {
                grid-template-columns: 1fr;
                gap: 2rem;
                text-align: center;
                justify-items: center;
            }

            .header-text {
                padding-left: 0;
                text-align: center;
            }

            .header-text h1 {
                font-size: 2.8rem;
            }

            .header-text p {
                font-size: 1.3rem;
            }

            .profile-img {
                width: 240px;
                height: 300px;
            }

            .popup-image {
                width: 260px;
                height: 325px;
            }

            nav ul {
                flex-wrap: wrap;
                gap: 1rem;
            }

            .section-title {
                font-size: 2.2rem;
            }

            .skills-grid,
            .portfolio-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Scroll animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.8s ease forwards;
        }

        .slide-in-left {
            animation: slideInLeft 0.8s ease forwards;
        }

        /* Loading animation for dynamic content */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(139, 92, 246, 0.3);
            border-radius: 50%;
            border-top-color: var(--accent-violet);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Profile Popup Modal -->
    <div class="profile-popup" id="profilePopup">
        <button class="close-popup" onclick="closeProfilePopup()">&times;</button>
        <div class="popup-content">
            <div class="popup-image" id="popupImage">
                <!-- Same photo as profile image will appear here -->
            </div>
            <h3 style="color: var(--accent-violet); margin-bottom: 0.5rem;">Brent Warren M. Morta</h3>
            <p style="color: var(--text-secondary);">Full Stack Developer & Designer</p>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 1rem;">21 years old | Philippines</p>
        </div>
    </div>

    <header>
        <div class="container">
            <div class="header-content">
                <div class="profile-img" onclick="openProfilePopup()">
                    <!-- Replace this URL with your actual photo -->
                </div>
                <div class="header-text">
                    <h1>Brent Warren M. Morta</h1>
                    <p>Full Stack Developer & Designer</p>
                    <div class="subtitle">Creating digital experiences with modern technology</div>
                </div>
            </div>
        </div>
    </header>

    <nav>
        <div class="container">
            <ul>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#skills" class="nav-link">Skills</a></li>
                <li><a href="#portfolio" class="nav-link">Portfolio</a></li>
                <li><a href="admin.php" class="nav-link">Admin</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>
        </div>
    </nav>

    <section id="about">
        <div class="container">
            <h2 class="section-title">About Me</h2>
            <div class="about-content">
                <p>I'm a passionate 21-year-old developer and designer with expertise in programming, web design, and graphic design. I love creating digital solutions that combine functionality with beautiful design. My journey in technology started with curiosity and has grown into a dedication to building meaningful projects that make a difference.</p>
                
                <p>With skills in Java, Python, and JavaScript, I enjoy working on both front-end and back-end development. I believe in continuous learning and staying updated with the latest technologies and design trends.</p>
            </div>
        </div>
    </section>

    <section id="skills">
        <div class="container">
            <h2 class="section-title">Skills & Expertise</h2>
            <div class="skills-grid">
                <div class="skill-category">
                    <h3>Programming Languages</h3>
                    <div class="skill-tags">
                        <span class="skill-tag">Java</span>
                        <span class="skill-tag">Python</span>
                        <span class="skill-tag">JavaScript</span>
                        <span class="skill-tag">HTML5</span>
                        <span class="skill-tag">CSS3</span>
                        <span class="skill-tag">SQL</span>
                    </div>
                </div>
                
                <div class="skill-category">
                    <h3>Web Development</h3>
                    <div class="skill-tags">
                        <span class="skill-tag">React</span>
                        <span class="skill-tag">Node.js</span>
                        <span class="skill-tag">Express.js</span>
                        <span class="skill-tag">MongoDB</span>
                        <span class="skill-tag">Bootstrap</span>
                        <span class="skill-tag">Responsive Design</span>
                    </div>
                </div>

                <div class="skill-category">
                    <h3>Design & Tools</h3>
                    <div class="skill-tags">
                        <span class="skill-tag">Adobe Photoshop</span>
                        <span class="skill-tag">Adobe Illustrator</span>
                        <span class="skill-tag">Figma</span>
                        <span class="skill-tag">UI/UX Design</span>
                        <span class="skill-tag">Git</span>
                        <span class="skill-tag">VS Code</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="portfolio">
        <div class="container">
            <h2 class="section-title">My Portfolio</h2>
            <div class="portfolio-grid">
                <?php
                if ($projects_result->num_rows > 0) {
                    while($project = $projects_result->fetch_assoc()) {
                        // Split technologies into an array
                        $tech_array = explode(',', $project['technologies']);
                        ?>
                        <div class="portfolio-item">
                            <div class="portfolio-img">
                                <?php echo htmlspecialchars($project['image_url']); ?>
                            </div>
                            <div class="portfolio-content">
                                <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                                <p><?php echo htmlspecialchars($project['description']); ?></p>
                                <div class="portfolio-tags">
                                    <?php
                                    foreach($tech_array as $tech) {
                                        echo '<span class="portfolio-tag">' . htmlspecialchars(trim($tech)) . '</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p style="color: var(--text-secondary); text-align: center; grid-column: 1 / -1;">No projects found. Use the admin panel to add some projects.</p>';
                }
                ?>
            </div>
            
            <!-- Admin Panel Link -->
            <div style="text-align: center; margin-top: 3rem;">
                <a href="admin.php" class="admin-btn">Manage Projects</a>
            </div>
        </div>
    </section>

    <section id="contact">
        <div class="container">
            <h2 class="section-title">Get In Touch</h2>
            <div class="contact-info">
                <div class="contact-item">
                    <h3>📧 Email</h3>
                    <p>s.brentwarren.morta@sccr.edu</p>
                </div>
                <div class="contact-item">
                    <h3>📱 Phone</h3>
                    <p>09556273936</p>
                </div>
                <div class="contact-item">
                    <h3>🌐 LinkedIn</h3>
                    <p>linkedin.com/in/brentmorta</p>
                </div>
                <div class="contact-item">
                    <h3>💻 GitHub</h3>
                    <p>github.com/Sh1n1gam13</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Profile popup functionality
        function openProfilePopup() {
            document.getElementById('profilePopup').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeProfilePopup() {
            document.getElementById('profilePopup').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close popup when clicking outside
        document.getElementById('profilePopup').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProfilePopup();
            }
        });

        // Close popup with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProfilePopup();
            }
        });

        // Smooth scrolling for navigation
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth'
                    });
                    
                    // Update active nav link
                    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });

        // Scroll animations with Intersection Observer
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('skill-category') || 
                        entry.target.classList.contains('portfolio-item') ||
                        entry.target.classList.contains('contact-item')) {
                        entry.target.classList.add('fade-in');
                    } else {
                        entry.target.classList.add('slide-in-left');
                    }
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('section, .skill-category, .portfolio-item, .contact-item').forEach(element => {
            observer.observe(element);
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Portfolio loaded with database integration');
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>