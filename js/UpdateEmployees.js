function addEmployee() {
  const form = document.getElementById("addEmployeeForm");
  const formData = new FormData(form);
  const employeeData = Object.fromEntries(formData);

  // Convert checkbox values to boolean
  ["isWaiter", "isStorekeeper", "isKitchenStaff", "isAdmin"].forEach((role) => {
    employeeData[role] = form.elements[role].checked ? 1 : 0;
  });

  // Try to add a new employee to the database when the user clicks the submit button
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

// Set the employee id to the modify employee form
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

  // Try to update the employee info in the database when the user clicks the submit button
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

// Try to delete an employee from the database when the user clicks the delete button
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
