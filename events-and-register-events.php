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

.pre-register-container .form-actions {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 1.5rem;
}

button.register-btn.cancel-btn {
    background-color: #ccc;
    color: #000;
}

button.register-btn.cancel-btn:hover {
    background-color: #aaa;
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

/* Make form container scrollable if needed */
.pre-register-container {
    color: white;
    padding: 2rem;
    overflow-y: auto;
    max-height: 60vh;
    grid-column: 1 / -1;
}

@media (max-width: 968px) {
    .modal-content {
        grid-template-columns: 1fr;
        max-width: 600px;
        max-height: 90vh;
    }
    
    .modal-image {
        height: 0;
        padding-bottom: 100%;
    }
}

@media (max-width: 768px) {
    .modal {
        padding: 1rem;
    }
    
    .modal-content {
        max-height: 95vh;
        width: 95%;
    }
    
    .modal-image {
        padding-bottom: 100%;
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
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-grid label {
        display: block;
        margin-bottom: 0.5rem;
        color: #b9da05;
    }

    .form-grid input,
    .form-grid select {
        width: 100%;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(185, 218, 5, 0.3);
        border-radius: 8px;
        color: white;
    }

    .single-select {
        margin-bottom: 1.5rem;
    }

    .single-select label {
        display: block;
        margin-bottom: 0.5rem;
        color: #b9da05;
    }

    .single-select select {
        width: 100%;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(185, 218, 5, 0.3);
        border-radius: 8px;
        color: white;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }

    .form-actions button {
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    #submitPreRegister,
    #submitBulSUPreRegister {
        background: #b9da05;
        color: #000;
    }

    #cancelPreRegister,
    #cancelBulSUPreRegister {
        background: rgba(255, 255, 255, 0.1);
        color: white;
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
            <button id="cancelPreRegisterGlobal" class="register-btn cancel-btn" style="display: none;">
    Cancel Pre-Registration
</button>
        </div>
    </div>
</div>

<div id="messageModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn" id="messageCloseBtn">&times;</span>
        <div class="modal-body">
            <div id="messageInner"></div>
        </div>
    </div>
</div>

<!-- All your existing scripts remain exactly the same (but with JS fixes below) -->
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
            renderEvents(completedEvents, "pastSection");
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

            card.dataset.id = event.event_id;
            card.dataset.title = event.event_name;
            card.dataset.date = event.event_date;
            card.dataset.status = event.event_status;
            card.dataset.content = event.description;
            card.dataset.capacity = event.capacity || 0;
            card.dataset.registeredCount = event.attendants || 0;
            card.dataset.access = event.event_restriction || "public";
            card.dataset.image = event.event_batch_image || ""; // Store image URL

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

            // No inline click here — listeners attached in attachCardListeners()
            section.appendChild(card);
        });
    }

    window.addEventListener("DOMContentLoaded", loadEvents);
</script>

<!-- Keep all your existing scripts below (modified for correct registration logic) -->
<script>
    // Helper modal functions (used for guest prompts)
    function showModal(html) {
        const messageModal = document.getElementById("messageModal");
        const inner = document.getElementById("messageInner");
        inner.innerHTML = html;
        messageModal.style.display = "flex";

        // attach close handler to close icon
        const closeIcon = document.getElementById("messageCloseBtn");
        closeIcon.onclick = () => closeModal();
    }

    function closeModal() {
        const messageModal = document.getElementById("messageModal");
        messageModal.style.display = "none";
        document.getElementById("messageInner").innerHTML = "";
    }

    // keep your existing DOM ready block (we don't change it)
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

