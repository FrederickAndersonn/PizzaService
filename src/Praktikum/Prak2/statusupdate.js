// statusupdate.js

var request = new XMLHttpRequest(); // global variable for XMLHttpRequest

// Define process function in the global scope
function process(jsonString) {
    // Parse the JSON data
    var jsonData = JSON.parse(jsonString);

    // Check if jsonData is not empty
    if (jsonData.length > 0) {
        // Iterate through the data and update the status
        jsonData.forEach(function (item) {
            var articleId = item.article_id;
            var status = item.status;
            updateStatus(articleId, status);
        });
    } else {
        // Handle the case where no data is returned
        console.error("No data returned from the server");
    }
}

function requestData() {
    request.open("GET", "KundenStatus.php");
    request.onreadystatechange = processData;
    request.send(null);
}

function processData() {
    if (request.readyState == 4) { // Transmission = DONE
        if (request.status == 200) { // HTTP-Status = OK
            if (request.responseText != null) {
                process(request.responseText); // Process the data
            } else {
                console.error("Document is empty");
            }
        } else {
            console.error("Transmission failed");
        }
    } else {
        // Transmission is still in progress
    }
}

function startPolling() {
    // Start polling every 2 seconds
    window.setInterval(requestData, 2000);
}

function updateStatus(articleId, status) {
   
    var statusElement = document.querySelector('[data-article-id="' + articleId + '"]');
    
    if (statusElement) {
        statusElement.textContent = "Status: " + getStatusText(status);
    }
}

function getStatusText(status) {
    var statusOptions = [
        'Bestellt',
        'In Bearbeitung',
        'Fertig zur Lieferung',
        'Unterwegs',
        'Geliefert'
    ];

    return statusOptions[status] || 'Unknown';
}

// Execute the following code after the page has completely loaded
window.onload = function () {
    // Disable caching to ensure fresh data
    requestData();
    startPolling();
};
