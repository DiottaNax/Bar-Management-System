document.addEventListener("DOMContentLoaded", function () {
  /**
   * Block of code to make date selectpr work
   */
  const dateSelector = document.getElementById("date-selector");
  const tableContainer = document.getElementById("table-container");

  dateSelector.addEventListener("change", function () {
    const selectedDate = this.value;

    // Use AJAX to fetch tables for the selected date
    fetch(`./api/get_tables.php?date=${selectedDate}`)
      .then((response) => response.json())
      .then((tables) => {
        tableContainer.innerHTML = "";

        tables.forEach((table) => {
          const tableHtml = `
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">Table ${
                                      table.name
                                    }</h5>
                                    <p class="card-text">Seats: ${
                                      table.seats
                                    }</p>
                                    <p class="card-text">Created: ${new Date(
                                      table.creationTimestamp
                                    ).toLocaleString()}</p>
                                    <div class="mt-auto">
                                        <a href="#" class="btn btn-primary me-2" data-table-id="${
                                          table.tableId
                                        }">Add Products</a>
                                        <a href="#" class="btn btn-success" data-table-id="${
                                          table.tableId
                                        }">Pay</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
          tableContainer.insertAdjacentHTML("beforeend", tableHtml);
        });
      })
      .catch((error) => console.error("Error:", error));
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