<!-- Main registration logic and listeners -->
<script>
    function attachCardListeners() {
        const modal = document.getElementById("eventModal");
        const closeBtn = document.querySelector("#eventModal .close-btn");
        const registerBtn = document.getElementById("registerBtn");
        const cancelBtnGlobal = document.getElementById("cancelPreRegisterGlobal");

        // Make sure to rebind listeners - call this after renderEvents
        document.querySelectorAll(".event-card").forEach(card => {
            card.addEventListener("click", async (e) => {
                e.stopPropagation();

                const eventId = card.dataset.id;
                const modalTitle = document.getElementById("modalTitle");
                const modalDate = document.getElementById("modalDate");
                const modalDesc = document.getElementById("modalContent");

                modalTitle.textContent = card.dataset.title;
                modalDate.textContent = card.dataset.date;
                modalDesc.textContent = card.dataset.content;

                // Update modal image
                const modalImage = document.getElementById("modalImage");
                if (card.dataset.image) {
                    modalImage.innerHTML = `<img src="${card.dataset.image}" alt="Event Image" />`;
                } else {
                    modalImage.innerHTML = `<i class="bi bi-calendar-event"></i>`;
                }

                // Reset buttons
                registerBtn.style.display = "none";
                cancelBtnGlobal.style.display = "none";
                registerBtn.dataset.eventId = eventId;

                // Check login status
                let authStatus = null;
                try {
                    authStatus = await apiCall("/auth/check-login", "GET");
                } catch (err) {
                    console.error("Auth check error:", err);
                }

            if (authStatus?.success && authStatus?.data?.logged_in) {
                // Logged in: use email to check registration
                const email = authStatus.data.user?.email;
                console.log("Logged-in user email:", email);
                try {
                    const checkEndpoint = `/events/${eventId}/check-registration-email?email=${email}`;
                    const regStatus = await apiCall(checkEndpoint, "GET");

                    // Controller returns { success:true, registered: boolean }
                    const isRegistered =
                        regStatus?.success &&
                        (regStatus.data?.registered === true ||
                        regStatus.data?.registered === "1" ||
                        regStatus.data?.registered === 1);


                    console.log("Registration check response:", regStatus, "isRegistered:", isRegistered);


                    if (isRegistered) {
                        // Show cancel pre-registration for logged-in users
                        cancelBtnGlobal.style.display = "inline-block";
                        console.log("Cancel button element:", cancelBtnGlobal);
                        registerBtn.style.display = "none";

                        // Attach cancel logic (ensure single binding)
                        cancelBtnGlobal.onclick = async (ev) => {
                            ev.preventDefault();
                            ev.stopPropagation();

                            try {
                                const payload = { email }; // ✅ use email only
                                const result = await apiCall(
                                    `/events/${eventId}/cancel-pre-registration`,
                                    "POST",
                                    payload
                                );

                                if (result?.success) {
                                    showMessage('<p class="text-white">✅ Pre-registration cancelled successfully.</p>');
                                    modal.style.display = "none";
                                    setTimeout(() => window.location.reload(), 800);
                                } else {
                                    showMessage(`<p class="text-white">❌ ${result?.message || "Failed to cancel pre-registration"}</p>`);
                                }
                            } catch (err) {
                                console.error("Cancel error:", err);
                                showMessage('<p class="text-white">❌ Error cancelling registration.</p>');
                            }
                        };
                    } else {
                        // Not yet registered — allow registration
                        cancelBtnGlobal.style.display = "none";
                        registerBtn.style.display =
                            card.dataset.status?.toLowerCase() === "upcoming"
                                ? "inline-block"
                                : "none";
                    }

                    } catch (err) {
                        console.error("Error checking user registration:", err);
                        // fallback: show register button if upcoming
                        registerBtn.style.display = (card.dataset.status?.toLowerCase() === "upcoming") ? "inline-block" : "none";
                    }
                } else {
                    // Not logged in (guest) -> show register button to open form
                    registerBtn.style.display = (card.dataset.status?.toLowerCase() === "upcoming") ? "inline-block" : "none";
                    cancelBtnGlobal.style.display = "none";
                    // cancel handler hidden for guests here (cancellation from guest is via form)
                    cancelBtnGlobal.onclick = null;
                }

                // show modal
                modal.style.display = "flex";
            });
        });

        // modal close
        closeBtn.addEventListener("click", () => {
            const modal = document.getElementById("eventModal");
            modal.style.display = "none";
        });
        document.getElementById("eventModal").addEventListener("click", e => { if (e.target === document.getElementById("eventModal")) document.getElementById("eventModal").style.display = "none"; });

    // Register button handler — route based on restriction type
    registerBtn.addEventListener("click", async () => {
        const eventId = registerBtn.dataset.eventId;
        if (!eventId) return;

        const eventCard = document.querySelector(`.event-card[data-id='${eventId}']`);
        const restriction = eventCard?.dataset?.access?.toLowerCase() || "public";

        // ✅ Check if event is full
        const registered = parseInt(eventCard?.dataset?.registeredCount || "0");
        const capacity = parseInt(eventCard?.dataset?.capacity || "0");
        if (capacity === 0 || registered >= capacity) {
            showMessage(`<p class="text-white">⚠ Sorry, this event is already full.</p>`);
            setTimeout(() => window.location.reload(), 800);
            return;
        }

    // Continue as normal
    const auth = await apiCall("/auth/check-login", "GET");
    const isLoggedIn = auth?.success && auth?.data?.logged_in;

            let profileData = null;
            if (isLoggedIn) {
                try {
                    const studentId = auth.data.user?.id;
                    const studentRes = await apiCall(`/students/${studentId}`, "GET");
                    if (studentRes?.success && studentRes.data) profileData = studentRes.data;
                } catch (err) {
                    console.error("Error fetching profile:", err);
                }
            }

            if (restriction === "public") {
                showPreRegisterFormInsideModal(eventId, profileData);
            } else if (restriction === "bulsuans") {
                showBulSUPreRegisterForm(eventId, profileData);
            } else if (restriction === "members") {
                handleMembersOnlyRegistration(eventId, profileData);
            } else {
                showMessage(`<p class="text-white">🚫 This event is restricted to ${restriction} participants.</p>`);
            }
        });
    }

    // Public pre-register form (guest or logged-in pre-fill)
