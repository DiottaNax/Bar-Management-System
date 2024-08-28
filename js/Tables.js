document.addEventListener("DOMContentLoaded", function () {
  /**
   * Block of code to make date selectpr work
   */
  const dateSelector = document.getElementById("date-selector");
  const tableContainer = document.getElementById("table-container");

  dateSelector.addEventListener("change", function () {
    window.location.href = "?date=" + this.value;
  });

  /**
   * Block of code to make add new table link work
   */
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

    fetch("api/add_table.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        console.log(response.body.getReader.toString());
        return response.json();
      })
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
