<?php include '_header.php'; ?>
<style>
    /* Grid Layout Styles */
    .event-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        padding: 2rem 1.5rem;
        max-width: 100%;
        width: 100%;
        margin: 0;
        box-sizing: border-box;
    }

    @media (max-width: 768px) {
        .event-list {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 1rem;
        }
    }

    .event-card {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(185, 218, 5, 0.2);
        cursor: pointer;
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 450px;
        width: 100%;
    }

    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(185, 218, 5, 0.4);
        border-color: rgba(185, 218, 5, 0.6);
    }

    .event-image {
        width: 100%;
        height: 320px;
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .event-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .event-image i {
        font-size: 5.5rem;
        color: #b9da05;
    }

    .event-content {
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.3rem;
        flex-grow: 1;
    }

    .event-content h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #b9da05;
        margin: 0;
        line-height: 1.3;
    }

    .event-content .date {
        color: #ffffff;
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 500;
    }

    .event-content .excerpt {
        color: #ffffff;
        opacity: 0.75;
        line-height: 1.7;
        font-size: 1.1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Remove arrow button styles since we're not using them */
    .nav-arrow {
        display: none;
    }

    /* Main content area */
    .main-content {
        min-height: 60vh;
        padding: 2rem 0;
        width: 100%;
        max-width: 100%;
    }

    .event-section {
        width: 100%;
        max-width: 100%;
        padding: 0;
    }

    main.flex-grow {
        width: 100%;
        max-width: 100%;
        padding: 0 !important;
    }

    /* Filter buttons */
    .filter-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .filter-btn {
        padding: 0.75rem 2rem;
        background: #1a1a2e;
        color: white;
        border: 2px solid rgba(185, 218, 5, 0.3);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .filter-btn:hover {
        background: rgba(185, 218, 5, 0.1);
        border-color: #b9da05;
    }

    .filter-btn.active {
        background: #b9da05;
        color: #000;
        border-color: #b9da05;
    }

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {

        /* Stack filter buttons vertically */
        .filter-buttons {
            flex-direction: column;
            align-items: stretch;
            padding: 0 1rem;
        }

        .filter-btn {
            width: 100%;
            text-align: center;
            padding: 1rem;
        }

        /* Adjust event cards for mobile */
        .event-card {
            min-height: 400px;
        }

        .event-image {
            height: 250px;
        }

        .event-content {
            padding: 1.5rem;
        }

        .event-content h3 {
            font-size: 1.5rem;
        }

        .event-content .date,
        .event-content .excerpt {
            font-size: 1rem;
        }
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }

    .modal-content {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        padding: 0;
        border-radius: 16px;
        max-width: 800px;
        width: 90%;
        max-height: 85vh;
        display: grid;
        grid-template-columns: 1fr 1fr;
        position: relative;
        border: 2px solid rgba(185, 218, 5, 0.3);
        overflow: hidden;
    }

    .modal-content:has(.pre-register-container) {
        grid-template-columns: 1fr;
        max-width: 700px;
        overflow-y: auto;
    }

    .modal-image {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .modal-content:has(.pre-register-container) .modal-image {
        display: none;
    }

    .modal-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .modal-image i {
        font-size: 5rem;
        color: #b9da05;
    }

    .modal-body {
        padding: 2rem;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .modal-content h2 {
        color: #b9da05;
        font-size: 1.6rem;
        margin: 0 0 0.5rem 0;
        line-height: 1.3;
    }

    .modal-content .date {
        color: #ffffff;
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 1rem;
        display: block;
    }

    .modal-content p {
        color: #ffffff;
        line-height: 1.6;
        font-size: 0.95rem;
        margin-bottom: 1rem;
        flex-grow: 1;
    }

    .close-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 1.5rem;
        color: #b9da05;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
        background: rgba(0, 0, 0, 0.7);
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-btn:hover {
        transform: rotate(90deg);
        background: rgba(0, 0, 0, 0.9);
    }

    .register-btn {
        background: #b9da05;
        color: #000;
        padding: 0.7rem 1.8rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        align-self: flex-start;
        font-size: 0.95rem;
    }

    .register-btn:hover {
        background: #a0c005;
        transform: scale(1.05);
    }

    .register-btn.cancel {
    background: #dc3545 !important;
    color: white !important;
    }

    .register-btn.cancel:hover {
        background: #c82333 !important;
        transform: translateY(-2px);
    }

    /* Make form container scrollable if needed */
    .pre-register-container {
        color: white;
        padding: 3rem;
        grid-column: 1 / -1;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%);
        min-height: 100%;
    }

    .pre-register-container h2 {
        color: #b9da05;
        margin-bottom: 0.5rem;
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
    }

    .pre-register-container h3 {
        color: #b9da05;
        font-size: 1.3rem;
        margin: 2rem 0 1rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(185, 218, 5, 0.3);
    }

    .pre-register-container .subtitle {
        margin-bottom: 2rem;
        opacity: 0.85;
        text-align: center;
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .form-section {
        background: rgba(255, 255, 255, 0.05);
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(185, 218, 5, 0.2);
    }

    @media (max-width: 968px) {
        .modal-content {
            grid-template-columns: 1fr;
            max-width: 600px;
            max-height: 90vh;
        }

        .modal-image {
            height: 300px;
            padding-bottom: 0;
            position: relative;
        }

        .modal-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }

    @media (max-width: 768px) {
        .modal {
            padding: 1rem;
        }

        .modal-content {
            max-height: 95vh;
            width: 95%;
            overflow-y: auto;
        }

        .modal-image {
            height: 250px;
            padding-bottom: 0;
            position: relative;
            min-height: 250px;
        }

        .modal-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-image i {
            font-size: 4rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-content h2 {
            font-size: 1.3rem;
        }

        .modal-content p {
            font-size: 0.9rem;
        }

        .close-btn {
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            width: 35px;
            height: 35px;
        }

        .register-btn {
            padding: 0.7rem 1.8rem;
            font-size: 0.95rem;
        }

        /* Pre-register form styles */
        .pre-register-container {
            color: white;
            padding: 2rem;
            max-height: 85vh;
            overflow-y: auto;
        }

        .pre-register-container h2 {
            color: #b9da05;
            margin-bottom: 1rem;
        }

        .pre-register-container .subtitle {
            margin-bottom: 1.5rem;
            opacity: 0.8;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 0;
        }

        .form-field {
            display: flex;
            flex-direction: column;
        }

        .form-grid label,
        .form-field label {
            display: block;
            margin-bottom: 0.5rem;
            color: #b9da05;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-grid input,
        .form-grid select,
        .form-field input,
        .form-field select {
            width: 100%;
            padding: 0.875rem;
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(185, 218, 5, 0.2);
            border-radius: 10px;
            color: white;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-grid input:focus,
        .form-grid select:focus,
        .form-field input:focus,
        .form-field select:focus {
            outline: none;
            border-color: #b9da05;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 3px rgba(185, 218, 5, 0.1);
        }

        .form-grid input::placeholder,
        .form-field input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .pre-register-container {
                padding: 2rem 1.5rem;
                max-height: 90vh;
            }

            .pre-register-container h2 {
                font-size: 1.5rem;
            }

            .pre-register-container h3 {
                font-size: 1.1rem;
            }

            .form-section {
                padding: 1rem;
            }

            .form-actions {
                flex-direction: column;
                gap: 0.75rem;
            }

            .form-actions button {
                width: 100%;
                padding: 1rem;
            }
        }

        .single-select {
            margin-bottom: 2rem;
        }

        .single-select label {
            display: block;
            margin-bottom: 0.5rem;
            color: #b9da05;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .single-select select {
            width: 100%;
            padding: 0.875rem;
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(185, 218, 5, 0.2);
            border-radius: 10px;
            color: white;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .single-select select:focus {
            outline: none;
            border-color: #b9da05;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 3px rgba(185, 218, 5, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(185, 218, 5, 0.2);
        }

        .form-actions button {
            padding: 0.875rem 2.5rem;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        #submitPreRegister,
        #submitBulSUPreRegister {
            background: linear-gradient(135deg, #b9da05 0%, #a0c005 100%);
            color: #000;
            box-shadow: 0 4px 15px rgba(185, 218, 5, 0.3);
        }

        #submitPreRegister:hover,
        #submitBulSUPreRegister:hover {
            background: linear-gradient(135deg, #a0c005 0%, #8ab004 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(185, 218, 5, 0.4);
        }

        #cancelPreRegister,
        #cancelBulSUPreRegister {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        #cancelPreRegister:hover,
        #cancelBulSUPreRegister:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
</style>

<!-- Rest of your HTML remains exactly the same -->
<div class="flex flex-col min-h-screen">

    <div class="mt-20 px-5 py-20 text-center max-w-7xl mx-auto mb-0 relative">
        <h1 class="text-4xl sm:text-5xl font-extrabold text-[#b9da05] mb-4">Events</h1>
        <p class="text-[1.2rem] text-white/90 relative z-[1]">Track your upcoming, completed, and past events</p>
    </div>

    <div class="main-content">
        <div class="flex justify-center mb-10">
            <div class="flex flex-wrap justify-center gap-4">
                <button class="filter-btn active" data-section="upcomingSection">Upcoming</button>
                <button class="filter-btn" data-section="pastSection">Completed</button>
                <button class="filter-btn" onclick="window.location.href='previous-activities.php'">Past Activities</button>
            </div>
        </div>

        <main class="flex-grow pt-10 p-3 flex justify-center">
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
            <div class="modal-image" id="modalImage">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="modal-body">
                <h2 id="modalTitle"></h2>
                <p class="date" id="modalDate"></p>
                <p id="modalContent"></p>
                <button id="registerBtn" class="register-btn" style="display:none;">Register Now</button>
            </div>
        </div>
    </div>

    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="messageCloseBtn">&times;</span>
            <div class="modal-body">
                <p id="messageText"></p>
            </div>
        </div>
    </div>
</div>

<!-- All your existing scripts remain exactly the same -->
<script>
    const API_BASE = "/updated-msc-website/api";

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
                headers: {
                    "Content-Type": "application/json"
                },
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

    async function loadEvents() {
        try {
            const response = await apiCall("/events?page=1&limit=100", "GET");
            const events = response?.data?.events || [];
            if (!events.length) return;

            const now = new Date();
            const upcomingEvents = [];
            const completedEvents = [];
            const pastEvents = [];

            events.forEach(event => {
                // Create a Date object for the event's end time
                const eventEndDateTime = new Date(`${event.event_date}T${event.event_time_end}`);

                // Check if event has ended (current time is after event end time)
                if (now > eventEndDateTime) {
                    completedEvents.push(event);
                } else {
                    upcomingEvents.push(event);
                }
            });

            renderEvents(upcomingEvents, "upcomingSection");
            renderEvents(completedEvents, "pastSection"); // Note: using "pastSection" as completed section

            attachCardListeners();

            // Start checking for event status changes every minute
            startEventStatusChecker();
        } catch (err) {
            console.error("Error loading events:", err);
        }
    }

    // Function to check and update event status in real-time
    function startEventStatusChecker() {
        setInterval(() => {
            updateEventStatuses();
        }, 60000); // Check every minute
    }

    function updateEventStatuses() {
        const now = new Date();
        const upcomingSection = document.getElementById("upcomingSection").querySelector(".event-list");
        const completedSection = document.getElementById("pastSection").querySelector(".event-list");

        const upcomingCards = Array.from(upcomingSection.querySelectorAll(".event-card"));

        upcomingCards.forEach(card => {
            const eventDate = card.dataset.date;
            const eventTimeEnd = card.dataset.time ? card.dataset.time.split(' - ')[1] : '23:59';

            // Create Date object for event end time
            const eventEndDateTime = new Date(`${eventDate}T${eventTimeEnd}`);

            // If event has ended, move it to completed section
            if (now > eventEndDateTime) {
                // Remove from upcoming
                card.remove();

                // Update card status
                card.dataset.status = "completed";

                // Hide register button if it exists in modal data
                const registerBtn = document.getElementById("registerBtn");
                if (registerBtn && registerBtn.dataset.eventId === card.dataset.id) {
                    registerBtn.style.display = "none";
                }

                // Add to completed section
                completedSection.appendChild(card);

                console.log(`Moved event "${card.dataset.title}" to completed section`);
            }
        });
    }

    // Update the renderEvents function to include time data
    function renderEvents(eventsArray, sectionId) {
        const section = document.getElementById(sectionId).querySelector(".event-list");
        section.innerHTML = "";

        eventsArray.forEach((event, index) => {
            const card = document.createElement("div");
            card.classList.add("event-card");

            card.dataset.id = event.event_id;
            card.dataset.title = event.event_name;
            card.dataset.date = event.event_date;
            card.dataset.time = `${event.event_time_start || "00:00"} - ${event.event_time_end || "23:59"}`;
            card.dataset.status = event.event_status;
            card.dataset.content = event.description;
            card.dataset.capacity = event.capacity || 0;
            card.dataset.registeredCount = event.attendants || 0;
            card.dataset.access = event.event_restriction || "public";
            card.dataset.image = event.event_batch_image || "";

            let imgPath = event.event_batch_image || "";
            if (imgPath.startsWith("/updated-msc-website")) {
                imgPath = imgPath.replace("/updated-msc-website", "");
            }
            const fullImgPath = imgPath ? `${window.location.origin}${imgPath}` : null;

            card.innerHTML = `
            <div class="event-image">
                ${fullImgPath
                    ? `<img src="${fullImgPath}" alt="Event Badge" />`
                    : `<i class="bi bi-calendar-event"></i>`}
            </div>
            <div class="event-content">
                <div>
                    <h3>${event.event_name}</h3>
                    <p class="date">${new Date(event.event_date).toLocaleDateString("en-US", { month: "long", day: "numeric", year: "numeric" })}</p>
                    <p class="date">${event.event_time_start || "TBA"} - ${event.event_time_end || "TBA"}</p>
                </div>
                <p class="excerpt">${event.description}</p>
            </div>
            `;


            card.addEventListener("click", () => {
                document.getElementById("modalTitle").textContent = card.dataset.title;
                document.getElementById("modalDate").textContent = `${card.dataset.date} • ${card.dataset.time}`;
                document.getElementById("modalContent").textContent = card.dataset.content;

                // Update modal image
                const modalImage = document.getElementById("modalImage");
                if (card.dataset.image) {
                    //modalImage.innerHTML = `<img src="${window.location.origin + card.dataset.image.replace('/updated-msc-website', '')} alt="Event Image" />`;
                    let imgPath = card.dataset.image;
                    if (imgPath.startsWith("/updated-msc-website")) {
                        imgPath = imgPath.replace("/updated-msc-website", "");
                    }
                    modalImage.innerHTML = `<img src="${window.location.origin + imgPath}" alt="Event Image" />`;
                } else {
                    modalImage.innerHTML = `<i class="bi bi-calendar-event"></i>`;
                }

                const registerBtn = document.getElementById("registerBtn");
                registerBtn.dataset.eventId = card.dataset.id;
                // Check if event has ended to determine if registration should be available
                const now = new Date();
                const eventEndDateTime = new Date(`${card.dataset.date}T${card.dataset.time.split(' - ')[1]}`);
                const isEventEnded = now > eventEndDateTime;

                registerBtn.style.display = (card.dataset.status.toLowerCase() === "upcoming" && !isEventEnded) ?
                    "inline-block" :
                    "none";

                document.getElementById("eventModal").style.display = "flex";
            });

            section.appendChild(card);
        });
    }

    window.addEventListener("DOMContentLoaded", loadEvents);
</script>

<!-- Keep all your existing scripts below -->
<script>
    document.addEventListener("DOMContentLoaded", async () => {
        try {
            const eventsData = await apiCall("/events?page=1&limit=100", "GET");
            const events = eventsData?.data?.events || [];

            events.forEach(event => {
                console.log(event.event_id, event.event_name, event.event_date, event.event_status, event.event_batch_image);
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

<!-- Keep all remaining scripts exactly as they were -->
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

                modalTitle.textContent = card.dataset.title;
                modalDate.textContent = card.dataset.date;
                modalDesc.textContent = card.dataset.content;

                registerBtn.dataset.eventId = card.dataset.id;
                
                // Check if event has ended to determine if registration should be available
                const now = new Date();
                const eventEndDateTime = new Date(`${card.dataset.date}T${card.dataset.time.split(' - ')[1]}`);
                const isEventEnded = now > eventEndDateTime;

                // Hide register button initially
                registerBtn.style.display = "none";

                // Hide any existing cancel button
                const existingCancelBtn = document.getElementById("cancelPreRegister");
                if (existingCancelBtn) {
                    existingCancelBtn.style.display = "none";
                }

                const authStatus = await apiCall("/auth/check-login", "GET");
                const isLoggedIn = authStatus?.success && authStatus?.data?.logged_in;

                // Logic for different user types and event restrictions
                if (isLoggedIn) {
                    // LOGIC 2: Member/officer logic
                    try {
                        const userEmail = authStatus.data.user.email;
                        
                        // Check registration by email
                        const emailCheck = await checkEmailRegistration(card.dataset.id, userEmail);
                        
                        if (emailCheck.registered) {
                            // User is registered - show cancel button
                            showCancelButtonForMember(card.dataset.id, userEmail, card);
                            card.dataset.registered = "true";
                        } else {
                            // User is not registered - show register button if event is upcoming
                            if (card.dataset.status?.toLowerCase() === "upcoming" && !isEventEnded) {
                                registerBtn.style.display = "inline-block";
                                registerBtn.textContent = "Register Now";
                            }
                            card.dataset.registered = "false";
                        }
                    } catch (err) {
                        console.warn("Could not verify registration status:", err);
                        if (card.dataset.status?.toLowerCase() === "upcoming" && !isEventEnded) {
                            registerBtn.style.display = "inline-block";
                        }
                    }
                } else {
                    // Non-member logic - we'll check email registration when they try to register
                    if (card.dataset.status?.toLowerCase() === "upcoming" && !isEventEnded) {
                        registerBtn.style.display = "inline-block";
                        registerBtn.textContent = "Register Now";
                    }
                }

                // Update modal image
                const modalImage = document.getElementById("modalImage");
                if (card.dataset.image) {
                    let imgPath = card.dataset.image;
                    if (imgPath.startsWith("/updated-msc-website")) {
                        imgPath = imgPath.replace("/updated-msc-website", "");
                    }
                    modalImage.innerHTML = `<img src="${window.location.origin + imgPath}" alt="Event Image" />`;
                } else {
                    modalImage.innerHTML = `<i class="bi bi-calendar-event"></i>`;
                }

                modal.style.display = "flex";
            });
        });

    // Existing close button and modal click handlers...
    closeBtn.addEventListener("click", () => modal.style.display = "none");
    modal.addEventListener("click", e => {
        if (e.target === modal) modal.style.display = "none";
    });

    const newRegisterBtn = registerBtn.cloneNode(true);
    registerBtn.parentNode.replaceChild(newRegisterBtn, registerBtn);

    // Add fresh event listener
    newRegisterBtn.addEventListener("click", handleRegisterButtonClick, { once: false });

    async function handleRegisterButtonClick() {
        const eventId = this.dataset.eventId;
        const eventCard = document.querySelector(`.event-card[data-id='${eventId}']`);
        if (!eventId || !eventCard) {
            showMessage("⚠ Event not found.");
            return;
        }

        const authStatus = await apiCall("/auth/check-login", "GET");
        const isLoggedIn = authStatus?.success && authStatus?.data?.logged_in;

        // If user is logged in, prevent double registration by disabling button temporarily
        if (isLoggedIn) {
            this.disabled = true;
            this.textContent = "Processing...";
        }

        const accessMap = {
            public: "open for public",
            members: "members only",
            bulsuans: "BulSUans only",
            inviteOnly: "invite only"
        };
        const eventAccess = accessMap[eventCard.dataset.access] || "open for public";

         // LOGIC 2: For members, if event is "members only" or "bulsuans only" or "public"
        if (isLoggedIn) {
            try {
                const userEmail = authStatus.data.user.email;
                // Check if already registered by email (more reliable)
                const emailCheck = await checkEmailRegistration(eventId, userEmail);
                
                if (emailCheck.registered) {
                    showMessage(`You are already registered for this event as a ${emailCheck.participant_type}.`);
                    
                    // Hide register button and show cancel button
                    this.style.display = "none";
                    showCancelButtonForMember(eventId, userEmail, eventCard);
                    
                    // Re-enable button
                    this.disabled = false;
                    this.textContent = "Register Now";
                    return;
                }
            } catch (err) {
                console.warn("Could not verify registration status:", err);
            }

            if (parseInt(eventCard.dataset.registeredCount) >= parseInt(eventCard.dataset.capacity)) {
                showMessage("⚠ Sorry, this event is already full.");
                this.disabled = false;
                this.textContent = "Register Now";
                return;
            }

            let payload = {};
            const userId = authStatus.data.user.id;

            try {
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
                        user_type: s.role || "member",
                    };
                }
            } catch (err) {
                console.error("Error fetching student profile:", err);
            }

            if (!payload.first_name || !payload.last_name || !payload.email) {
                showMessage("⚠ Could not load your BulSU profile. Please re-login and try again.");
                this.disabled = false;
                this.textContent = "Register Now";
                return;
            }

            const result = await apiCall(`/events/${eventId}/register`, "POST", payload);

            // Re-enable button regardless of result
            this.disabled = false;
            this.textContent = "Register Now";

            if (result?.success) {
                if (result.data?.duplicate) {
                    // LOGIC 1: Show cancel button for duplicate registration
                    showMessage(`You are already registered for this event with email: <strong>${result.data.email}</strong>. 
                        <br><br>
                        <button id="cancelByEmailBtn" class="register-btn" style="background: #dc3545; margin-top: 10px;">
                            Cancel Pre-Registration
                        </button>`);
                    
                    document.getElementById("cancelByEmailBtn").addEventListener("click", async () => {
                        const cancelResult = await apiCall(`/events/${eventId}/cancel-by-email`, "POST", { 
                            email: result.data.email 
                        });
                        if (cancelResult?.success) {
                            showMessage("Your pre-registration has been cancelled.");
                            // Update event card data
                            const currentCount = parseInt(eventCard.dataset.registeredCount) || 0;
                            if (currentCount > 0) {
                                eventCard.dataset.registeredCount = (currentCount - 1).toString();
                            }
                            // Refresh the modal
                            document.getElementById("eventModal").style.display = "none";
                        } else {
                            showMessage(cancelResult?.message || "Failed to cancel pre-registration.");
                        }
                    });
                } else {
                    showMessage(result.message || "Registered successfully!");
                    eventCard.dataset.registered = "true";
                    
                    // After successful registration, show cancel button for member
                    const userEmail = authStatus.data.user.email;
                    showCancelButtonForMember(eventId, userEmail, eventCard);
                    
                    // Generate QR code for member if available
                    if (result.data && result.data.qr_code) {
                        generateAndDownloadQRCode(result.data.qr_code, eventId, payload);
                    }
                }
            } else {
                showMessage(result?.message || "Registration failed.");
            }
            return;
        }

        if (!isLoggedIn) {
            if (eventCard.dataset.access === "public") return showPreRegisterFormInsideModal(eventId);
            if (eventCard.dataset.access === "bulsuans") return showBulSUPreRegisterForm(eventId);
            if (eventCard.dataset.access === "members")
                return showMessage('This event is for members only. Please <a href="login.php" class="text-blue-500">log in</a> to register.');
            return showMessage(`🚫 This event is restricted to "${eventAccess}" users only.`);
        }

        // For logged-in members - check if already registered
        try {
            const userId = authStatus.data.user.id || authStatus.data.user_id;
            const regStatus = await apiCall(`/events/${eventId}/is-registered?user_id=${userId}`, "GET");
            
            if (regStatus?.success && regStatus?.data?.registered) {
                showMessage("You are already registered for this event.");
                // Re-enable button
                this.disabled = false;
                this.textContent = "Register Now";
                return;
            }
        } catch (err) {
            console.warn("Could not verify registration status:", err);
        }

        if (parseInt(eventCard.dataset.registeredCount) >= parseInt(eventCard.dataset.capacity)) {
            showMessage("⚠ Sorry, this event is already full.");
            // Re-enable button
            this.disabled = false;
            this.textContent = "Register Now";
            return;
        }

        let payload = {};

        try {
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

        if (!payload.first_name || !payload.last_name || !payload.email) {
            showMessage("⚠ Could not load your BulSU profile. Please re-login and try again.");
            // Re-enable button
            this.disabled = false;
            this.textContent = "Register Now";
            return;
        }

        const result = await apiCall(`/events/${eventId}/register`, "POST", payload);

        // Re-enable button regardless of result
        this.disabled = false;
        this.textContent = "Register Now";

        if (result?.success) {
            showMessage(result.message || "Registered successfully!");
            eventCard.dataset.registered = "true";
            
            // After successful registration, show cancel button for member
            const userId = authStatus.data.user.id || authStatus.data.user_id;
            showCancelButtonForMember(eventId, userId);
            
            // Generate QR code for member if available
            if (result.data && result.data.qr_code) {
                generateAndDownloadQRCode(result.data.qr_code, eventId, payload);
            }
        } else {
            showMessage(result?.message || "Registration failed.");
        }
    }

    function generateAndDownloadQRCode(qrData, eventId, userData) {
        // Create a temporary container for QR generation
        const tempDiv = document.createElement("div");
        tempDiv.style.position = "absolute";
        tempDiv.style.left = "-9999px";
        document.body.appendChild(tempDiv);

        // Generate QR code
        new QRCode(tempDiv, {
            text: qrData,
            width: 200,
            height: 200,
            colorDark: "#06047b",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H,
        });

        // Wait for QR code to fully render
        setTimeout(() => {
            const canvas = tempDiv.querySelector("canvas");
            const img = tempDiv.querySelector("img");

            const padding = 20;

            if (canvas) {
                // Create a new canvas with extra padding
                const paddedCanvas = document.createElement("canvas");
                const size = canvas.width + padding * 2;
                paddedCanvas.width = size;
                paddedCanvas.height = size;

                const ctx = paddedCanvas.getContext("2d");
                ctx.fillStyle = "#ffffff";
                ctx.fillRect(0, 0, size, size);
                ctx.drawImage(canvas, padding, padding);

                const url = paddedCanvas.toDataURL("image/png");
                const a = document.createElement("a");
                const identifier = userData.student_id || `${userData.first_name}-${userData.last_name}`;
                a.href = url;
                a.download = `Event-${eventId}-${identifier}-QR.png`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

                console.log("QR code download triggered successfully for member");
            } else if (img) {
                // For <img> fallback
                const paddedCanvas = document.createElement("canvas");
                const size = img.width + padding * 2;
                paddedCanvas.width = size;
                paddedCanvas.height = size;

                const ctx = paddedCanvas.getContext("2d");
                ctx.fillStyle = "#ffffff";
                ctx.fillRect(0, 0, size, size);
                ctx.drawImage(img, padding, padding);

                const url = paddedCanvas.toDataURL("image/png");
                const a = document.createElement("a");
                const identifier = userData.student_id || `${userData.first_name}-${userData.last_name}`;
                a.href = url;
                a.download = `Event-${eventId}-${identifier}-QR.png`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

                console.log("QR code download triggered successfully (img fallback)");
            } else {
                console.error("No canvas or img element found for QR code");
                alert("QR code generation failed. Please try again.");
            }

            // Clean up temporary div
            document.body.removeChild(tempDiv);
        }, 500);
    }


    // Function to show cancel button for members
    function showCancelButtonForMember(eventId, userEmail, eventCard = null) {
        const registerBtn = document.getElementById("registerBtn");
        registerBtn.style.display = "none";

        let cancelBtn = document.getElementById("cancelPreRegister");
        if (!cancelBtn) {
            cancelBtn = document.createElement("button");
            cancelBtn.id = "cancelPreRegister";
            cancelBtn.textContent = "Cancel Pre-Registration";
            cancelBtn.className = "register-btn cancel";
            cancelBtn.style.marginLeft = "10px";
            document.querySelector(".modal-body").appendChild(cancelBtn);
        }

        cancelBtn.style.display = "inline-block";
        cancelBtn.dataset.eventId = eventId;

        cancelBtn.onclick = async () => {
            const confirmCancel = confirm("Are you sure you want to cancel your registration?");
            if (!confirmCancel) return;

            const result = await apiCall(`/events/${eventId}/cancel-by-email`, "POST", {
                email: userEmail
            });
            
            if (result?.success) {
                showMessage("✅ Registration canceled successfully.");
                cancelBtn.style.display = "none";
                registerBtn.style.display = "inline-block";
                
                // Update the card dataset if provided
                if (eventCard) {
                    eventCard.dataset.registered = "false";
                    // Update registered count
                    const currentCount = parseInt(eventCard.dataset.registeredCount) || 0;
                    if (currentCount > 0) {
                        eventCard.dataset.registeredCount = (currentCount - 1).toString();
                    }
                }
                
                // Close modal after successful cancellation
                setTimeout(() => {
                    document.getElementById("eventModal").style.display = "none";
                }, 1500);
            } else {
                showMessage(result?.message || "Cancellation failed.");
            }
        };
    }

    async function showPreRegisterFormInsideModal(eventId) {
        const authStatus = await apiCall("/auth/check-login", "GET");
        let userEmail = null;
        
        if (authStatus?.success && authStatus?.data?.logged_in) {
            userEmail = authStatus.data.user.email;
        }
        
        // If user is logged in and has email, check if already registered
        if (userEmail) {
            const registrationCheck = await checkEmailRegistration(eventId, email);
            if (registrationCheck.registered) {
                // Show message with cancel button
                showMessage(`You are already registered for this event as a ${registrationCheck.participant_type}. 
                    <br><br>
                    <button id="cancelByEmailBtn" class="register-btn" style="background: #dc3545; margin-top: 10px;">
                        Cancel Pre-Registration
                    </button>`);
                
                document.getElementById("cancelByEmailBtn").addEventListener("click", async () => {
                    const result = await apiCall(`/events/${eventId}/cancel-by-email`, "POST", { email });
                    if (result?.success) {
                        showMessage("Your pre-registration has been cancelled.");
                        // Update event card data
                        const eventCard = document.querySelector(`.event-card[data-id='${eventId}']`);
                        if (eventCard) {
                            const currentCount = parseInt(eventCard.dataset.registeredCount) || 0;
                            if (currentCount > 0) {
                                eventCard.dataset.registeredCount = (currentCount - 1).toString();
                            }
                        }
                        // Close modal
                        const modal = document.getElementById("eventModal");
                        modal.style.display = "none";
                    } else {
                        showMessage(result?.message || "Failed to cancel pre-registration.");
                    }
                });
                return;
            }
        }
        const modal = document.getElementById("eventModal");
        const modalContent = document.querySelector("#eventModal .modal-content");

        const modalTitle = document.getElementById("modalTitle");
        const modalDate = document.getElementById("modalDate");
        const modalDesc = document.getElementById("modalContent");
        const registerBtn = document.getElementById("registerBtn");

        [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => {
            if (el) el.style.display = "none";
        });

        const formContainer = document.createElement("div");
        formContainer.classList.add("pre-register-container");

        formContainer.innerHTML = `
            <h2>Pre-Register for Event</h2>
            <p class="subtitle">This event is open for public participants. Please fill out your information below:</p>

            <div class="single-select">
                <label>Participant Type*</label>
                <select id="participantType" required>
                    <option value="" disabled selected>Select Type</option>
                    <option value="guest">Guest</option>
                    <option value="bulsuan">BulSUan</option>
                </select>
            </div>

            <div id="guestFields">
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

                        <label>Facebook Profile Link</label>
                        <input type="text" id="facebook">
                    </div>
                </div>
            </div>

            <div id="bulsuanFields" style="display: none;">
                <div class="form-grid">
                    <div class="left-col">
                        <label>First Name*</label>
                        <input type="text" id="bulsuFirstName" required>

                        <label>Last Name*</label>
                        <input type="text" id="bulsuLastName" required>

                        <label>Email*</label>
                        <input type="email" id="bulsuEmail" required>

                        <label>Gender*</label>
                        <select id="bulsuGender" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <div class="right-col">
                        <label>Middle Name</label>
                        <input type="text" id="bulsuMiddleName">

                        <label>Suffix</label>
                        <select id="bulsuSuffix">
                            <option>None</option>
                            <option>Jr.</option>
                            <option>I</option>
                            <option>II</option>
                            <option>III</option>
                        </select>

                        <label>Phone Number</label>
                        <input type="text" id="bulsuPhone">

                        <label>Facebook Profile Link</label>
                        <input type="text" id="bulsuFacebook">
                    </div>
                </div>

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
            </div>

            <div class="form-actions">
                <button id="submitPreRegister">Submit</button>
                <button id="cancelPreRegister">Cancel</button>
            </div>
        `;

        const existingForm = document.querySelector(".pre-register-container");
            if (existingForm) existingForm.remove();

            modalContent.appendChild(formContainer);
            modal.style.display = "flex";

            // Add event listener for participant type change
            document.getElementById("participantType").addEventListener("change", function() {
                const isBulSUan = this.value === "bulsuan";
                document.getElementById("guestFields").style.display = isBulSUan ? "none" : "block";
                document.getElementById("bulsuanFields").style.display = isBulSUan ? "block" : "none";
            });

            document.getElementById("cancelPreRegister").addEventListener("click", () => {
                formContainer.remove();
                [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => {
                    if (el) el.style.display = "";
                });
            });

            document.getElementById("submitPreRegister").addEventListener("click", async () => {
                const participantType = document.getElementById("participantType").value.toLowerCase();
                
                let data = {};
                let email = "";

                if (participantType === "guest") {
                    data = {
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
                    email = document.getElementById("email").value;
                } else if (participantType === "bulsuan") {
                    data = {
                        first_name: document.getElementById("bulsuFirstName").value,
                        last_name: document.getElementById("bulsuLastName").value,
                        middle_name: document.getElementById("bulsuMiddleName").value,
                        suffix: document.getElementById("bulsuSuffix").value,
                        gender: document.getElementById("bulsuGender").value,
                        email: document.getElementById("bulsuEmail").value,
                        phone: document.getElementById("bulsuPhone").value,
                        facebook: document.getElementById("bulsuFacebook").value,
                        student_id: document.getElementById("studentId").value,
                        program: document.getElementById("program").value,
                        college: document.getElementById("college").value,
                        year_level: document.getElementById("yearLevel").value,
                        section: document.getElementById("section").value,
                        user_type: "bulsuan",
                    };
                    email = document.getElementById("bulsuEmail").value;
                }

                // Validate required fields
                if (!data.first_name || !data.last_name || !data.email || !data.gender || !participantType) {
                    alert("Please fill in all required fields.");
                    return;
                }

                if (participantType === "bulsuan" && (!data.student_id || !data.program || !data.college || !data.year_level)) {
                    alert("Please fill in all required BulSU information fields.");
                    return;
                }

                // First, check if this email is already registered
                const registrationCheck = await checkEmailRegistration(eventId, email);
                if (registrationCheck.registered) {
                    // Show message with cancel button
                    showMessage(`You are already registered for this event as a ${registrationCheck.participant_type}. 
                        <br><br>
                        <button id="cancelByEmailBtn" class="register-btn" style="background: #dc3545; margin-top: 10px;">
                            Cancel Pre-Registration
                        </button>`);
                    
                    document.getElementById("cancelByEmailBtn").addEventListener("click", async () => {
                        const result = await apiCall(`/events/${eventId}/cancel-by-email`, "POST", { email });
                        if (result?.success) {
                            showMessage("Your pre-registration has been cancelled.");
                            formContainer.remove();
                            [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => {
                                if (el) el.style.display = "";
                            });
                        } else {
                            showMessage(result?.message || "Failed to cancel pre-registration.");
                        }
                    });
                    return;
                }

                const result = await apiCall(`/events/${eventId}/register`, "POST", data);

                console.log("API Response:", result);

                if (result && result.success) {
                    if (result.data && result.data.duplicate) {
                        showMessage(`You are already registered for this event with email: <strong>${result.data.email}</strong>. 
                            <br><br>
                            <button id="cancelByEmailBtn" class="register-btn" style="background: #dc3545; margin-top: 10px;">
                                Cancel Pre-Registration
                            </button>`);
                        
                        document.getElementById("cancelByEmailBtn").addEventListener("click", async () => {
                            const cancelResult = await apiCall(`/events/${eventId}/cancel-by-email`, "POST", { email: result.data.email });
                            if (cancelResult?.success) {
                                showMessage("Your pre-registration has been cancelled.");
                                formContainer.remove();
                                [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => {
                                    if (el) el.style.display = "";
                                });
                            } else {
                                showMessage(cancelResult?.message || "Failed to cancel pre-registration.");
                            }
                        });
                        return;
                    }
                    showMessage("Registration successful! Please keep your downloaded QR code for verification.");

                    // Close the modal first
                    formContainer.remove();
                    const modal = document.getElementById("eventModal");
                    modal.style.display = "none";

                    let qrData;
                    if (result.data && result.data.qr_code) {
                        qrData = result.data.qr_code;
                    } else {
                        console.error("No qr_code returned from API:", result);
                        alert("Registration successful but QR code generation failed. Please contact support.");
                        return;
                    }

                    console.log("Generating QR with data:", qrData);

                    // Create a temporary container for QR generation
                    const tempDiv = document.createElement("div");
                    tempDiv.style.position = "absolute";
                    tempDiv.style.left = "-9999px";
                    document.body.appendChild(tempDiv);

                    // Generate QR code
                    new QRCode(tempDiv, {
                        text: qrData,
                        width: 200,
                        height: 200,
                        colorDark: "#06047b",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H,
                    });

                    // Wait longer for QR code to fully render
                    setTimeout(() => {
                        const canvas = tempDiv.querySelector("canvas");
                        const img = tempDiv.querySelector("img");

                        const padding = 20;

                        if (canvas) {
                            // Create a new canvas with extra padding
                            const paddedCanvas = document.createElement("canvas");
                            const size = canvas.width + padding * 2;
                            paddedCanvas.width = size;
                            paddedCanvas.height = size;

                            const ctx = paddedCanvas.getContext("2d");
                            ctx.fillStyle = "#ffffff";
                            ctx.fillRect(0, 0, size, size);
                            ctx.drawImage(canvas, padding, padding);

                            const url = paddedCanvas.toDataURL("image/png");
                            const a = document.createElement("a");
                            const identifier = data.student_id || `${data.first_name}-${data.last_name}`;
                            a.href = url;
                            a.download = `Event-${eventId}-${identifier}-QR.png`;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);

                            console.log("QR code download triggered successfully");
                        } else if (img) {
                            // For <img> fallback
                            const paddedCanvas = document.createElement("canvas");
                            const size = img.width + padding * 2;
                            paddedCanvas.width = size;
                            paddedCanvas.height = size;

                            const ctx = paddedCanvas.getContext("2d");
                            ctx.fillStyle = "#ffffff";
                            ctx.fillRect(0, 0, size, size);
                            ctx.drawImage(img, padding, padding);

                            const url = paddedCanvas.toDataURL("image/png");
                            const a = document.createElement("a");
                            const identifier = data.student_id || `${data.first_name}-${data.last_name}`;
                            a.href = url;
                            a.download = `Event-${eventId}-${identifier}-QR.png`;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);

                            console.log("QR code download triggered successfully (img fallback)");
                        } else {
                            console.error("No canvas or img element found for QR code");
                            alert("QR code generation failed. Please try again.");
                        }

                        // Clean up temporary div
                        document.body.removeChild(tempDiv);
                    }, 500); // Increased timeout to 500ms
                } else {
                    showMessage(result?.message || "Pre-registration failed.");
                }
            });
        }

        async function showBulSUPreRegisterForm(eventId) {
            
            const modal = document.getElementById("eventModal");
            const modalContent = document.querySelector("#eventModal .modal-content");

            const modalTitle = document.getElementById("modalTitle");
            const modalDate = document.getElementById("modalDate");
            const modalDesc = document.getElementById("modalContent");
            const registerBtn = document.getElementById("registerBtn");

            [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => {
                if (el) el.style.display = "none";
            });

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

                        <label>Facebook Profile Link</label>
                        <input type="text" id="facebook">
                    </div>
                </div>

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

            const existingForm = document.querySelector(".pre-register-container");
            if (existingForm) existingForm.remove();

            modalContent.appendChild(formContainer);
            modal.style.display = "flex";

            document.getElementById("cancelBulSUPreRegister").addEventListener("click", () => {
                formContainer.remove();
                [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => {
                    if (el) el.style.display = "";
                });
            });

            document.getElementById("submitBulSUPreRegister").addEventListener("click", async () => {

                const email = document.getElementById("email").value;
                // Check if already registered
                const registrationCheck = await checkEmailRegistration(eventId, email);
                
                if (registrationCheck.registered) {
                    showMessage(`You are already registered for this event as a ${registrationCheck.participant_type}. 
                        <br><br>
                        <button id="cancelByEmailBtn" class="register-btn" style="background: #dc3545; margin-top: 10px;">
                            Cancel Pre-Registration
                        </button>`);
                    
                    document.getElementById("cancelByEmailBtn").addEventListener("click", async () => {
                        const result = await apiCall(`/events/${eventId}/cancel-by-email`, "POST", { email });
                        if (result?.success) {
                            showMessage("Your pre-registration has been cancelled.");
                            formContainer.remove();
                            [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => {
                                if (el) el.style.display = "";
                            });
                            // Update event card data if needed
                            const eventCard = document.querySelector(`.event-card[data-id='${eventId}']`);
                            if (eventCard) {
                                const currentCount = parseInt(eventCard.dataset.registeredCount) || 0;
                                if (currentCount > 0) {
                                    eventCard.dataset.registeredCount = (currentCount - 1).toString();
                                }
                            }
                        } else {
                            showMessage(result?.message || "Failed to cancel pre-registration.");
                        }
                    });
                    return;
                }

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

                if (!data.first_name || !data.last_name || !data.email || !data.gender || !data.student_id || !data.program || !data.college || !data.year_level) {
                    alert("Please fill in all required fields.");
                    return;
                }

                const result = await apiCall(`/events/${eventId}/register`, "POST", data);
                if (result && result.success) {
                    if (result.data && result.data.duplicate) {
                        showMessage(`You are already registered for this event with email: <strong>${result.data.email}</strong>. 
                            <br><br>
                            <button id="cancelByEmailBtn" class="register-btn" style="background: #dc3545; margin-top: 10px;">
                                Cancel Pre-Registration
                            </button>`);
                        
                        document.getElementById("cancelByEmailBtn").addEventListener("click", async () => {
                            const cancelResult = await apiCall(`/events/${eventId}/cancel-by-email`, "POST", { email: result.data.email });
                            if (cancelResult?.success) {
                                showMessage("Your pre-registration has been cancelled.");
                                formContainer.remove();
                                [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => {
                                    if (el) el.style.display = "";
                                });
                            } else {
                                showMessage(cancelResult?.message || "Failed to cancel pre-registration.");
                            }
                        });
                        return;
                    }
                    showMessage(result.message || "Pre-registration successful!");
                    // Close the modal first
                    formContainer.remove();
                    const modal = document.getElementById("eventModal");
                    modal.style.display = "none";

                    let qrData;
                    if (result.data && result.data.qr_code) {
                        qrData = result.data.qr_code;
                    } else {
                        console.error("No qr_code returned from API:", result);
                        alert("Registration successful but QR code generation failed. Please contact support.");
                        return;
                    }

                    console.log("Generating QR with data:", qrData);

                    // Create a temporary container for QR generation
                    const tempDiv = document.createElement("div");
                    tempDiv.style.position = "absolute";
                    tempDiv.style.left = "-9999px";
                    document.body.appendChild(tempDiv);

                    // Generate QR code
                    new QRCode(tempDiv, {
                        text: qrData,
                        width: 200,
                        height: 200,
                        colorDark: "#06047b",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H,
                    });

                    // Wait longer for QR code to fully render
                    setTimeout(() => {
                        const canvas = tempDiv.querySelector("canvas");
                        const img = tempDiv.querySelector("img");

                        const padding = 20;

                        if (canvas) {
                            // Create a new canvas with extra padding
                            const paddedCanvas = document.createElement("canvas");
                            const size = canvas.width + padding * 2;
                            paddedCanvas.width = size;
                            paddedCanvas.height = size;

                            const ctx = paddedCanvas.getContext("2d");
                            ctx.fillStyle = "#ffffff";
                            ctx.fillRect(0, 0, size, size);
                            ctx.drawImage(canvas, padding, padding);

                            const url = paddedCanvas.toDataURL("image/png");
                            const a = document.createElement("a");
                            const identifier = data.student_id || `${data.first_name}-${data.last_name}`;
                            a.href = url;
                            a.download = `Event-${eventId}-${identifier}-QR.png`;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);

                            console.log("QR code download triggered successfully");
                        } else if (img) {
                            // For <img> fallback
                            const paddedCanvas = document.createElement("canvas");
                            const size = img.width + padding * 2;
                            paddedCanvas.width = size;
                            paddedCanvas.height = size;

                            const ctx = paddedCanvas.getContext("2d");
                            ctx.fillStyle = "#ffffff";
                            ctx.fillRect(0, 0, size, size);
                            ctx.drawImage(img, padding, padding);

                            const url = paddedCanvas.toDataURL("image/png");
                            const a = document.createElement("a");
                            const identifier = data.student_id || `${data.first_name}-${data.last_name}`;
                            a.href = url;
                            a.download = `Event-${eventId}-${identifier}-QR.png`;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);

                            console.log("QR code download triggered successfully (img fallback)");
                        } else {
                            console.error("No canvas or img element found for QR code");
                            alert("QR code generation failed. Please try again.");
                        }

                        // Clean up temporary div
                        document.body.removeChild(tempDiv);
                    }, 500); // Increased timeout to 500ms
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

            [modalTitle, modalDate, modalDesc].forEach(el => {
                if (el) el.style.display = "";
            });

            registerBtn.style.display = "inline-block";
            registerBtn.textContent = "Register Now";
            registerBtn.dataset.eventId = eventId;

            const newRegisterBtn = registerBtn.cloneNode(true);
            registerBtn.parentNode.replaceChild(newRegisterBtn, registerBtn);
            registerBtn = newRegisterBtn;

            registerBtn.onclick = async () => {
                const authStatus = await apiCall("/auth/check-login", "GET");
                if (!authStatus?.success || !authStatus?.data?.logged_in) {
                    showMessage('Please <a href="login.php" class="text-blue-500">log in</a> first to register.');
                    return;
                }

                const result = await apiCall(`/events/${eventId}/register`, "POST", {}, `Register for Event #${eventId}`);
                if (result?.success) {
                    showMessage(result.message || "✅ Registered successfully!");
                } else {
                    showMessage(result?.message || "Registration failed.");
                }
            };

            let cancelPreBtn = document.getElementById("cancelPreRegister");
            if (!cancelPreBtn) {
                cancelPreBtn = document.createElement("button");
                cancelPreBtn.id = "cancelPreRegister";
                cancelPreBtn.textContent = "Cancel Pre-Registration";
                cancelPreBtn.className = "register-btn";
                modalContent.appendChild(cancelPreBtn);
            }

            cancelPreBtn.style.display = "inline-block";

            cancelPreBtn.onclick = async () => {
                const authStatus = await apiCall("/auth/check-login", "GET");
                if (!authStatus?.success || !authStatus?.data?.logged_in) {
                    showMessage('Please <a href="login.php" class="text-blue-500">log in</a> first.');
                    return;
                }

                const userId = authStatus.data.user_id;

                const result = await apiCall(`/events/${eventId}/cancel-pre-registration`, "POST", {
                    user_id: userId
                });
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
            closeBtn.onclick = () => window.location.reload();

            messageModal.onclick = e => {
                if (e.target === messageModal) messageModal.style.display = "none";
            };
        }

        // Add this function to check if email is registered for an event
        async function checkEmailRegistration(eventId, email) {
            try {
                const response = await apiCall(`/events/${eventId}/check-email?email=${encodeURIComponent(email)}`, 'GET');
                if (response?.success && response.data) {
                    return response.data;
                }
                return { registered: false, participant_type: null };
            } catch (err) {
                console.error("Error checking email registration:", err);
                return { registered: false, participant_type: null };
            }
        }

        // Also update the existing checkEventStatus function to be more robust
        function checkEventStatus() {
            const now = new Date();
            const upcomingSection = document.getElementById("upcomingSection").querySelector(".event-list");
            const completedSection = document.getElementById("pastSection").querySelector(".event-list");

            document.querySelectorAll("#upcomingSection .event-card").forEach(card => {
                const eventDate = card.dataset.date;
                const eventTimeEnd = card.dataset.time ? card.dataset.time.split(' - ')[1] : '23:59';
                const eventEndDateTime = new Date(`${eventDate}T${eventTimeEnd}`);

                if (now > eventEndDateTime) {
                    // Move to completed section
                    card.remove();

                    card.dataset.status = "completed";
                    completedSection.appendChild(card);

                    // Update any open modal
                    const registerBtn = document.getElementById("registerBtn");
                    if (registerBtn && registerBtn.dataset.eventId === card.dataset.id) {
                        registerBtn.style.display = "none";
                    }
                }
            });
        }

        setInterval(updateEventStatuses, 60000); // Check every minute instead of 30 seconds

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<?php include '_footer.php'; ?>