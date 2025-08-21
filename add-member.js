const programs = {
  "College of Architecture and Fine Arts": [
    "BS Architecture",
    "B Landscape Architecture",
    "B Fine Arts Major in Visual Communication",
  ],
  "College of Arts and Letters": [
    "BA Broadcasting",
    "BA Journalism",
    "B Performing Arts (Theater Track)",
    "B Sining sa Malikhaing Pagsulat",
  ],
  "College of Business Education and Accountancy": [
    "BS Accountancy",
    "BS Business Administration Major in Business Economics",
    "BS Business Administration Major in Financial Management",
    "BS Business Administration Major in Marketing Management",
    "BS Entrepreneurship",
  ],
  "College of Criminal Justice Education": [
    "BS Criminology",
    "BA Legal Management",
  ],
  "College of Hospitality and Tourism Management": [
    "BS Hospitality Management",
    "BS Tourism Management",
  ],
  "College of Information and Communications Technology": [
    "BS Information Technology",
    "BS Information System",
    "B Library and Information Science",
  ],
  "College of Industrial Technology": [
    "BIT with specialization in Automotive",
    "BIT with specialization in Computer",
    "BIT with specialization in Drafting",
    "BIT with specialization in Electrical",
    "BIT with specialization in Electronics & Communication Technology",
    "BIT with specialization in Electronics Technology",
    "BIT with specialization in Food Processing Technology",
    "BIT with specialization in Heating, Ventilation, Air Conditioning and Refrigeration Technology (HVACR)",
    "BIT with specialization in Mechanical",
    "BIT with specialization in Mechatronics Technology",
    "BIT with specialization in Welding Technology",
  ],
  "College of Nursing": ["BS Nursing"],
  "College of Engineering": [
    "BS Civil Engineering",
    "BS Computer Engineering",
    "BS Electrical Engineering",
    "BS Electronics Engineering",
    "BS Industrial Engineering",
    "BS Manufacturing Engineering",
    "BS Mechanical Engineering",
    "BS Mechatronics Engineering",
  ],
  "College of Education": [
    "B Early Childhood Education",
    "B Elementary Education",
    "B Physical Education",
    "BSEd Major in English Minor in Mandarin",
    "BSEd in Filipino",
    "BSEd in Mathematics",
    "BSEd in Sciences",
    "BSEd in Social Studies",
    "BSEd in Values Education",
    "BTLEd Major in Home Economics",
    "BTLEd Major in Industrial Arts",
    "BTLEd Major in Information and Communication Technology",
  ],
  "College of Science": [
    "BS Biology",
    "BS Environmental Science",
    "BS Food Technology",
    "BS Math with Specialization in Applied Statistics",
    "BS Math with Specialization in Business Applications",
    "BS Math with Specialization in Computer Science",
  ],
  "College of Sports, Exercise and Recreation": [
    "BS ESS with specialization in Fitness and Sports Coaching",
    "BS ESS with specialization in Fitness and Sports Management",
  ],
  "College of Social Sciences and Philosophy": [
    "B Public Administration",
    "BS Psychology",
    "BS Social Work",
  ],
};

document.addEventListener("DOMContentLoaded", () => {
  const collegeSelect = document.getElementById("regCollege");
  const programSelect = document.getElementById("regProgram");

  // Populate colleges
  Object.keys(programs).forEach(college => {
    const opt = document.createElement("option");
    opt.value = college;
    opt.textContent = college;
    collegeSelect.appendChild(opt);
  });

  // On college change → show programs
  collegeSelect.addEventListener("change", () => {
    programSelect.innerHTML = "";
    programSelect.disabled = false;

    if (collegeSelect.value && programs[collegeSelect.value]) {
      programs[collegeSelect.value].forEach(prog => {
        const opt = document.createElement("option");
        opt.value = prog;
        opt.textContent = prog;
        programSelect.appendChild(opt);
      });
    } else {
      const opt = document.createElement("option");
      opt.textContent = "Select a College first";
      programSelect.appendChild(opt);
      programSelect.disabled = true;
    }
  });

  // Toggle password visibility
  document.querySelectorAll(".toggle-password").forEach(btn => {
    btn.addEventListener("click", () => {
      const target = document.getElementById(btn.dataset.target);
      target.type = target.type === "password" ? "text" : "password";
    });
  });

  // Form validation
  document.getElementById("addMemberForm").addEventListener("submit", (e) => {
    e.preventDefault();
    const errorBox = document.getElementById("error-box");
    errorBox.classList.add("hidden");

    const pass = document.getElementById("regPassword").value;
    const confirmPass = document.getElementById("confirmPassword").value;

    if (pass !== confirmPass) {
      showError("Passwords do not match");
      return;
    }

    alert("Member added successfully!");
  });

  function showError(msg) {
    const errorBox = document.getElementById("error-box");
    errorBox.textContent = msg;
    errorBox.classList.remove("hidden");
  }
});