// Public pre-register form (guest or logged-in pre-fill)
async function showPreRegisterFormInsideModal(eventId, profileData = null) {
    const modal = document.getElementById("eventModal");
    const modalContent = modal.querySelector(".modal-content");
    const modalTitle = document.getElementById("modalTitle");
    const modalDate = document.getElementById("modalDate");
    const modalDesc = document.getElementById("modalContent");
    const registerBtn = document.getElementById("registerBtn");

    // Hide default text/buttons
    [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => { if (el) el.style.display = "none"; });

    // Remove old form if present
    const existingForm = modalContent.querySelector(".pre-register-container");
    if (existingForm) existingForm.remove();

    // Create form
    const formContainer = document.createElement("div");
    formContainer.classList.add("pre-register-container");
    formContainer.innerHTML = `
        <h2>Pre-Register for Event</h2>
        <p class="subtitle">This event is open for public participants. Please fill out your information below:</p>
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
                <label>First Name*</label><input type="text" id="firstName" required>
                <label>Last Name*</label><input type="text" id="lastName" required>
                <label>Email*</label><input type="email" id="email" required>
                <label>Gender*</label>
                <select id="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option>Male</option><option>Female</option><option>Other</option>
                </select>
            </div>
            <div class="right-col">
                <label>Middle Name</label><input type="text" id="middleName">
                <label>Suffix</label>
                <select id="suffix"><option>None</option><option>Jr.</option><option>I</option><option>II</option><option>III</option></select>
                <label>Phone Number</label><input type="text" id="phone">
                <label>Facebook Profile Name</label><input type="text" id="facebook">
            </div>
        </div>
        <div class="form-actions">
            <button id="submitPreRegister" class="register-btn">Submit</button>
            <button id="cancelPreRegister" class="register-btn cancel-btn">Cancel</button>
        </div>
    `;
    modalContent.appendChild(formContainer);
    modal.style.display = "flex";

    // Prefill if profile data exists (logged-in BulSUan)
    if (profileData) {
        const participantSelect = document.getElementById("participantType");
        participantSelect.value = "BulSUan";
        participantSelect.disabled = true; // lock selection

        document.getElementById("firstName").value = profileData.first_name || "";
        document.getElementById("lastName").value = profileData.last_name || "";
        document.getElementById("middleName").value = profileData.middle_name || "";
        document.getElementById("suffix").value = profileData.suffix || "None";
        document.getElementById("email").value = profileData.email || "";
        document.getElementById("phone").value = profileData.phone || "";
        document.getElementById("facebook").value = profileData.facebook || "";
        document.getElementById("gender").value = profileData.gender || "";
    }

    // Restore original elements on cancel
    document.getElementById("cancelPreRegister").addEventListener("click", () => {
        formContainer.remove();
        [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => { if (el) el.style.display = ""; });
    });

    // Helper to check registration by email (uses backend endpoint)
    async function checkRegistrationByEmail(eventIdLocal, email) {
        try {
            const res = await apiCall(`/events/${eventIdLocal}/check-registration-email?email=${encodeURIComponent(email)}`, "GET");
            return res?.success && !!res.data?.registered;
        } catch (err) {
            console.error("Error checking registration by email:", err);
            return false;
        }
    }

    const emailInput = document.getElementById("email");
    const submitBtn = document.getElementById("submitPreRegister");

    // Update button state — only for Guests
    async function updateRegistrationState() {
        const email = (emailInput.value || "").trim();
        const participantType = document.getElementById("participantType").value.trim();

        if (!email) {
            submitBtn.textContent = "Submit";
            submitBtn.classList.remove("cancel-mode");
            return;
        }

        // Skip check for BulSUan (logged in)
        if (participantType === "BulSUan") return;

        submitBtn.disabled = true;
        const alreadyRegistered = await checkRegistrationByEmail(eventId, email);
        submitBtn.disabled = false;

        if (alreadyRegistered) {
            submitBtn.textContent = "Cancel Pre-Registration";
            submitBtn.classList.add("cancel-mode");
        } else {
            submitBtn.textContent = "Submit";
            submitBtn.classList.remove("cancel-mode");
        }
    }

    // Trigger when email loses focus (for guests)
    emailInput.addEventListener("blur", updateRegistrationState);

    // Submit handler — either cancel or register
    submitBtn.addEventListener("click", async () => {
        const email = (emailInput.value || "").trim();
        const participantType = document.getElementById("participantType").value.trim();

        if (!email) {
            alert("Please enter your email to continue.");
            emailInput.focus();
            return;
        }

        // Skip registration check for BulSUan (since backend join causes false positives)
        let already = false;
        if (participantType === "Guest") {
            already = await checkRegistrationByEmail(eventId, email);
        }

        if (already && participantType === "Guest") {
            // Guest cancel modal
            showModal(`
                <p class="text-white mb-4">You are already registered for this event. Do you want to cancel your registration?</p>
                <div class="flex gap-4 justify-center">
                    <button id="confirmCancelBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Cancel Pre-Registration</button>
                    <button id="closeModalBtn" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Close</button>
                </div>
            `);

            document.getElementById("confirmCancelBtn").addEventListener("click", async () => {
                const res = await apiCall(`/events/${eventId}/cancel-pre-registration`, "POST", { email });
                if (res?.success) {
                    closeModal();
                    formContainer.remove();
                    showMessage('<p class="text-white">✅ Your pre-registration has been cancelled.</p>');
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    closeModal();
                    showMessage(`<p class="text-white">❌ ${res?.message || "Failed to cancel."}</p>`);
                }
            });

            document.getElementById("closeModalBtn").addEventListener("click", closeModal);
            return;
        }

        // Proceed to register
        const formData = {
            user_type: participantType,
            first_name: document.getElementById("firstName").value.trim(),
            last_name: document.getElementById("lastName").value.trim(),
            middle_name: document.getElementById("middleName").value.trim(),
            suffix: document.getElementById("suffix").value,
            email,
            phone: document.getElementById("phone").value.trim(),
            facebook: document.getElementById("facebook").value.trim(),
            gender: document.getElementById("gender").value
        };

        const result = await apiCall(`/events/${eventId}/register`, "POST", formData);

        if (result?.success) {
            showMessage(`<p class="text-white">✅ ${result.message || "Pre-registration successful!"}</p>`);
            formContainer.remove();
            setTimeout(() => window.location.reload(), 800);
        } else {
            showMessage(`<p class="text-white">❌ ${result?.message || "Pre-registration failed."}</p>`);
        }
    });
}

    // BulSU pre-register form (full implementation)
    async function showBulSUPreRegisterForm(eventId, profileData = null) {
        const modal = document.getElementById("eventModal");
        const modalContent = modal.querySelector(".modal-content");
        const modalTitle = document.getElementById("modalTitle");
        const modalDate = document.getElementById("modalDate");
        const modalDesc = document.getElementById("modalContent");
        const registerBtn = document.getElementById("registerBtn");

        // Hide default modal content
        [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => { if (el) el.style.display = "none"; });

        // Remove old form if exists
        const existingForm = modalContent.querySelector(".pre-register-container");
        if (existingForm) existingForm.remove();

        // Create form
        const formContainer = document.createElement("div");
        formContainer.classList.add("pre-register-container");

        formContainer.innerHTML = `
            <h2>Pre-Register for BulSUans Only Event</h2>
            <p class="subtitle">This event is restricted to BulSU participants. Please fill out your information below:</p>

            <div class="form-grid">
                <div class="left-col">
                    <label>First Name*</label><input type="text" id="firstName" required>
                    <label>Last Name*</label><input type="text" id="lastName" required>
                    <label>Email*</label><input type="email" id="email" required>
                    <label>Gender*</label>
                    <select id="gender" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="right-col">
                    <label>Middle Name</label><input type="text" id="middleName">
                    <label>Suffix</label>
                    <select id="suffix">
                        <option>None</option>
                        <option>Jr.</option>
                        <option>I</option>
                        <option>II</option>
                        <option>III</option>
                    </select>
                    <label>Phone Number</label><input type="text" id="phone">
                    <label>Facebook Profile Name</label><input type="text" id="facebook">
                </div>
            </div>

            <h3>BulSU Information</h3>
            <div class="form-grid">
                <div class="left-col">
                    <label>Student/Employee ID*</label><input type="text" id="studentId" required>
                    <label>Program*</label><input type="text" id="program" required>
                </div>
                <div class="right-col">
                    <label>College*</label><input type="text" id="college" required>
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
                <label>Section</label><input type="text" id="section">
            </div>

            <div class="form-actions">
                <button id="submitBulSUPreRegister" class="register-btn">Submit</button>
                <button id="cancelBulSUPreRegister" class="register-btn cancel-btn">Cancel</button>
            </div>
        `;

        modalContent.appendChild(formContainer);
        modal.style.display = "flex";

        // Prefill from profileData if available
        if (profileData) {
            document.getElementById("firstName").value = profileData.first_name || "";
            document.getElementById("lastName").value = profileData.last_name || "";
            document.getElementById("middleName").value = profileData.middle_name || "";
            document.getElementById("suffix").value = profileData.suffix || "None";
            document.getElementById("email").value = profileData.email || "";
            document.getElementById("phone").value = profileData.phone || "";
            document.getElementById("facebook").value = profileData.facebook || "";
            document.getElementById("gender").value = profileData.gender || "";
            document.getElementById("studentId").value = profileData.student_id || profileData.student_no || profileData.msc_id || "";
            document.getElementById("program").value = profileData.program || "";
            document.getElementById("college").value = profileData.college || "";
            document.getElementById("yearLevel").value = profileData.year_level || "";
            document.getElementById("section").value = profileData.section || "";
        }

        // Cancel button
        document.getElementById("cancelBulSUPreRegister").addEventListener("click", () => {
            formContainer.remove();
            [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => { if (el) el.style.display = ""; });
        });

        // Helper: check registration by email
        async function checkRegistrationByEmail(eventIdLocal, email) {
            try {
                const res = await apiCall(`/events/${eventIdLocal}/check-registration-email?email=${encodeURIComponent(email)}`, "GET");
                return res?.success && !!res.data?.registered;
            } catch (err) {
                console.error("Error checking registration by email:", err);
                return false;
            }
        }

        const emailInput = document.getElementById("email");
        const submitBtn = document.getElementById("submitBulSUPreRegister");

        async function updateRegistrationState() {
            const email = (emailInput.value || "").trim();
            if (!email) return;
            submitBtn.disabled = true;
            const alreadyRegistered = await checkRegistrationByEmail(eventId, email);
            submitBtn.disabled = false;
            submitBtn.textContent = alreadyRegistered ? "Cancel Pre-Registration" : "Submit";
            submitBtn.classList.toggle("cancel-mode", alreadyRegistered);
        }

        emailInput.addEventListener("blur", updateRegistrationState);

        // Submit handler
        submitBtn.addEventListener("click", async () => {
            const data = {
                first_name: document.getElementById("firstName").value.trim(),
                last_name: document.getElementById("lastName").value.trim(),
                middle_name: document.getElementById("middleName").value.trim(),
                suffix: document.getElementById("suffix").value,
                gender: document.getElementById("gender").value,
                email: document.getElementById("email").value.trim(),
                phone: document.getElementById("phone").value.trim(),
                facebook: document.getElementById("facebook").value.trim(),
                student_id: document.getElementById("studentId").value.trim(),
                program: document.getElementById("program").value.trim(),
                college: document.getElementById("college").value.trim(),
                year_level: document.getElementById("yearLevel").value.trim(),
                section: document.getElementById("section").value.trim(),
                user_type: "bulsuan",
            };

            // Validate required fields
            if (!data.first_name || !data.last_name || !data.email || !data.gender ||
                !data.student_id || !data.program || !data.college || !data.year_level) {
                alert("Please fill in all required fields.");
                return;
            }

            // Check if already registered
            const already = await checkRegistrationByEmail(eventId, data.email);
            if (already) {
                showModal(`
                    <p class="text-white mb-4">You are already registered. Cancel your registration?</p>
                    <div class="flex gap-4 justify-center">
                        <button id="confirmCancelBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Cancel Pre-Registration</button>
                        <button id="closeModalBtn" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Close</button>
                    </div>
                `);
                document.getElementById("confirmCancelBtn").addEventListener("click", async () => {
                    const res = await apiCall(`/events/${eventId}/cancel-pre-registration`, "POST", { email: data.email });
                    if (res?.success) {
                        closeModal();
                        formContainer.remove();
                        showMessage('<p class="text-white">✅ Pre-registration cancelled.</p>');
                        setTimeout(() => window.location.reload(), 800);
                    } else {
                        closeModal();
                        showMessage(`<p class="text-white">❌ ${res?.message || "Failed to cancel."}</p>`);
                    }
                });
                document.getElementById("closeModalBtn").addEventListener("click", closeModal);
                return;
            }

            // Register
            const result = await apiCall(`/events/${eventId}/register`, "POST", data);
            if (result?.success) {
                showMessage(`<p class="text-white">✅ ${result.message || "Pre-registration successful!"}</p>`);
                formContainer.remove();
                setTimeout(() => window.location.reload(), 800);
            } else {
                showMessage(`<p class="text-white">❌ ${result?.message || "Pre-registration failed."}</p>`);
            }
        });
    }


    // Members only registration
