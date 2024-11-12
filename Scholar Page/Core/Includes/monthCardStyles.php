<style>
    html, body {
        overflow-x: hidden;
    }

    #reportTitle::placeholder, #reportContent::placeholder {
        color: #000;
    }

    .month-card {
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .month-card .card-text {
        color: #6c757d;
    }

    .month-card .btn {
        background-color: #003c3c;
        color: #fff;
    }

    .month-card.selected {
        background: linear-gradient(125deg, #00b5b5 5%, #00a6a6 15%, #008080 25%, #007373 50%, #003c3c 100%);
        color: #fff;
    }

    .month-card.selected .card-title,
    .month-card.selected .card-text {
        color: #fff;
    }

</style>