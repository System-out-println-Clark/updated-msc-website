document.addEventListener("DOMContentLoaded", async () => {

  // Sidebar toggle
  const sidebarToggle = document.getElementById('sidebarToggle');
  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', () => {
      document.querySelector('.sidebar').classList.toggle('open');
    });
  }

  // Tabs
  document.querySelectorAll('.tab-button').forEach(tab => {
    tab.addEventListener('click', function() {
      const targetTab = this.dataset.tab;
      document.querySelectorAll('.tab-button').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
      this.classList.add('active');
      const content = document.getElementById(targetTab);
      if (content) content.classList.add('active');
    });
  });

  // Generic API call
  async function apicall(url, method = "GET", data = null, isFormData = false) {
    const options = { method, headers: {} };
    if (isFormData) {
      options.body = data;
      if (method === "PUT" || method === "PATCH") {
        url += (url.includes("?") ? "&" : "?") + `_method=${method}`;
        options.method = "POST";
      }
    } else if (data) {
      options.headers["Content-Type"] = "application/json";
      options.body = JSON.stringify(data);
    }
    try {
      const res = await fetch(url, options);
      return await res.json();
    } catch (err) {
      console.error("API call error:", err);
      return null;
    }
  }

  // Load officers into select dropdown
  async function loadOfficers() {
    const officerSelect = document.getElementById("officerOptions");
    if (!officerSelect) return;

    officerSelect.innerHTML = '<option value="">Select Committee Head</option>';

    const result = await apicall("./api/students?role=officer", "GET");
    const officers = result?.data?.students || [];

    if (officers.length > 0) {
      officers.forEach(officer => {
        const option = document.createElement("option");
        option.value = officer.id;
        option.textContent = `${officer.first_name} ${officer.last_name}`;
        officerSelect.appendChild(option);
      });
    } else {
      officerSelect.innerHTML += '<option value="">No officers found</option>';
    }
  }

  // Load committees
  async function loadCommittees() {
    const container = document.getElementById("committee-list");
    if (!container) return;
    container.innerHTML = ""; // clear old

    const result = await apicall("./api/committees", "GET");
    const committees = result?.data || [];

    if (committees.length === 0) {
      container.innerHTML = "<p class='text-gray-400'>No committees found.</p>";
      return;
    }

    committees.forEach(c => {
      const card = document.createElement("div");
      card.classList.add("committee-card");
      card.innerHTML = `
        <h3 class="committee-title">${c.name}</h3>
        <p class="committee-description">${c.description || ""}</p>
        <div class="committee-actions">
          <button class="btn btn-info view-btn">View</button>
          <button class="btn btn-secondary edit-btn">Edit</button>
          <button class="btn btn-danger delete-btn">Delete</button>
        </div>
      `;
      container.appendChild(card);

      // Attach buttons dynamically
      card.querySelector('.view-btn')?.addEventListener('click', () => alert(`Viewing ${c.name}`));
      card.querySelector('.edit-btn')?.addEventListener('click', () => alert(`Editing ${c.name}`));
      card.querySelector('.delete-btn')?.addEventListener('click', async () => {
        if (confirm(`Delete ${c.name}?`)) {
          await apicall(`./api/committees/${c.id}`, "DELETE");
          loadCommittees();
        }
      });
    });
  }

  // Add Committee Form
  const addForm = document.getElementById('addCommitteeForm');
  if (addForm) {
    addForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const committeeName = document.getElementById("committeeName").value.trim();
      const committeeDescription = document.getElementById("committeeDescription").value.trim();
      const headId = document.getElementById("officerOptions")?.value;

      if (!committeeName) {
        alert("Committee name is required!");
        return;
      }

      const newCommittee = {
        name: committeeName,
        description: committeeDescription,
        members: headId ? [{ student_id: headId, position: "head" }] : []
      };

      console.log("Payload to backend:", newCommittee); // debug

      const res = await apicall("./api/committees", "POST", newCommittee);

      if (res?.success) {
        alert("Committee created!");
        addForm.reset();
        document.querySelector('[data-tab="committee-list"]').click(); // switch tab
        loadCommittees();
      } else {
        alert("Failed: " + (res?.message || "Unknown error"));
      }
    });
  }

  // Initial load
  await loadCommittees();
  await loadOfficers();
});