async function handleMembersOnlyRegistration(eventId, profileData = null) {
    const modal = document.getElementById("eventModal");
    const modalContent = modal.querySelector(".modal-content");
    const modalTitle = document.getElementById("modalTitle");
    const modalDate = document.getElementById("modalDate");
    const modalDesc = document.getElementById("modalContent");
    const registerBtn = document.getElementById("registerBtn");

    // Hide default modal content
    [modalTitle, modalDate, modalDesc, registerBtn].forEach(el => {
        if (el) el.style.display = "none";
    });

    // Remove any old forms
    const existingForm = modalContent.querySelector(".pre-register-container");
    if (existingForm) existingForm.remove();

    // Create a simple container
    const formContainer = document.createElement("div");
    formContainer.classList.add("pre-register-container");
    formContainer.innerHTML = `
        <h2>Member Registration Check</h2>
        <p class="subtitle">Checking your registration status...</p>
        <div id="memberStatusActions" class="form-actions" style="display:none;">
            <button id="submitMemberRegister" class="register-btn">Register Now</button>
            <button id="cancelMemberRegister" class="register-btn cancel-btn">Cancel Registration</button>
        </div>
    `;
    modalContent.appendChild(formContainer);
    modal.style.display = "flex";

    const statusText = formContainer.querySelector(".subtitle");
    const actions = document.getElementById("memberStatusActions");
    const submitBtn = document.getElementById("submitMemberRegister");
    const cancelBtn = document.getElementById("cancelMemberRegister");

    // --- Helper: check registration ---
    async function checkRegistrationByEmail(eventIdLocal, email) {
        try {
            const res = await apiCall(`/events/${eventIdLocal}/check-registration-email?email=${encodeURIComponent(email)}`, "GET");
            return res?.success && !!res.data?.registered;
        } catch (err) {
            console.error("Error checking registration by email:", err);
            return false;
        }
    }

    // --- Helper: refresh UI based on registration state ---
    async function updateRegistrationState() {
        const email = (profileData?.email || "").trim();
        if (!email) {
            statusText.textContent = "❌ Missing email address in your profile.";
            return;
        }

        statusText.textContent = "Checking registration...";
        actions.style.display = "none";

        const alreadyRegistered = await checkRegistrationByEmail(eventId, email);

        if (alreadyRegistered) {
            statusText.textContent = "✅ You are already registered for this event.";
            submitBtn.style.display = "none";
            cancelBtn.style.display = "inline-block";
        } else {
            statusText.textContent = "You are not registered for this event.";
            submitBtn.style.display = "inline-block";
            cancelBtn.style.display = "none";
        }

        actions.style.display = "flex";
    }

    // --- Click: register ---
    submitBtn.addEventListener("click", async () => {
        console.log("Profile data being sent:", profileData);
        if (!profileData) {
            showMessage(`<p class="text-white">❌ Missing profile data. Please log in again.</p>`);
            return;
        }

        const data = {
            student_id: profileData.student_id || profileData.student_no || profileData.msc_id || "",
            first_name: profileData.first_name || "",
            last_name: profileData.last_name || "",
            email: profileData.email || "",
            program: profileData.program || "",
            college: profileData.college || "",
            year_level: profileData.year_level || "",
            section: profileData.section || "",
            gender: profileData.gender || "",
            phone: profileData.phone || "",
            facebook: profileData.facebook_link || "",
            user_type: "member"
        };

        const result = await apiCall(`/events/${eventId}/register`, "POST", data);
        if (result?.success) {
            showMessage(`<p class="text-white">✅ ${result.message || "Successfully pre-registered!"}</p>`);
            setTimeout(() => window.location.reload(), 800);
        } else {
            showMessage(`<p class="text-white">❌ ${result?.message || "Pre-registration failed."}</p>`);
        }
    });

    // --- Click: cancel registration ---
    cancelBtn.addEventListener("click", async () => {
        const email = profileData?.email;
        if (!email) return;

        const res = await apiCall(`/events/${eventId}/cancel-pre-registration`, "POST", { email });
        if (res?.success) {
            showMessage(`<p class="text-white">✅ Registration cancelled successfully.</p>`);
            setTimeout(() => window.location.reload(), 800);
        } else {
            showMessage(`<p class="text-white">❌ ${res?.message || "Failed to cancel registration."}</p>`);
        }
    });

    // --- Initialize ---
    await updateRegistrationState();
}



    function showMessage(msg) {
        const eventModal = document.getElementById("eventModal");
        const messageModal = document.getElementById("messageModal");
        const inner = document.getElementById("messageInner");

        // Hide event modal
        eventModal.style.display = "none";

        // Show message in message modal
        inner.innerHTML = msg;
        messageModal.style.display = "flex";

        // Handle close button
        const closeBtn = document.getElementById("messageCloseBtn");
        closeBtn.onclick = () => {
            messageModal.style.display = "none";
            inner.innerHTML = "";
            // reload if success sign present
            if (msg.includes("✅")) {
                window.location.reload();
            }
        };

        // Auto-close after 3 seconds on success
        if (msg.includes("✅")) {
            setTimeout(() => {
                messageModal.style.display = "none";
                inner.innerHTML = "";
                window.location.reload();
            }, 3000);
        }
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

    // initialize listeners after initial load
    document.addEventListener("DOMContentLoaded", () => {
        // attachCardListeners will be called after renderEvents; but we call again to be safe
        setTimeout(()=> attachCardListeners(), 250);
    });
</script>

</div>
<?php include '_footer.php'; ?>