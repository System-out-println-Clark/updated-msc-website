document.addEventListener("DOMContentLoaded", () => {
  const membersTableBody = document.getElementById("membersTableBody");
  const selectAll = document.getElementById("selectAll");
  const bulkAction = document.getElementById("bulkAction");

  // Sample data
  const members = [
    {
      id: "MSC-001",
      first: "Juan",
      middle: "Santos",
      last: "Dela Cruz",
      suffix: "",
      email: "juan@email.com",
      phone: "09123456789",
      gender: "Male",
      position: "Member",
      studentNo: "2021-12345",
      program: "BS Information Technology",
      year: "3",
      college: "College of ICT",
      section: "3A",
      address: "Bulacan",
      facebook: "fb.com/juan"
    },
    {
      id: "MSC-002",
      first: "Maria",
      middle: "Reyes",
      last: "Lopez",
      suffix: "",
      email: "maria@email.com",
      phone: "09987654321",
      gender: "Female",
      position: "Officer",
      studentNo: "2021-54321",
      program: "BS Accountancy",
      year: "2",
      college: "College of Business Education and Accountancy",
      section: "2B",
      address: "Malolos",
      facebook: "fb.com/maria"
    }
  ];

  // Render members
  function renderTable(data) {
    membersTableBody.innerHTML = "";
    data.forEach(member => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td><input type="checkbox" class="rowCheckbox"></td>
        <td>${member.id}</td>
        <td>${member.first}</td>
        <td>${member.middle}</td>
        <td>${member.last}</td>
        <td>${member.suffix}</td>
        <td>${member.email}</td>
        <td>${member.phone}</td>
        <td>${member.gender}</td>
        <td>${member.position}</td>
        <td>${member.studentNo}</td>
        <td>${member.program}</td>
        <td>${member.year}</td>
        <td>${member.college}</td>
        <td>${member.section}</td>
        <td>${member.address}</td>
        <td>${member.facebook}</td>
      `;
      membersTableBody.appendChild(row);
    });
  }

  renderTable(members);

  // Select all checkboxes
  selectAll.addEventListener("change", () => {
    document.querySelectorAll(".rowCheckbox").forEach(cb => {
      cb.checked = selectAll.checked;
    });
  });

  // Bulk Action
  document.getElementById("applyBtn").addEventListener("click", () => {
    alert(`Applying action: ${bulkAction.value}`);
  });

  // Sort last name
  let asc = true;
  document.getElementById("sortLastName").addEventListener("click", () => {
    const sorted = [...members].sort((a, b) => {
      if (asc) return a.last.localeCompare(b.last);
      else return b.last.localeCompare(a.last);
    });
    asc = !asc;
    renderTable(sorted);
  });
});
