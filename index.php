<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="JAITRA 2026 - The Ultimate Sports Carnival for All A.P. State Engineering Colleges. Experience thrilling competitions in Volleyball, Kabaddi, Badminton, and Pickleball with ₹5 Lakhs prize pool.">
    <meta name="keywords"
        content="JAITRA 2026, SRKR Engineering College, Sports Carnival, Engineering College Sports, AP State, Live Sports, Bhimavaram">
    <title>JAITRA 2026 | Home - AP's Premier Engineering Sports Carnival</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="css/styles.css">
    
    <style>
        /* ===== HOME PAGE SPECIFIC STYLES ===== */
        
        /* Prevent horizontal scroll */
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #1a2332 0%, #2563eb 100%);
            color: var(--color-white);
            padding: 4rem 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(37, 99, 235, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(99, 102, 241, 0.3) 0%, transparent 50%);
            animation: pulse 8s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(220, 38, 38, 0.9);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            animation: livePulse 2s ease-in-out infinite;
        }
        
        .live-dot {
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1rem;
            text-shadow: 0 4px 20px rgba(233, 229, 229, 0.3);
            color: white;
        }
        
        .hero-title .highlight {
            color: #fbbf24;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 500;
            margin-bottom: 2rem;
            opacity: 0.95;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            color:#c6eb25;
        }
        
        .hero-cta {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 3rem;
        }
        
        .cta-btn {
            padding: 1rem 2.5rem;
            font-size: 1.125rem;
            font-weight: 600;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
        }
        
        .cta-btn-primary {
            background: var(--color-white);
            color: var(--color-primary);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        
        .cta-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }
        
        .cta-btn-secondary {
            background: rgba(255, 255, 255, 0.15);
            color: var(--color-white);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .cta-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
        }
        
        .countdown-timer {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .countdown-item {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem 1.5rem;
            border-radius: 12px;
            min-width: 80px;
        }
        
        .countdown-value {
            display: block;
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }
        
        .countdown-label {
            display: block;
            font-size: 0.875rem;
            opacity: 0.8;
            margin-top: 0.5rem;
        }
        
        /* Overview Cards */
        .overview-section {
            padding: 4rem 1.5rem;
            background: var(--color-background);
        }
        
        .overview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .overview-card {
            background: var(--color-white);
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .overview-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .overview-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .overview-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        
        .overview-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 0.5rem;
        }
        
        .overview-text {
            font-size: 0.938rem;
            color: var(--color-secondary);
        }
        
        /* Sports Grid */
        .sports-section {
            padding: 4rem 1.5rem;
            background: transparent;
        }
        
        .section-title-center {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 3rem;
        }
        
        .sports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .sport-card {
            background: linear-gradient(135deg, var(--card-color) 0%, var(--card-color-dark) 100%);
            padding: 2rem;
            border-radius: 16px;
            color: white;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .sport-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sport-card:hover::before {
            opacity: 1;
        }
        
        .sport-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        }
        
        .sport-card.volleyball {
            --card-color: #6366f1;
            --card-color-dark: #4f46e5;
        }
        
        .sport-card.kabaddi {
            --card-color: #dc2626;
            --card-color-dark: #b91c1c;
        }
        
        .sport-card.badminton {
            --card-color: #0891b2;
            --card-color-dark: #0e7490;
        }
        
        .sport-card.pickleball {
            --card-color: #059669;
            --card-color-dark: #047857;
        }
        
        .sport-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        
        .sport-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .sport-stats {
            display: flex;
            justify-content: space-around;
            margin: 1.5rem 0;
            padding: 1rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .sport-stat {
            text-align: center;
        }
        
        .stat-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .stat-label {
            display: block;
            font-size: 0.75rem;
            opacity: 0.8;
            margin-top: 0.25rem;
        }
        
        .sport-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .sport-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        /* Event Information Section */
        .event-info-section {
            padding: 5rem 1.5rem;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }
        
        .info-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .info-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .info-header h2 {
            font-size: 3rem;
            font-weight: 800;
            color: var(--color-primary);
            margin-bottom: 1rem;
        }
        
        .info-tagline {
            font-size: 1.25rem;
            color: var(--color-secondary);
        }
        
        /* Key Details Grid */
        .key-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 4rem;
        }
        
        .detail-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .detail-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .highlight-card {
            border: 2px solid transparent;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #2563eb, #6366f1) border-box;
        }
        
        .detail-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .detail-label {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--color-secondary);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .detail-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-primary);
        }
        
        /* Registration Fees Section */
        .fees-section {
            margin-bottom: 4rem;
        }
        
        .fees-section h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .fees-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        
        .fee-card {
            background: white;
            padding: 2rem 1.5rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid var(--color-accent);
        }
        
        .fee-card:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }
        
        .sport-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 1rem;
        }
        
        .fee-amount {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--color-accent);
        }
        
        .fee-amount span {
            font-size: 0.875rem;
            color: var(--color-secondary);
            font-weight: 500;
            display: block;
            margin-top: 0.25rem;
        }
        
        /* Host Section / About SRKR */
        .host-section {
            margin-bottom: 4rem;
            padding: 3rem 2rem;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .host-section h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .section-subtitle {
            text-align: center;
            font-size: 1.5rem;
            color: #DC143C;
            font-weight: 600;
            margin-bottom: 3rem;
        }
        
        .host-content-wrapper {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 3rem;
            align-items: start;
        }
        
        .founder-image {
            text-align: center;
        }
        
        .founder-frame {
            background: linear-gradient(135deg, var(--color-primary) 0%, #1e3a8a 100%);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            margin-bottom: 1rem;
        }
        
        .founder-photo {
            width: 100%;
            height: auto;
            border-radius: 12px;
            display: block;
            object-fit: cover;
        }
        
        .founder-placeholder {
            background: white;
            border-radius: 12px;
            padding: 3rem;
            font-size: 6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
        }
        
        .founder-label {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-secondary);
            margin-top: 0.5rem;
        }
        
        .host-text-content {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 2.5rem;
            border-radius: 16px;
            border-left: 5px solid var(--color-accent);
        }
        
        .host-description p {
            font-size: 1.0625rem;
            line-height: 1.8;
            color: var(--color-secondary);
            margin-bottom: 1.25rem;
            text-align: justify;
        }
        
        .host-location {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .host-location p {
            margin: 0 !important;
            font-size: 1rem !important;
        }
        
        /* Coordinators Section */
        .coordinators-section {
            margin-bottom: 4rem;
        }
        
        .coordinators-section h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .coordinators-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .coordinator-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        }
        
        .coord-type {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-accent);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .coord-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .coord-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .coord-name {
            font-weight: 600;
            color: var(--color-primary);
            font-size: 1.125rem;
        }
        
        .coord-phone {
            color: var(--color-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .coord-phone:hover {
            color: var(--color-accent);
            transform: translateX(4px);
        }
        
        /* Social Actions */
        .social-actions {
            background: linear-gradient(135deg, var(--color-primary) 0%, #1e3a8a 100%);
            padding: 3rem;
            border-radius: 16px;
            color: white;
        }
        
        .social-links {
            margin-bottom: 2rem;
        }
        
        .social-links h4 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .social-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .social-btn {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .social-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }
        
        .action-btn {
            padding: 1rem 2.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.125rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .action-btn.primary {
            background: #fbbf24;
            color: var(--color-primary);
        }
        
        .action-btn.primary:hover {
            background: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(251, 191, 36, 0.4);
        }
        
        .action-btn.secondary {
            background: rgba(255, 255, 255, 0.9);
            color: var(--color-primary);
        }
        
        .action-btn.secondary:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
        }
        
        /* About SRKR College Section */
        .about-college-section {
            padding: 5rem 1.5rem;
            background: white;
        }
        
        .about-college-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .about-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .about-header h2 {
            font-size: 3rem;
            font-weight: 800;
            color: var(--color-primary);
            margin-bottom: 0.5rem;
        }
        
        .about-subtitle {
            font-size: 1.5rem;
            color: #DC143C;
            font-weight: 600;
        }
        
        .about-content-wrapper {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 3rem;
            align-items: start;
        }
        
        .founder-image {
            text-align: center;
        }
        
        .founder-frame {
            background: linear-gradient(135deg, var(--color-primary) 0%, #1e3a8a 100%);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            margin-bottom: 1rem;
        }
        
        .founder-placeholder {
            background: white;
            border-radius: 12px;
            padding: 3rem;
            font-size: 6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
        }
        
        .founder-label {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-secondary);
            margin-top: 0.5rem;
        }
        
        .about-text-content {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 2.5rem;
            border-radius: 16px;
            border-left: 5px solid var(--color-accent);
        }
        
        .about-description p {
            font-size: 1.0625rem;
            line-height: 1.8;
            color: var(--color-secondary);
            margin-bottom: 1.25rem;
            text-align: justify;
        }
        
        .highlight-text {
            font-weight: 600;
            color: var(--color-primary) !important;
            font-size: 1.125rem !important;
        }
        
        .college-highlights {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin: 2rem 0;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }
        
        .highlight-item {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .highlight-icon {
            font-size: 2.5rem;
        }
        
        .highlight-info {
            display: flex;
            flex-direction: column;
        }
        
        .highlight-label {
            font-size: 0.875rem;
            color: var(--color-secondary);
            font-weight: 500;
        }
        
        .highlight-value {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--color-primary);
        }
        
        .read-more-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--color-accent);
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .read-more-btn:hover {
            background: #1d4ed8;
            transform: translateX(4px);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .info-header h2 {
                font-size: 2rem;
            }
            
            .key-details-grid {
                grid-template-columns: 1fr;
            }
            
            .fees-grid {
                grid-template-columns: 1fr;
            }
            
            .host-card {
                flex-direction: column;
                text-align: center;
                padding: 2rem;
            }
            
            .coordinators-grid {
                grid-template-columns: 1fr;
            }
            
            .host-content-wrapper {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .founder-frame {
                max-width: 250px;
                margin: 0 auto;
            }
            
            .founder-placeholder {
                padding: 2rem;
                min-height: 200px;
                font-size: 4rem;
            }
            
            .host-description p {
                text-align: left;
            }
            
            .social-buttons {
                flex-direction: column;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
            
            .about-content-wrapper {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .founder-frame {
                max-width: 250px;
                margin: 0 auto;
            }
            
            .founder-placeholder {
                padding: 2rem;
                min-height: 200px;
                font-size: 4rem;
            }
            
            .college-highlights {
                grid-template-columns: 1fr;
                padding: 1.5rem;
            }
            
            .about-description p {
                text-align: left;
            }
            
            .hero-title {
                font-size: 2.25rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .countdown-timer {
                gap: 0.75rem;
            }
            
            .countdown-item {
                padding: 0.75rem 1rem;
                min-width: 60px;
            }
            
            .countdown-value {
                font-size: 1.5rem;
            }
            
            .why-container {
                grid-template-columns: 1fr;
            }
            
            .why-image {
                min-height: 250px;
                order: -1;
            }
            
            .section-title-center {
                font-size: 1.875rem;
            }
        }
        
        /* ===== IMAGE CAROUSEL ===== */
        .carousel-section {
            position: relative;
            width: 100%;
            height: 700px;
            overflow: hidden;
            background: #000;
        }
        
        .carousel-container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        .carousel-slides {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.8s ease-in-out;
        }
        
        .carousel-slide {
            min-width: 100%;
            height: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #000;
        }
        
        /* Hero Slide Specific Styles */
        .carousel-slide.hero-slide {
            background: linear-gradient(135deg, #1a2332 0%, #2563eb 100%);
            overflow: hidden;
        }
        
        .carousel-slide.hero-slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(37, 99, 235, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(99, 102, 241, 0.3) 0%, transparent 50%);
            animation: pulse 8s ease-in-out infinite;
        }
        
        
        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .carousel-nav:hover {
            background: rgba(0, 0, 0, 0.8);
            transform: translateY(-50%) scale(1.1);
        }
        
        .carousel-nav.prev {
            left: 20px;
        }
        
        .carousel-nav.next {
            right: 20px;
        }
        
        .carousel-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }
        
        .carousel-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .carousel-indicator.active {
            background: white;
            width: 30px;
            border-radius: 6px;
        }
        
        @media (max-width: 768px) {
            .carousel-section {
                height: 500px;
            }
            
            .carousel-nav {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
            
            .carousel-nav.prev {
                left: 10px;
            }
            
            .carousel-nav.next {
                right: 10px;
            }
            
            .carousel-overlay {
                bottom: 60px;
            }
            
            .carousel-cta-btn {
                padding: 0.875rem 1.5rem;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Live YouTube Ticker -->
    <div class="live-ticker">
        <div class="ticker-content">
            <div class="ticker-item">
                Volleyball Finals - Watch Live:
                <a href="https://youtube.com/live/example1" target="_blank">YouTube Stream</a>
            </div>
            <span class="ticker-separator">•</span>
            <div class="ticker-item">
                Kabaddi Semi-Finals - Watch Live:
                <a href="https://youtube.com/live/example2" target="_blank">YouTube Stream</a>
            </div>
            <span class="ticker-separator">•</span>
            <div class="ticker-item">
                Badminton Quarters - Watch Live:
                <a href="https://youtube.com/live/example3" target="_blank">YouTube Stream</a>
            </div>
            <span class="ticker-separator">•</span>
            <div class="ticker-item">
                Pickleball Opening Match - Watch Live:
                <a href="https://youtube.com/live/example4" target="_blank">YouTube Stream</a>
            </div>
            <!-- Duplicate for seamless loop -->
            <div class="ticker-item">
                Volleyball Finals - Watch Live:
                <a href="https://youtube.com/live/example1" target="_blank">YouTube Stream</a>
            </div>
            <span class="ticker-separator">•</span>
            <div class="ticker-item">
                Kabaddi Semi-Finals - Watch Live:
                <a href="https://youtube.com/live/example2" target="_blank">YouTube Stream</a>
            </div>
            <span class="ticker-separator">•</span>
            <div class="ticker-item">
                Badminton Quarters - Watch Live:
                <a href="https://youtube.com/live/example3" target="_blank">YouTube Stream</a>
            </div>
            <span class="ticker-separator">•</span>
            <div class="ticker-item">
                Pickleball Opening Match - Watch Live:
                <a href="https://youtube.com/live/example4" target="_blank">YouTube Stream</a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="header-main">
            <div class="logo-section">
                <img src="assets/logo.png" alt="SRKR Engineering College Logo" class="logo"
                    onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><circle cx=%2250%22 cy=%2250%22 r=%2245%22 fill=%22%23384959%22/><text x=%2250%22 y=%2260%22 text-anchor=%22middle%22 fill=%22white%22 font-size=%2230%22 font-weight=%22bold%22>S</text></svg>'">
                <div class="brand-info">
                    <div class="brand-text">
                        <h1 class="event-name">JAITRA <span>2026</span></h1>
                        <span class="event-tagline">Sports Carnival for All A.P. State Engineering Colleges</span>
                    </div>
                </div>
            </div>
            <div class="header-info">
                <div class="event-dates">
                    <div class="dates">7, 8 & 9 January 2026</div>
                    <div class="venue">SRKR Marg, Bhimavaram</div>
                </div>
                <div class="prize-badge">
                    <div class="label">Prize Pool</div>
                    <div class="amount">₹5 Lakhs</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Image Carousel Section -->
    <section class="carousel-section">
        <div class="carousel-container">
            <div class="carousel-slides" id="carouselSlides">
                <!-- Hero Slide -->
                <div class="carousel-slide hero-slide">
                    <div class="hero-content">
                        <div class="hero-badge">
                            <span class="live-dot"></span>
                            <span id="live-count">3</span> Matches Live Now
                        </div>
                        
                        <h2 class="hero-title">
                            <span class="highlight">JAITRA 2026</span><br>
                            AP's Premier Engineering Sports Carnival
                        </h2>
                        
                        <p class="hero-subtitle">
                            Experience the ultimate sports showdown featuring Volleyball, Kabaddi, Badminton, and Pickleball. 
                            Compete for glory and a massive ₹5 Lakhs prize pool!
                        </p>
                        
                        <div class="hero-cta">
                            <a href="scoreboard.php" class="cta-btn cta-btn-primary">
                                📊 View Scores
                            </a>
                            <a href="#" class="cta-btn cta-btn-secondary" id="liveMatchesBtn">
                                🎥 View Live Matches
                            </a>
                        </div>
                        
                        <div class="countdown-timer" id="countdown">
                            <div class="countdown-item">
                                <span class="countdown-value" id="days">07</span>
                                <span class="countdown-label">Days</span>
                            </div>
                            <div class="countdown-item">
                                <span class="countdown-value" id="hours">00</span>
                                <span class="countdown-label">Hours</span>
                            </div>
                            <div class="countdown-item">
                                <span class="countdown-value" id="minutes">00</span>
                                <span class="countdown-label">Minutes</span>
                            </div>
                            <div class="countdown-item">
                                <span class="countdown-value" id="seconds">00</span>
                                <span class="countdown-label">Seconds</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Poster Slides -->
                <div class="carousel-slide">
                    <img src="assets/home-1.jpeg" alt="JAITRA 2026 - Sports Carnival Poster"
                        onerror="this.src='https://via.placeholder.com/1200x700/1a2332/ffffff?text=JAITRA+2026+-+Sports+Carnival'">
                </div>
                <div class="carousel-slide">
                    <img src="assets/event-poster-2.jpg" alt="JAITRA 2026 - Event Details"
                        onerror="this.src='https://via.placeholder.com/1200x700/2563eb/ffffff?text=₹5+Lakhs+Prize+Pool'">
                </div>
                <div class="carousel-slide">
                    <img src="assets/event-poster-3.jpg" alt="JAITRA 2026 - Registration"
                        onerror="this.src='https://via.placeholder.com/1200x700/6366f1/ffffff?text=Register+Now+-+All+AP+Colleges'">
                </div>
            </div>
            
            <button class="carousel-nav prev" onclick="moveSlide(-1)">‹</button>
            <button class="carousel-nav next" onclick="moveSlide(1)">›</button>
            
            <div class="carousel-indicators" id="carouselIndicators"></div>
        </div>
    </section>

    <!-- Live Scoreboard Section -->
    <section class="scoreboard-section">
        <div class="scoreboard-container">
            <div class="scoreboard-header">
                <h2>🏆 Live Matches & Results</h2>
                <p class="scoreboard-subtitle">Follow all the action in real-time</p>
            </div>

            <!-- All Matches in Single Scroll -->
            <div class="matches-scroll" id="all-matches">
                <p class="loading-text">Loading matches...</p>
            </div>
        </div>
    </section>
       <div class="host-section">
                <h3>About SRKR College</h3>
                <p class="section-subtitle">Welcome to SRKREC!</p>
                
                <div class="host-content-wrapper">
                    <div class="founder-image">
                        <div class="founder-frame">
                            <img src="assets/srkr-founder.jpg" alt="SRKR Founder" class="founder-photo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="founder-placeholder" style="display: none;">🎓</div>
                        </div>
                        <p class="founder-label">Founder's Vision</p>
                    </div>
                    
                    <div class="host-text-content">
                        <div class="host-description">
                            <p class="highlight-text">
                                Sagi Rama Krishnam Raju Engineering College, established in 1980, is one of the earliest 
                                self-financing Engineering Colleges in the state of Andhra Pradesh.
                            </p>
                            
                            <p>
                                Established with a noble cause to empower rural students through technical education, 
                                the institution today has evolved as one of the pioneering technical institutions in the country.
                            </p>
                            
                            <p>
                                Spreading over 30 acres of green land, the institution has set in state-of-the-art facilities 
                                for science and technology and created a conducive environment for inclusive and culturally 
                                responsive teaching-learning process.
                            </p>
                            
                            <p>
                                Alongside education and research, it has established a history of work ethos that supports 
                                students in developing a creative, confident and logical approach to nation building, making 
                                them highly valued graduates and opening doors to a wide range of exciting careers.
                            </p>
                            
                            
                        </div>
                    </div>
                </div>
            </div>


    <!-- Sports Categories -->
    <section class="sports-section" id="sports">
        <h2 class="section-title-center">Featured Sports</h2>
        
        <div class="sports-grid">
            <!-- Volleyball -->
            <div class="sport-card volleyball">
                <div class="sport-icon">🏐</div>
                <h3 class="sport-name">Volleyball</h3>
                <div class="sport-stats">
                    <div class="sport-stat">
                        <span class="stat-value">16</span>
                        <span class="stat-label">Teams</span>
                    </div>
                    <div class="sport-stat">
                        <span class="stat-value">3</span>
                        <span class="stat-label">Live</span>
                    </div>
                </div>
                <a href="scoreboard.php?sport=volleyball" class="sport-btn">View Matches →</a>
            </div>
            
            <!-- Kabaddi -->
            <div class="sport-card kabaddi">
                <div class="sport-icon">🤼</div>
                <h3 class="sport-name">Kabaddi</h3>
                <div class="sport-stats">
                    <div class="sport-stat">
                        <span class="stat-value">12</span>
                        <span class="stat-label">Teams</span>
                    </div>
                    <div class="sport-stat">
                        <span class="stat-value">2</span>
                        <span class="stat-label">Live</span>
                    </div>
                </div>
                <a href="scoreboard.php?sport=kabaddi" class="sport-btn">View Matches →</a>
            </div>
            
            <!-- Badminton -->
            <div class="sport-card badminton">
                <div class="sport-icon">🏸</div>
                <h3 class="sport-name">Badminton</h3>
                <div class="sport-stats">
                    <div class="sport-stat">
                        <span class="stat-value">20</span>
                        <span class="stat-label">Teams</span>
                    </div>
                    <div class="sport-stat">
                        <span class="stat-value">1</span>
                        <span class="stat-label">Live</span>
                    </div>
                </div>
                <a href="scoreboard.php?sport=badminton" class="sport-btn">View Matches →</a>
            </div>
            
            <!-- Pickleball -->
            <div class="sport-card pickleball">
                <div class="sport-icon">🎾</div>
                <h3 class="sport-name">Pickleball</h3>
                <div class="sport-stats">
                    <div class="sport-stat">
                        <span class="stat-value">14</span>
                        <span class="stat-label">Teams</span>
                    </div>
                    <div class="sport-stat">
                        <span class="stat-value">0</span>
                        <span class="stat-label">Live</span>
                    </div>
                </div>
                <a href="scoreboard.php?sport=pickleball" class="sport-btn">View Matches →</a>
            </div>
        </div>
    </section>

        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <!-- About Column -->
            <div class="footer-column">
                <h3>About JAITRA 2026</h3>
                <p>AP's Premier Engineering Sports Carnival for all state engineering colleges featuring Volleyball, Kabaddi, Badminton, and Pickleball.</p>
                <p><strong>Prize Pool:</strong> ₹5 Lakhs</p>
            </div>
            
            <!-- Quick Links Column -->
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="scoreboard.php">Live Scoreboard</a></li>
                    <li><a href="#">Schedule</a></li>
                    <li><a href="#">Results</a></li>
                    <li><a href="#">Gallery</a></li>
                </ul>
            </div>
            
            <!-- Contact Column -->
            <div class="footer-column">
                <h3>Contact Coordinators</h3>
                <p><strong>Faculty:</strong></p>
                <p>Dr. Ch. Hari Mohan - <a href="tel:+919441911178">+91 94419 11178</a></p>
                <p>V. Avinash - <a href="tel:+919866474674">+91 98664 74674</a></p>
                <p><strong>Students:</strong></p>
                <p>V. Sivaram - <a href="tel:+918948636169">+91 89486 36169</a></p>
                <p>D. Ashok - <a href="tel:+918520088959">+91 85200 88959</a></p>
            </div>
            
            <!-- Follow Us Column -->
            <div class="footer-column">
                <h3>Follow Us</h3>
                <ul>
                    <li><a href="#">@SRKRECOFFICIAL</a></li>
                    <li><a href="#">@SRKR_ENGINEERING_COLLEGE</a></li>
                    <li><a href="#">SRKRECLIVEB303</a></li>
                </ul>
                <div class="footer-logo">SRKR</div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>JAITRA 2026 - SRKR Engineering College (A), Bhimavaram</p>
            <p>© 2026 SRKR Engineering College. All rights reserved. | Men & Women Categories
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Countdown Timer
        function updateCountdown() {
            const eventDate = new Date('2026-01-07T00:00:00').getTime();
            const now = new Date().getTime();
            const distance = eventDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');

            if (distance < 0) {
                document.getElementById('countdown').innerHTML = '<h3>Event is Live!</h3>';
            }
        }

        // Update countdown every second
        updateCountdown();
        setInterval(updateCountdown, 1000);

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // ===== IMAGE CAROUSEL =====
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const totalSlides = slides.length;
        const indicatorsContainer = document.getElementById('carouselIndicators');
        let autoScrollInterval;

        // Create indicators
        for (let i = 0; i < totalSlides; i++) {
            const indicator = document.createElement('div');
            indicator.className = 'carousel-indicator' + (i === 0 ? ' active' : '');
            indicator.onclick = () => goToSlide(i);
            indicatorsContainer.appendChild(indicator);
        }

        function moveSlide(direction) {
            currentSlide += direction;
            if (currentSlide >= totalSlides) currentSlide = 0;
            if (currentSlide < 0) currentSlide = totalSlides - 1;
            updateCarousel();
            resetAutoScroll();
        }

        function goToSlide(index) {
            currentSlide = index;
            updateCarousel();
            resetAutoScroll();
        }

        function updateCarousel() {
            const slidesContainer = document.getElementById('carouselSlides');
            slidesContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            // Update indicators
            const indicators = document.querySelectorAll('.carousel-indicator');
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentSlide);
            });
        }

        function resetAutoScroll() {
            // Clear existing interval
            if (autoScrollInterval) {
                clearInterval(autoScrollInterval);
            }
            // Start new interval
            autoScrollInterval = setInterval(() => {
                moveSlide(1);
            }, 5000);
        }

        // Start auto-scroll carousel every 5 seconds
        resetAutoScroll();
    </script>

    <!-- App JavaScript -->
    <script src="js/app.js"></script>
</body>

</html>