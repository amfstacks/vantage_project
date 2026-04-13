<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Vantage Luxe Realty | Coming Soon</title>

    <!-- Enhanced Meta Tags for WhatsApp & Social Sharing -->
    <meta property="og:title" content="Vantage Luxe Realty – Launching Soon" />
    <meta property="og:description" content="Elevating luxury living. Exclusive properties, seamless services, and unparalleled elegance await. Get notified on launch." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://vantageluxerealty.com" />
    <meta property="og:image" content="https://i.imgur.com/8vGqKxJ.jpg" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:site_name" content="Vantage Luxe Realty" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Vantage Luxe Realty – Launching Soon" />
    <meta name="twitter:description" content="Elevating luxury living. Exclusive properties, seamless services, and unparalleled elegance await." />
    <meta name="twitter:image" content="https://i.imgur.com/8vGqKxJ.jpg" />

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>Gold</text></svg>">

    <!-- Fonts: Playfair Display (Luxury) + Satoshi (Modern, Premium) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Satoshi:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* === CSS RESET & BASE === */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --gold: #d4af37;
            --gold-light: #e6c963;
            --dark: #0f0f0f;
            --darker: #0a0a0a;
            --text: #e5e5e5;
            --text-muted: #bbbbbb;
            --border: #222222;
        }

        body {
            font-family: 'Satoshi', sans-serif;
            background: var(--dark);
            color: var(--text);
            line-height: 1.7;
            overflow-x: hidden;
            opacity: 0;
            animation: fadeIn 1s ease forwards;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* === HEADER & NAV === */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 1.8rem 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        header.scrolled {
            background: rgba(15, 15, 15, 0.97);
            backdrop-filter: blur(16px);
            padding: 1.2rem 0;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 2.1rem;
            font-weight: 700;
            color: var(--gold);
            letter-spacing: 0.5px;
            text-decoration: none;
            position: relative;
        }

        .logo span {
            color: #ffffff;
            font-weight: 400;
        }

        .logo::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gold);
            transition: width 0.4s ease;
        }

        .logo:hover::after {
            width: 100%;
        }

        /* Hamburger Icon (White) */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            z-index: 1001;
        }

        .hamburger span {
            width: 28px;
            height: 2px;
            background: #ffffff;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            max-width: 320px;
            height: 100vh;
            background: rgba(15, 15, 15, 0.98);
            backdrop-filter: blur(20px);
            padding: 6rem 2rem 2rem;
            transition: right 0.5s cubic-bezier(0.77, 0, 0.175, 1);
            z-index: 1000;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.5);
        }

        .mobile-menu.active {
            right: 0;
        }

        .mobile-menu ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 1.8rem;
        }

        .mobile-menu a {
            color: #cccccc;
            text-decoration: none;
            font-weight: 500;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
            padding: 0.5rem 0;
            position: relative;
            transition: all 0.3s ease;
        }

        .mobile-menu a:hover {
            color: var(--gold);
            padding-left: 10px;
        }

        .mobile-menu a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 0;
            height: 1px;
            background: var(--gold);
            transition: width 0.3s ease;
        }

        .mobile-menu a:hover::before {
            width: 30px;
        }

        /* Desktop Nav */
        .nav-links {
            display: flex;
            gap: 2.8rem;
            list-style: none;
        }

        .nav-links a {
            color: #cccccc;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 1.5px;
            bottom: -8px;
            left: 0;
            background: var(--gold);
            transition: width 0.4s ease;
        }

        .nav-links a:hover {
            color: var(--gold);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* === HERO SECTION === */
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: 
                linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.75)),
                url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120"><rect width="120" height="120" fill="%23151515"/><path d="M0 60 L60 0 L120 60 L60 120 Z" fill="%23d4af37" opacity="0.06"/></svg>') repeat;
            background-size: 90px;
            animation: float 25s infinite linear;
            z-index: 1;
        }

        @keyframes float {
            0% { transform: translateY(0); }
            100% { transform: translateY(-90px); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 1000px;
            padding: 2rem;
            animation: slideUp 1.2s ease 0.5s both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 6rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 1.5rem;
            line-height: 1.05;
            letter-spacing: -1.5px;
            position: relative;
        }

        .hero h1 span {
            color: var(--gold);
            position: relative;
            display: inline-block;
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0%, 100% { text-shadow: 0 0 10px rgba(212, 175, 55, 0.5); }
            50% { text-shadow: 0 0 30px rgba(212, 175, 55, 0.9), 0 0 50px rgba(212, 175, 55, 0.6); }
        }

        .hero p {
            font-size: 1.35rem;
            color: var(--text-muted);
            margin-bottom: 3.5rem;
            max-width: 720px;
            margin-left: auto;
            margin-right: auto;
            font-weight: 300;
            animation: slideUp 1.2s ease 0.7s both;
        }

        .cta-button {
            display: inline-block;
            background: var(--gold);
            color: #0f0f0f;
            padding: 1.1rem 3rem;
            border-radius: 60px;
            text-decoration: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.92rem;
            transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.35);
            position: relative;
            overflow: hidden;
            animation: slideUp 1.2s ease 0.9s both;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: 0.7s;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .cta-button:hover {
            background: #e6c963;
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.5);
        }

        /* === PROGRESS SECTION === */
        .progress-section {
            padding: 9rem 0;
            text-align: center;
            background: #111111;
            position: relative;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.4rem;
            color: #ffffff;
            margin-bottom: 1.6rem;
            animation: slideUp 1s ease 0.3s both;
        }

        .section-title span {
            color: var(--gold);
        }

        .progress-text {
            font-size: 1.15rem;
            color: #aaaaaa;
            max-width: 720px;
            margin: 0 auto 3.5rem;
            animation: slideUp 1s ease 0.5s both;
        }

        .progress-bar-container {
            width: 100%;
            max-width: 650px;
            margin: 0 auto 2rem;
            background: #1a1a1a;
            height: 14px;
            border-radius: 7px;
            overflow: hidden;
            box-shadow: inset 0 3px 10px rgba(0,0,0,0.4);
            animation: slideUp 1s ease 0.7s both;
        }

        .progress-bar {
            height: 100%;
            width: 78%;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 7px;
            position: relative;
            overflow: hidden;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.25) 50%, transparent 70%);
            animation: shine 2.2s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .progress-label {
            font-size: 1.5rem;
            color: var(--gold);
            font-weight: 600;
            animation: slideUp 1s ease 0.9s both;
        }

        /* === FEATURES === */
        .features {
            padding: 7rem 0;
            background: var(--darker);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.8rem;
            margin-top: 3.5rem;
        }

        .feature-card {
            background: #141414;
            padding: 3rem 2.2rem;
            border-radius: 18px;
            text-align: center;
            transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: 1px solid var(--border);
            opacity: 0;
            transform: translateY(30px);
        }

        .feature-card.visible {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.8s ease;
        }

        .feature-card:hover {
            transform: translateY(-12px);
            border-color: var(--gold);
            box-shadow: 0 25px 50px rgba(212, 175, 55, 0.15);
        }

        .feature-icon {
            width: 78px;
            height: 78px;
            margin: 0 auto 1.8rem;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #0f0f0f;
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
        }

        .feature-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.65rem;
            color: #ffffff;
            margin-bottom: 1.1rem;
        }

        .feature-card p {
            color: #999999;
            font-size: 0.98rem;
            line-height: 1.6;
        }

        /* === FOOTER === */
        footer {
            background: var(--darker);
            padding: 3.5rem 0 1.8rem;
            border-top: 1px solid var(--border);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.9rem;
            color: var(--gold);
            text-decoration: none;
        }

        .footer-links {
            display: flex;
            gap: 2.2rem;
        }

        .footer-links a {
            color: #888888;
            text-decoration: none;
            font-size: 0.92rem;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--gold);
        }

        .copyright {
            text-align: center;
            margin-top: 2.2rem;
            color: #666666;
            font-size: 0.88rem;
        }

        /* === RESPONSIVE === */
        @media (max-width: 992px) {
            .hero h1 { font-size: 4.8rem; }
            .nav-links { display: none; }
            .hamburger { display: flex; }
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 3.6rem; }
            .hero p { font-size: 1.15rem; }
            .section-title { font-size: 2.8rem; }
            .footer-content { flex-direction: column; text-align: center; }
        }

        @media (max-width: 480px) {
            .hero h1 { font-size: 2.8rem; }
            .container { padding: 0 1.5rem; }
            .cta-button { padding: 1rem 2.2rem; font-size: 0.88rem; }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="container">
            <nav class="nav">
                <a href="#" class="logo">Vantage<span>Luxe</span></a>
                <ul class="nav-links">
                    <li><a href="#">Properties</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu">
        <ul>
            <li><a href="#">Properties</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Elevating <span>Luxury</span> Living</h1>
            <p>Our new digital experience is under construction. Prepare to explore exclusive properties, seamless services, and unparalleled elegance.</p>
            <a href="#notify" class="cta-button">Get Notified on Launch</a>
        </div>
    </section>

    <!-- Progress Section -->
    <section class="progress-section">
        <div class="container">
            <h2 class="section-title">Website <span>Progress</span></h2>
            <p class="progress-text">We're meticulously crafting every detail to deliver a world-class real estate platform.</p>
            <div class="progress-bar-container">
                <div class="progress-bar"></div>
            </div>
            <div class="progress-label">78% Complete</div>
        </div>
    </section>

    <!-- Features Preview -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">What to <span>Expect</span></h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">Luxury Home</div>
                    <h3>Curated Properties</h3>
                    <p>Hand-selected luxury homes, estates, and investment opportunities worldwide.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">Search</div>
                    <h3>Advanced Search</h3>
                    <p>Powerful filters and AI-driven recommendations tailored to your lifestyle.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">Sparkles</div>
                    <h3>Virtual Tours</h3>
                    <p>Immersive 3D experiences and professional photography for every listing.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <a href="#" class="footer-logo">VantageLuxe</a>
                <div class="footer-links">
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Contact</a>
                </div>
            </div>
            <p class="copyright">© 2025 Vantage Luxe Realty. All rights reserved. Launching Soon.</p>
        </div>
    </footer>

    <script>
        // Header scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Hamburger menu toggle
        const hamburger = document.querySelector('.hamburger');
        const mobileMenu = document.querySelector('.mobile-menu');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });

        // Close menu when clicking link
        document.querySelectorAll('.mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
            });
        });

        // CTA Alert
        document.querySelector('.cta-button').addEventListener('click', (e) => {
            e.preventDefault();
            alert('Launch notification system coming soon!');
        });

        // Animate feature cards on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.feature-card').forEach(card => {
            observer.observe(card);
        });
    </script>
</body>
</html>