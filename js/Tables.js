document.addEventListener("DOMContentLoaded", function () {
  
  const dateSelector = document.getElementById("date-selector");
  const tableContainer = document.getElementById("table-container");

  dateSelector.addEventListener("change", function () {
    // Redirection to the page with the selected date
    window.location.href = "?date=" + this.value;
  });

  const offcanvas = new bootstrap.Offcanvas(
    document.getElementById("top-menu")
  );
  const addTableLink = document.getElementById("new-table-link");
  const addTableForm = document.getElementById("add-table-form");

  addTableLink.addEventListener("click", function () {
    offcanvas.hide();
  });

  addTableForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(addTableForm);

    // Try to add a new table to the database when the user clicks the submit button
    fetch("api/add_table.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          location.reload(); // Reload the page to show the new table
        } else {
          alert("Error adding table: " + data.error);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });
});
