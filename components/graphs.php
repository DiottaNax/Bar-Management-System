<main class="container mt-5">
    <h1 class="mb-4">Sales Dashboard</h1>

    <div class="row mb-4">
        <div class="col-md-3">
            <label for="start-date" class="form-label">Start Date:</label>
            <input type="date" id="start-date" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="end-date" class="form-label">End Date:</label>
            <input type="date" id="end-date" class="form-control">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button id="update-chart" class="btn btn-primary">Update Chart</button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="./js/DrawSalesGraph.js"></script>
