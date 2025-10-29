<?php include '_header.php'; ?>

<div class="flex flex-col min-h-screen">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./Logos/Bulsu MSC Logo-02.png" rel="icon" type="image/png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&family=Segoe+UI:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Events | BulSU MSC</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Segoe UI', 'Segoe UI Web', 'Segoe UI Symbol', 'Helvetica Neue', 'Arial', sans-serif;
            background-color: #080c24;
            scroll-behavior: smooth;
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1 {
            font-family: 'Segoe UI', 'Segoe UI Web', 'Segoe UI Symbol', 'Helvetica Neue', 'Arial', sans-serif;
        }

        h2, h3 {
            font-family: 'Orbitron', sans-serif;
        }
        
        #main-header {
            background-color: #00071c;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        #main-header.header-scrolled {
            background-color: #00071c;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.4);
        }

        .nav-link {
            color: #d1d5db;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #b9da05;
        }

        .nav-icon-wrapper {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: transparent;
            transition: all 0.3s;
            cursor: pointer;
        }

        .nav-icon-wrapper:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-icon {
            color: white;
            font-size: 1.25rem;
            transition: transform 0.3s, color 0.3s;
        }

        .nav-icon-wrapper:hover .nav-icon {
            color: #b9da05;
            transform: scale(1.1);
        }

        .hero-section {
            margin-top: 80px;
            padding: 60px 20px;
            text-align: center;
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 0;
            position: relative;
        }

        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            font-family: 'Orbitron', sans-serif;
            color: #b9da05;
            position: relative;
            z-index: 1;
        }

        .hero-section p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            position: relative;
            z-index: 1;
        }

        .main-content {
            flex: 1;
            max-width: 80rem;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .filter-container {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        .filter-buttons {
            background: #011538;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 8px;
            display: flex;
            gap: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 0 15px rgba(255, 255, 255, 0.1);
        }

        .filter-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            background: transparent;
            color: #d1d5db;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .filter-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .filter-btn.active {
            background: #b9da05;
            color: #011538;
            box-shadow: 0 4px 12px rgba(185, 218, 5, 0.3);
        }

        .event-section {
            width: 100%;
            margin: 0;
            padding: 0 1rem;
        }

        .event-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin: 0 auto;
            max-width: 80rem;
            padding: 0 20px 70px;
            position: relative;
        }

        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(185, 218, 5, 0.9);
            color: #080c24;
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
            z-index: 10;
        }

        .nav-arrow:hover {
            background: #b9da05;
            transform: translateY(-50%) scale(1.1);
        }

        .nav-arrow.prev {
            left: -25px;
        }

        .nav-arrow.next {
            right: -25px;
        }

        .nav-arrow:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .nav-arrow:disabled:hover {
            transform: translateY(-50%);
        }

        .empty-state {
            background: #011538;
            border: 2px dashed rgba(185, 218, 5, 0.3);
            border-radius: 16px;
            padding: 60px 40px;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            margin: 0 auto;
            max-width: 80rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: rgba(185, 218, 5, 0.5);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: #b9da05;
            margin-bottom: 10px;
            font-family: 'Orbitron', sans-serif;
        }

        .empty-state p {
            font-size: 1rem;
            line-height: 1.6;
        }

        .event-card {
            background: #011538;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 0;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 0 10px rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: row;
            overflow: hidden;
            height: 280px;
            min-height: 280px;
            width: 100%;
            max-width: none;
            display: none;
        }

        .event-card.active {
            display: flex;
        }

        .event-card:hover {
            transform: translateY(-8px);
            border-color: #b9da05;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7), 0 0 30px rgba(185, 218, 5, 0.3);
        }

        .event-image {
            width: 280px;
            height: 100%;
            background: #1a1f3a;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            padding: 25px;
        }

        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        .event-image i {
            font-size: 3rem;
            color: #64748b;
        }

        .event-content {
            padding: 40px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .event-card h3 {
            font-size: 1.75rem;
            font-weight: bold;
            margin-bottom: 12px;
            color: white;
            line-height: 1.3;
        }

        .event-card .date {
            color: #b9da05;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 12px;
        }

        .event-card .excerpt {
            color: #d1d5db;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: #011538;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            position: relative;
            color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6), 0 0 20px rgba(255, 255, 255, 0.1);
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #d1d5db;
            transition: color 0.3s;
        }

        .close-btn:hover {
            color: #ef4444;
        }

        .register-btn {
            background: #b9da05;
            color: #011538;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .register-btn:hover {
            background: #9abc04;
            transform: translateY(-2px);
        }

        .cancel-btn {
            background: #b9da05;
            color: #011538;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .cancel-btn:hover {
            background: #9abc04;
            transform: translateY(-2px);
        }

        .footer-container {
            margin-top: auto;
        }

        footer {
            background: #00071c;
            text-align: center;
            padding: 1.5rem;
            border-top: 1px solid #374151;
        }

        footer .flex.justify-center.space-x-1.mb-1 img {
            height: 2.5rem;
        }

        footer .flex.justify-center.space-x-1.mb-1 img:last-child {
            height: 2.5rem;
            width: 2.5rem;
        }

        footer .text-gray-500 {
            color: #6b7280;
            font-size: 0.875rem;
        }

        footer .flex.justify-center.space-x-4.mt-2 a {
            color: #9ca3af;
            transition: color 0.3s;
        }

        footer .flex.justify-center.space-x-4.mt-2 a:hover {
            color: #b9da05;
        }

        .form-grid {
            display: flex;
            flex-wrap: wrap; /* ensures columns stack on smaller screens */
            gap: 20px;
            margin-top: 15px;
            width: 100%;
            box-sizing: border-box;
        }

        .left-col,
        .right-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-width: 0; /* prevents overflow when content is wide */
        }

        .modal-content form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            box-sizing: border-box;
            padding: 5px 5px 0 5px; /* small inner buffer */
        }

        /* Input and select field consistency */
        .modal-content input,
        .modal-content select,
        .modal-content textarea {
            width: 100%;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
            padding: 10px 14px;
            font-size: 0.95rem;
            box-sizing: border-box;
        }

        .form-grid input:focus,
        .form-grid select:focus,
        .form-grid textarea:focus {
            outline: none;
            border-color: #b9da05;
            box-shadow: 0 0 10px rgba(185, 218, 5, 0.4);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .form-actions button {
            background: #b9da05;
            color: #011538;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .form-actions button:hover {
            background: #9abc04;
            transform: translateY(-2px);
        }

        .form-grid select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.95rem;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg fill='white' height='16' viewBox='0 0 24 24' width='16' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 14px;
            padding-right: 36px;
        }

        /* This targets the dropdown list itself in Chromium browsers */
        .form-grid select option {
            background-color: #011538;
            color: #fff;
        }

        /* When hovering options (optional — improves contrast) */
        .form-grid select option:hover {
            background-color: #b9da05;
            color: #011538;
        }

        /* Optional: make it glow slightly when focused */
        .form-grid select:focus {
            outline: none;
            border-color: #b9da05;
            box-shadow: 0 0 10px rgba(185, 218, 5, 0.4);
        }

        .single-select {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 15px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Match input/select style from your reference */
        .single-select label {
            font-weight: 500;
            color: #ffffff;
            font-size: 0.95rem;
            margin-bottom: 5px;
        }

        .single-select select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 100%;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
            padding: 10px 14px;
            font-size: 0.95rem;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg fill='white' height='16' viewBox='0 0 24 24' width='16' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 14px;
            padding-right: 36px;
            transition: all 0.3s ease;
        }

        /* Dropdown options — consistent with other selects */
        .single-select select option {
            background-color: #011538;
            color: #fff;
        }

        /* Hover and focus effects */
        .single-select select option:hover {
            background-color: #b9da05;
            color: #011538;
        }

        .single-select select:focus {
            outline: none;
            border-color: #b9da05;
            box-shadow: 0 0 10px rgba(185, 218, 5, 0.4);
        }

        @media (max-width: 600px) {
            .form-grid {
                flex-direction: column;
                gap: 15px;
            }

            .left-col, .right-col {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                margin-top: 70px;
                padding: 40px 1rem;
            }
            
            .hero-section h1 {
                font-size: 1.75rem;
            }
            
            .hero-section p {
                font-size: 1rem;
            }
            
            .main-content {
                padding: 0 0.5rem;
            }
            
            .filter-container {
                margin-bottom: 25px;
                padding: 0 10px;
            }
            
            .filter-buttons {
                flex-direction: row;
                width: 100%;
                max-width: 350px;
                margin: 0 auto;
                padding: 6px;
            }
            
            .filter-btn {
                flex: 1;
                padding: 10px 12px;
                font-size: 13px;
                min-width: 100px;
            }
            
            .event-list {
                gap: 16px;
                padding: 0 10px 40px;
                max-width: 100%;
            }
            
            .nav-arrow {
                display: none;
            }
            
            .event-card {
                flex-direction: column;
                height: auto;
                min-height: auto;
                display: flex !important;
            }
            
            .event-image {
                width: 100%;
                height: 200px;
                padding: 20px;
            }
            
            .event-content {
                padding: 20px;
            }
            
            .event-card h3 {
                font-size: 1.5rem;
                margin-bottom: 8px;
            }
            
            .event-card .date {
                font-size: 1rem;
                margin-bottom: 8px;
            }
            
            .event-card .excerpt {
                font-size: 1rem;
                line-height: 1.5;
            }
            
            .modal-content {
                width: 95%;
                margin: 10px;
                padding: 20px;
                max-height: 90vh;
                overflow-y: auto;
            }
            
            .empty-state {
                padding: 40px 20px;
                margin: 0 10px;
            }
            
            .empty-state i {
                font-size: 3rem;
            }
            
            .empty-state h3 {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 480px) {
            .hero-section {
                margin-top: 60px;
                padding: 30px 0.75rem;
            }
            
            .hero-section h1 {
                font-size: 1.5rem;
            }
            
            .hero-section p {
                font-size: 0.9rem;
            }
            
            .filter-container {
                padding: 0 5px;
                margin-bottom: 20px;
            }
            
            .filter-buttons {
                max-width: 320px;
                padding: 4px;
            }
            
            .filter-btn {
                padding: 8px 10px;
                font-size: 12px;
                min-width: 90px;
            }
            
            .event-list {
                padding: 0 5px 30px;
            }
            
            .event-card {
                margin: 0 5px;
            }
            
            .event-image {
                height: 180px;
                padding: 15px;
            }
            
            .event-content {
                padding: 15px;
            }
            
            .event-card h3 {
                font-size: 1.25rem;
            }
            
            .modal-content {
                width: 98%;
                margin: 5px;
                padding: 15px;
            }
        }
    </style>
</head>
<body id="main-body">

    <div id="mobile-sidebar"
        class="fixed top-[64px] left-0 w-64 h-[calc(100%-64px)] bg-[#00071c] bg-opacity-100 backdrop-blur-md z-40 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
        <div class="p-6 space-y-4 text-white font-semibold">
            <a href="dashboard.html"
                class="flex items-center space-x-4 p-2 rounded-md group hover:bg-white/10 transition-colors duration-300 cursor-pointer">
                <i class="fa-solid fa-circle-info text-xl w-6 text-center group-hover:text-[#b9da05] group-hover:scale-110 transition-all duration-300"></i>
                <span>Home</span>
            </a>

            <a href="announcements.html"
                class="flex items-center space-x-4 p-2 rounded-md group hover:bg-white/10 transition-colors duration-300 cursor-pointer">
                <i class="fa-solid fa-bell text-xl w-6 text-center group-hover:text-[#b9da05] group-hover:scale-110 transition-all duration-300"></i>
                <span>Announcements</span>
            </a>

            <a href="events-and-register-events.html"
                class="flex items-center space-x-4 p-2 rounded-md group hover:bg-white/10 transition-colors duration-300 cursor-pointer">
                <i class="fa-solid fa-calendar-alt text-xl w-6 text-center group-hover:text-[#b9da05] group-hover:scale-110 transition-all duration-300"></i>
                <span>Events</span>
            </a>

            <a href="edit_profile.html"
                class="flex items-center space-x-4 p-2 rounded-md group hover:bg-white/10 transition-colors duration-300 cursor-pointer">
                <i class="fa-solid fa-user-circle text-xl w-6 text-center group-hover:text-[#b9da05] group-hover:scale-110 transition-all duration-300"></i>
                <span>Edit Profile</span>
            </a>

            <a href="calendar.html"
                class="flex items-center space-x-4 p-2 rounded-md group hover:bg-white/10 transition-colors duration-300 cursor-pointer">
                <i class="fa-solid fa-calendar-days text-xl w-6 text-center group-hover:text-[#b9da05] group-hover:scale-110 transition-all duration-300"></i>
                <span>Calendar</span>
            </a>

            <a href="settings.html"
                class="flex items-center space-x-4 p-2 rounded-md group hover:bg-white/10 transition-colors duration-300 cursor-pointer">
                <i class="fa-solid fa-gear text-xl w-6 text-center group-hover:text-[#b9da05] group-hover:scale-110 transition-all duration-300"></i>
                <span>Settings</span>
            </a>
        </div>
    </div>

    <div class="hero-section">
        <h1>BulSU MSC Events</h1>
        <p>Track your upcoming, completed, and past events</p>
    </div>

    <div class="main-content">
        <div class="filter-container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-section="upcomingSection">Upcoming</button>
                <button class="filter-btn" data-section="pastSection">Completed</button> 
                <button class="filter-btn" onclick="window.location.href='previous-activities.php'">Past Activities</button>
            </div>
        </div>

        <main class="flex-grow pt-28 p-3 flex justify-center">
            <section id="upcomingSection" class="event-section">
                <div class="event-list">
                </div>
            </section>

            <section id="pastSection" class="event-section" style="display:none;">
                <div class="event-list">
                </div>
            </section>
        </main>
    </div>

    <div class="modal" id="eventModal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2 id="modalTitle"></h2>
            <p class="date" id="modalDate"></p>
            <p id="modalContent"></p>
            <button id="registerBtn" class="register-btn" style="display:none;">Register Now</button>
        </div>
    </div>

    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="messageCloseBtn">&times;</span>
            <p id="messageText"></p>
        </div>
    </div>
    <?php include '_footer.php'; ?>
</div>
    <script>
    const API_BASE = "/updated-msc-website/api";

    const menuButton = document.getElementById("mobile-menu-button");
    const mobileSidebar = document.getElementById("mobile-sidebar");

    menuButton.addEventListener("click", () => {
        mobileSidebar.classList.toggle("-translate-x-full");
    });

    document.addEventListener("click", function (event) {
        const isClickInsideSidebar = mobileSidebar.contains(event.target);
        const isClickOnMenu = menuButton.contains(event.target);

        if (!isClickInsideSidebar && !isClickOnMenu) {
            mobileSidebar.classList.add("-translate-x-full");
        }
    });

    window.addEventListener('scroll', function() {
        const header = document.getElementById('main-header');
        if (window.scrollY > 50) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
    });

    async function apiCall(endpoint, method = "GET", data = null, title = "") {
        try {
            const options = {
                method: method,
                headers: { "Content-Type": "application/json" },
                credentials: "include",
            };
            if (data) options.body = JSON.stringify(data);

            const response = await fetch(`${API_BASE}${endpoint}`, options);
            const result = await response.json();
            return result;
        } catch (error) {
            console.error("API Error:", error);
            return null;
        }
    }

    function showEvent(index, sectionId) {
        const cards = document.querySelectorAll(`#${sectionId} .event-card`);
        if (!cards.length) return;

        if (index < 0) index = cards.length - 1;
        if (index >= cards.length) index = 0;

        cards.forEach(card => card.classList.remove("active"));
        cards[index].classList.add("active");

        document.getElementById(sectionId).dataset.currentIndex = index;
    }

    function prevEvent(sectionId) {
        const section = document.getElementById(sectionId);
        let currentIndex = parseInt(section.dataset.currentIndex || "0", 10);
        showEvent(currentIndex - 1, sectionId);
    }

    function nextEvent(sectionId) {
        const section = document.getElementById(sectionId);
        let currentIndex = parseInt(section.dataset.currentIndex || "0", 10);
        showEvent(currentIndex + 1, sectionId);
    }


    async function loadEvents() {
        try {
            const response = await apiCall("/events?page=1&limit=100", "GET");
            const events = response?.data?.events || [];
            if (!events.length) return;

            const today = new Date();
            const upcomingEvents = [];
            const completedEvents = [];
            const pastEvents = [];

            events.forEach(event => {
                const eventDate = new Date(event.event_date);

                if (event.event_status?.toLowerCase() === "upcoming") {
                    upcomingEvents.push(event);
                } else if (event.event_status?.toLowerCase() === "completed") {
                    completedEvents.push(event);
                } else if (eventDate < today) {
                    pastEvents.push(event);
                }
            });

            renderEvents(upcomingEvents, "upcomingSection");
            renderEvents(completedEvents, "completedSection");
            renderEvents(pastEvents, "pastSection");

            attachCardListeners();
        } catch (err) {
            console.error("Error loading events:", err);
        }
    }

    function renderEvents(eventsArray, sectionId) {
        const section = document.getElementById(sectionId).querySelector(".event-list");
        section.innerHTML = "";

        eventsArray.forEach((event, index) => {
            const card = document.createElement("div");
            card.classList.add("event-card");
            if (index === 0) card.classList.add("active");

            card.dataset.id = event.event_id;
            card.dataset.title = event.event_name;
            card.dataset.date = event.event_date;
            card.dataset.status = event.event_status;
            card.dataset.content = event.description;
            card.dataset.capacity = event.capacity || 0;
            card.dataset.registeredCount = event.attendants || 0;
            card.dataset.access = event.event_restriction || "public";

            card.innerHTML = `
                <div class="event-image">
                    ${event.event_batch_image   
                    ? `<img src="${event.event_batch_image}" alt="Event Badge" />`
                    : `<i class="bi bi-calendar-event"></i>`}
                </div>
                <div class="event-content">
                    <div>
                        <h3>${event.event_name}</h3>
                        <p class="date">${new Date(event.event_date).toLocaleDateString("en-US", { month: "long", day: "numeric", year: "numeric" })}</p>
                    </div>
                    <p class="excerpt">${event.description}</p>
                </div>
            `;

            card.addEventListener("click", () => {
                document.getElementById("modalTitle").textContent = card.dataset.title;
                document.getElementById("modalDate").textContent = card.dataset.date;
                document.getElementById("modalContent").textContent = card.dataset.content;

                const registerBtn = document.getElementById("registerBtn");
                registerBtn.dataset.eventId = card.dataset.id;
                registerBtn.style.display = (card.dataset.status.toLowerCase() === "upcoming") 
                    ? "inline-block" 
                    : "none";

                document.getElementById("eventModal").style.display = "flex";
            });

            section.appendChild(card);
        });

        if (eventsArray.length > 1) {
            
            const prevBtn = document.createElement("button");
            prevBtn.className = "nav-arrow prev";
            prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevBtn.onclick = () => prevEvent(sectionId);

            const nextBtn = document.createElement("button");
            nextBtn.className = "nav-arrow next";
            nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextBtn.onclick = () => nextEvent(sectionId);

            section.appendChild(prevBtn);
            section.appendChild(nextBtn);

            showEvent(0, sectionId);
        }
    }



    window.addEventListener("DOMContentLoaded", loadEvents);
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", async () => {
        try {
            const eventsData = await apiCall("/events?page=1&limit=100", "GET");
            const events = eventsData?.data?.events || [];

            events.forEach(event => {
                console.log(event.event_id, event.event_name, event.event_date, event.event_status);
            });

            if (!eventsData || !eventsData.data) {
                console.error("❌ No event data received");
                return;
            }

            const today = new Date();

            const pastEvents = [];
            const completedEvents = [];
            const upcomingEvents = [];

            events.forEach(event => {
                const now = new Date();
                const eventEnd = new Date(`${event.event_date}T${event.event_time_end}`);
                if (eventEnd > now) {
                    upcomingEvents.push(event);
                } else if (event.event_status && event.event_status.toLowerCase() === "completed") {
                    completedEvents.push(event);
                } else {
                    pastEvents.push(event);
                }
            });

            const pastCards = document.querySelectorAll("#pastSection .event-card");
            const completedCards = document.querySelectorAll("#completedSection .event-card");
            const upcomingCards = document.querySelectorAll("#upcomingSection .event-card");

            function updateCard(card, event) {
                card.dataset.id = event.event_id || "unknown";
                card.dataset.title = event.event_name || "Untitled Event";
                card.dataset.date = event.event_date || "Unknown Date";
                card.dataset.time = `${event.event_time_start || "???"} - ${event.event_time_end || "???"}`;
                card.dataset.location = event.location || "TBA";
                card.dataset.description = event.description || "No description available.";
                card.querySelector("h3").textContent = event.event_name || "Untitled Event";
                card.querySelector("p.date").textContent = event.event_date || "Unknown Date";
                card.querySelector("p.excerpt").textContent = event.description.substring(0, 50) + "...";
            }

            pastEvents.forEach((event, i) => {
                if (pastCards[i]) updateCard(pastCards[i], event);
            });

            completedEvents.forEach((event, i) => {
                if (completedCards[i]) updateCard(completedCards[i], event);
            });

            upcomingEvents.forEach((event, i) => {
                if (upcomingCards[i]) updateCard(upcomingCards[i], event);
            });

        } catch (err) {
            console.error("⚠ Error loading events:", err);
        }
    });
    </script>
    <script>
        function attachCardListeners() {
            const modal = document.getElementById("eventModal");
            const closeBtn = document.querySelector(".close-btn");
            const registerBtn = document.getElementById("registerBtn");

        document.querySelectorAll(".event-card").forEach(card => {
        card.addEventListener("click", async () => {
            const modal = document.getElementById("eventModal");
            const modalTitle = document.getElementById("modalTitle");
            const modalDate = document.getElementById("modalDate");
            const modalDesc = document.getElementById("modalContent");
            const registerBtn = document.getElementById("registerBtn");

            // Set event info
            modalTitle.textContent = card.dataset.title;
            modalDate.textContent = card.dataset.date;
            modalDesc.textContent = card.dataset.content;

            registerBtn.dataset.eventId = card.dataset.id;
            registerBtn.style.display = (card.dataset.status?.toLowerCase() === "upcoming") ? "inline-block" : "none";

            // ✅ Check login and registration status immediately
            let isRegistered = false;
            try {
                const authStatus = await apiCall("/auth/check-login", "GET");
                if (authStatus?.success && authStatus?.data?.logged_in) {
                    const regStatus = await apiCall(`/events/${card.dataset.id}/is-registered`, "GET");
                    if (regStatus?.success && regStatus?.data?.registered) {
                        isRegistered = true;
                        card.dataset.registered = "true";
                    }
                }
            } catch (err) {
                console.warn("Could not verify registration status:", err);
            }

            // ✅ Create cancel button if it doesn’t exist
            let cancelBtn = document.getElementById("cancelPreRegister");
            if (!cancelBtn) {
                cancelBtn = document.createElement("button");
                cancelBtn.id = "cancelPreRegister";
                cancelBtn.textContent = "Cancel Pre-Registration";
                cancelBtn.className = "register-btn"; // reuse button style
                modalContent.appendChild(cancelBtn);
            }

            // Always show the Cancel Pre-Register button if the user is registered
            cancelBtn.style.display = isRegistered ? "inline-block" : "none";

            // Cancel button logic (leave as-is)
            cancelBtn.onclick = async () => {
                const authStatus = await apiCall("/auth/check-login", "GET");
                if (!authStatus?.success || !authStatus?.data?.logged_in) {
                    showMessage('Please <a href="login.html" class="text-blue-500">log in</a> first.');
                    return;
                }
                const userId = authStatus.data.user_id;
                const result = await apiCall(`/events/${card.dataset.id}/cancel-pre-registration`, "POST", { user_id: userId });
                if (result?.success) {
                    showMessage(result.message || "✅ Registration canceled.");
                    cancelBtn.style.display = "none";
                    registerBtn.style.display = "inline-block";
                    card.dataset.registered = "false";
                } else {
                    showMessage(result?.message || "Cancellation failed.");
                }
            };

            modal.style.display = "flex";
        });
        });


            closeBtn.addEventListener("click", () => modal.style.display = "none");
            modal.addEventListener("click", e => { if (e.target === modal) modal.style.display = "none"; });

            registerBtn.addEventListener("click", async () => {
                const eventId = registerBtn.dataset.eventId;
                const eventCard = document.querySelector(`.event-card[data-id='${eventId}']`);
                if (!eventId || !eventCard) {
                    showMessage("⚠ Event not found.");
                    return;
                }

                // ✅ Check login
                const authStatus = await apiCall("/auth/check-login", "GET");
                const isLoggedIn = authStatus?.success && authStatus.data?.logged_in;

                // Access level map
                const accessMap = {
                    public: "open for public",
                    members: "members only",
                    bulsuans: "BulSUans only",
                    inviteOnly: "invite only"
                };
                const eventAccess = accessMap[eventCard.dataset.access] || "open for public";

                // Not logged in → show appropriate form
                if (!isLoggedIn) {
                    if (eventCard.dataset.access === "public") return showPreRegisterFormInsideModal(eventId);
                    if (eventCard.dataset.access === "bulsuans") return showBulSUPreRegisterForm(eventId);
                    if (eventCard.dataset.access === "members")
                        return showMessage('This event is for members only. Please <a href="login.html" class="text-blue-500">log in</a> to register.');
                    return showMessage(`🚫 This event is restricted to "${eventAccess}" users only.`);
                }

                // ✅ If user is logged in, continue with registration
                if (parseInt(eventCard.dataset.registeredCount) >= parseInt(eventCard.dataset.capacity)) {
                    showMessage("⚠ Sorry, this event is already full.");
                    return;
                }
                

                // ✅ Logged-in users
                let payload = {};

                try {
                    // Get user info
                    const userId = authStatus.data.user.id;
                    const studentRes = await apiCall(`/students/${userId}`, "GET");

                    if (studentRes.success && studentRes.data) {
                        const s = studentRes.data;
                        payload = {
                            first_name: s.first_name,
                            last_name: s.last_name,
                            middle_name: s.middle_name || "",
                            suffix: s.name_suffix || "",
                            gender: s.gender,
                            email: s.email,
                            phone: s.phone || "",
                            facebook: s.facebook_link || "",
                            student_id: s.student_no,
                            program: s.program || "",
                            college: s.college || "",
                            year_level: s.year_level || "",
                            section: s.section || "",
                            user_type: s.role || "bulsuan",
                        };
                    } else {
                        console.warn("Could not fetch student info — using fallback empty data.");
                    }
                } catch (err) {
                    console.error("Error fetching student profile:", err);
                }

                // Prevent empty data
                if (!payload.first_name || !payload.last_name || !payload.email) {
                    showMessage("⚠ Could not load your BulSU profile. Please re-login and try again.");
                    return;
                }

                // ✅ Send registration data
                const result = await apiCall(`/events/${eventId}/register`, "POST", payload);

                if (result?.success) {
                    showMessage(result.message || "Registered successfully!");
                    eventCard.dataset.registered = "true";
                } else {
                    showMessage(result?.message || "Registration failed.");
                }
            });


        function showPreRegisterFormInsideModal(eventId) {
            const modal = document.getElementById("eventModal");
            const modalContent = document.querySelector("#eventModal .modal-content");

            // ✅ Hide the original event info while the form is active
            const modalTitle = document.getElementById("modalTitle");
            const modalDate = document.getElementById("modalDate");
            const modalDesc = document.getElementById("modalContent");
            const registerBtn = document.getElementById("registerBtn");

            [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => {
                if (el) el.style.display = "none";
            });

            // ✅ Create form container
            const formContainer = document.createElement("div");
            formContainer.classList.add("pre-register-container");

            formContainer.innerHTML = `
                <h2>Pre-Register for Event</h2>
                <p class="subtitle">This event is open for public participants. Please fill out your information below:</p>

                <!-- 🆕 Participant Type field -->
                <div class="single-select">
                    <label>Participant Type*</label>
                    <select id="participantType" required>
                        <option value="" disabled selected>Select Type</option>
                        <option value="Guest">Guest</option>
                        <option value="BulSUan">BulSUan</option>
                    </select>
                </div>

                <div class="form-grid">
                    <div class="left-col">
                        <label>First Name*</label>
                        <input type="text" id="firstName" required>

                        <label>Last Name*</label>
                        <input type="text" id="lastName" required>

                        <label>Email*</label>
                        <input type="email" id="email" required>

                        <label>Gender*</label>
                        <select id="gender" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <div class="right-col">
                        <label>Middle Name</label>
                        <input type="text" id="middleName">

                        <label>Suffix</label>
                        <select id="suffix">
                            <option>None</option>
                            <option>Jr.</option>
                            <option>I</option>
                            <option>II</option>
                            <option>III</option>
                        </select>

                        <label>Phone Number</label>
                        <input type="text" id="phone">

                        <label>Facebook Profile Name</label>
                        <input type="text" id="facebook">
                    </div>
                </div>

                <div class="form-actions">
                    <button id="submitPreRegister">Submit</button>
                    <button id="cancelPreRegister">Cancel</button>
                </div>
            `;

            // ✅ Remove previous form (safety)
            const existingForm = document.querySelector(".pre-register-container");
            if (existingForm) existingForm.remove();

            modalContent.appendChild(formContainer);
            modal.style.display = "flex";

            // ✅ Cancel button: remove form + restore event details
            document.getElementById("cancelPreRegister").addEventListener("click", () => {
                formContainer.remove();
                [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => {
                    if (el) el.style.display = ""; // restore visibility
                });
            });

            // ✅ Submit logic
            document.getElementById("submitPreRegister").addEventListener("click", async () => {
                const data = {
                    first_name: document.getElementById("firstName").value,
                    last_name: document.getElementById("lastName").value,
                    middle_name: document.getElementById("middleName").value,
                    suffix: document.getElementById("suffix").value,
                    gender: document.getElementById("gender").value,
                    email: document.getElementById("email").value,
                    phone: document.getElementById("phone").value,
                    facebook: document.getElementById("facebook").value,
                    user_type: "guest",
                };

                if (!data.first_name || !data.last_name || !data.email || !data.gender) {
                    alert("Please fill in all required fields.");
                    return;
                }

                const result = await apiCall(`/events/${eventId}/register`, "POST", data);
                if (result && result.success) {
                    showMessage(result.message || "Pre-registration successful!");
                    formContainer.remove();
                    window.location.reload();
                } else {
                    showMessage(result?.message || "Pre-registration failed.");
                }
            });
        }

        function showBulSUPreRegisterForm(eventId) {
            const modal = document.getElementById("eventModal");
            const modalContent = document.querySelector("#eventModal .modal-content");

            // Hide the original event info
            const modalTitle = document.getElementById("modalTitle");
            const modalDate = document.getElementById("modalDate");
            const modalDesc = document.getElementById("modalContent");
            const registerBtn = document.getElementById("registerBtn");

            [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => {
                if (el) el.style.display = "none";
            });

            // Create form container
            const formContainer = document.createElement("div");
            formContainer.classList.add("pre-register-container");

            formContainer.innerHTML = `
                <h2>Pre-Register for BulSUans Only Event</h2>
                <p class="subtitle">This event is restricted to BulSU participants. Please fill out your information below:</p>

                <div class="form-grid">
                    <div class="left-col">
                        <label>First Name*</label>
                        <input type="text" id="firstName" required>

                        <label>Last Name*</label>
                        <input type="text" id="lastName" required>

                        <label>Email*</label>
                        <input type="email" id="email" required>

                        <label>Gender*</label>
                        <select id="gender" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <div class="right-col">
                        <label>Middle Name</label>
                        <input type="text" id="middleName">

                        <label>Suffix</label>
                        <select id="suffix">
                            <option>None</option>
                            <option>Jr.</option>
                            <option>I</option>
                            <option>II</option>
                            <option>III</option>
                        </select>

                        <label>Phone Number</label>
                        <input type="text" id="phone">

                        <label>Facebook Profile Name</label>
                        <input type="text" id="facebook">
                    </div>
                </div>

                <!-- BulSU Information -->
                <h3>BulSU Information</h3>
                <div class="form-grid">
                    <div class="left-col">
                        <label>Student/Employee ID*</label>
                        <input type="text" id="studentId" required>

                        <label>Program*</label>
                        <input type="text" id="program" required>
                    </div>
                    <div class="right-col">
                        <label>College*</label>
                        <input type="text" id="college" required>

                        <label>Year Level*</label>
                        <select id="yearLevel" required>
                            <option value="" disabled selected>Select Year Level</option>
                            <option>1st year</option>
                            <option>2nd year</option>
                            <option>3rd year</option>
                            <option>4th year</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <label>Section</label>
                    <input type="text" id="section">
                </div>

                <div class="form-actions">
                    <button id="submitBulSUPreRegister">Submit</button>
                    <button id="cancelBulSUPreRegister">Cancel</button>
                </div>
            `;

            // Remove any previous form
            const existingForm = document.querySelector(".pre-register-container");
            if (existingForm) existingForm.remove();

            modalContent.appendChild(formContainer);
            modal.style.display = "flex";

            // Cancel button
            document.getElementById("cancelBulSUPreRegister").addEventListener("click", () => {
                formContainer.remove();
                [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => {
                    if (el) el.style.display = "";
                });
            });

            // Submit logic
            document.getElementById("submitBulSUPreRegister").addEventListener("click", async () => {
                const data = {
                    first_name: document.getElementById("firstName").value,
                    last_name: document.getElementById("lastName").value,
                    middle_name: document.getElementById("middleName").value,
                    suffix: document.getElementById("suffix").value,
                    gender: document.getElementById("gender").value,
                    email: document.getElementById("email").value,
                    phone: document.getElementById("phone").value,
                    facebook: document.getElementById("facebook").value,
                    student_id: document.getElementById("studentId").value,
                    program: document.getElementById("program").value,
                    college: document.getElementById("college").value,
                    year_level: document.getElementById("yearLevel").value,
                    section: document.getElementById("section").value,
                    user_type: "bulsuan",
                };

                // Required field validation
                if (!data.first_name || !data.last_name || !data.email || !data.gender || !data.student_id || !data.program || !data.college || !data.year_level) {
                    alert("Please fill in all required fields.");
                    return;
                }

                const result = await apiCall(`/events/${eventId}/register`, "POST", data);
                if (result && result.success) {
                    showMessage(result.message || "Pre-registration successful!");
                    formContainer.remove();
                    window.location.reload();
                } else {
                    showMessage(result?.message || "Pre-registration failed.");
                }
            });
        }

async function handleMembersOnlyRegistration(eventId) {
    const modal = document.getElementById("eventModal");
    const modalContent = document.querySelector("#eventModal .modal-content");
    const modalTitle = document.getElementById("modalTitle");
    const modalDate = document.getElementById("modalDate");
    const modalDesc = document.getElementById("modalContent");
    let registerBtn = document.getElementById("registerBtn");

    // Show event info
    [modalTitle, modalDate, modalDesc].forEach(el => {
        if (el) el.style.display = "";
    });

    // Setup Register button (logic intact)
    registerBtn.style.display = "inline-block";
    registerBtn.textContent = "Register Now";
    registerBtn.dataset.eventId = eventId;

    const newRegisterBtn = registerBtn.cloneNode(true);
    registerBtn.parentNode.replaceChild(newRegisterBtn, registerBtn);
    registerBtn = newRegisterBtn;

    registerBtn.onclick = async () => {
        const authStatus = await apiCall("/auth/check-login", "GET");
        if (!authStatus?.success || !authStatus?.data?.logged_in) {
            showMessage('Please <a href="login.html" class="text-blue-500">log in</a> first to register.');
            return;
        }

        const result = await apiCall(`/events/${eventId}/register`, "POST", {}, `Register for Event #${eventId}`);
        if (result?.success) {
            showMessage(result.message || "✅ Registered successfully!");
        } else {
            showMessage(result?.message || "Registration failed.");
        }
    };

    // --- Always show Cancel Pre-Register button ---
    let cancelPreBtn = document.getElementById("cancelPreRegister");
    if (!cancelPreBtn) {
        cancelPreBtn = document.createElement("button");
        cancelPreBtn.id = "cancelPreRegister";
        cancelPreBtn.textContent = "Cancel Pre-Registration";
        cancelPreBtn.className = "register-btn"; // reuse button style
        modalContent.appendChild(cancelPreBtn);
    }

    // **Display by default**
    cancelPreBtn.style.display = "inline-block";

    // Cancel button logic (leave as-is)
    cancelPreBtn.onclick = async () => {
        const authStatus = await apiCall("/auth/check-login", "GET");
        if (!authStatus?.success || !authStatus?.data?.logged_in) {
            showMessage('Please <a href="login.html" class="text-blue-500">log in</a> first.');
            return;
        }

        const userId = authStatus.data.user_id;

        const result = await apiCall(`/events/${eventId}/cancel-pre-registration`, "POST", { user_id: userId });
        if (result?.success) {
            showMessage(result.message || "✅ Registration canceled.");
        } else {
            showMessage(result?.message || "Cancellation failed.");
        }
    };

    modal.style.display = "flex";
}




            function showMessage(msg) {
                const eventModal = document.getElementById("eventModal");
                eventModal.style.display = "none";

                const messageModal = document.getElementById("messageModal");
                const messageText = document.getElementById("messageText");
                messageText.innerHTML = msg;
                messageModal.style.display = "flex";

                const closeBtn = document.getElementById("messageCloseBtn");
                closeBtn.onclick = () => messageModal.style.display = "none";

                messageModal.onclick = e => {
                    if (e.target === messageModal) messageModal.style.display = "none";
                };
            }

            function checkEventStatus() {
                const now = new Date();

                document.querySelectorAll(".event-card[data-registered='true']").forEach(card => {
                    const eventEnd = new Date(`${card.dataset.date}T${card.dataset.time.split(' - ')[1]}`);
                    if (now > eventEnd) {
                        const completedSection = document.querySelector("#completedSection .event-list");
                        completedSection.appendChild(card);

                        card.dataset.status = "completed";
                    }
                });
            }

            setInterval(checkEventStatus, 30000);

            document.querySelectorAll(".filter-btn").forEach(btn => {
                btn.addEventListener("click", () => {
                    document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
                    btn.classList.add("active");

                    document.querySelectorAll(".event-section").forEach(section => {
                        section.style.display = "none";
                    });
                    
                    const targetSection = document.getElementById(btn.dataset.section);
                    if (targetSection) {
                        targetSection.style.display = "block";
                    }
                });
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            attachCardListeners();
        });

    </script>

</body>
</html>
