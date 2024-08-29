function addEmployee() {
  const form = document.getElementById("addEmployeeForm");
  const formData = new FormData(form);
  const employeeData = Object.fromEntries(formData);

  // Convert checkbox values to boolean
  ["isWaiter", "isStorekeeper", "isKitchenStaff", "isAdmin"].forEach((role) => {
    employeeData[role] = form.elements[role].checked ? 1 : 0;
  });

  fetch("./api/update_employees.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(employeeData),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        location.reload(); // Reload the page to show the new employee
      }
    })
    .catch((error) => console.error("Error:", error));
}

function setEmployeeId(employeeId) {
  $("#modifyEmployeeId").val(employeeId);
}

function updateEmployee() {
  const form = document.getElementById("modifyEmployeeForm");
  const formData = new FormData(form);
  const employeeData = Object.fromEntries(formData);

  // Convert checkbox values to boolean
  ["isWaiter", "isStorekeeper", "isKitchenStaff", "isAdmin"].forEach((role) => {
    employeeData[role] = form.elements[role].checked ? 1 : 0;
  });

  console.log("\n\n sending:     " + JSON.stringify(employeeData));

  fetch("./api/update_employees.php", {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(employeeData),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        location.reload(); // Reload the page to show the updated employee info
      }
    })
    .catch((error) => console.error("Error:", error));
}

function deleteEmployee(employeeId) {
  if (confirm("Do you really want to delete this employee?")) {
    fetch("./api/update_employees.php?employeeId=" + employeeId, {
      method: "DELETE",
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          location.reload(); // Reload the page to remove the deleted employee
        }
      })
      .catch((error) => console.error("Error:", error));
  }
}
