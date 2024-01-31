"use strict";
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
                var articleName = item.article_name;
                updateStatus(articleId, status, articleName);
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

    function updateStatus(articleId, status, articleName) {
        var statusElement = document.querySelector('[data-article-id="' + articleId + '"]');
    
        if (statusElement) {
            var statusText = getStatusText(status);
    
            // Create a span for the status text
            var statusTextSpan = document.createElement('p');
            statusTextSpan.className = 'status-item';
            statusTextSpan.textContent = articleName + ': ' + statusText.label;
    
            // Create an image element
            var imageElement = document.createElement('img');
            imageElement.src = statusText.image;
            imageElement.alt = statusText.label;
            imageElement.className = 'status-image';
            imageElement.width = 150;
            imageElement.height = 150;
    
            while (statusElement.firstChild) {
                statusElement.removeChild(statusElement.firstChild);
            }
    
            // Append the new elements to the statusElement
            statusElement.appendChild(statusTextSpan);
            statusElement.appendChild(imageElement);
        }
    }
        

    function getStatusText(status) {
        const statusOptions = [
            { label: 'Ordered', image: 'assets/ewa.gif' },
            { label: 'In the Oven', image: 'assets/giphy.gif' },
            { label: 'Ready', image: 'assets/ready.gif' },
            { label: 'Ready for Delivery', image: 'assets/readytogo.gif' },
            { label: 'In Delivery', image: 'assets/ondelivery.gif' },
            { label: 'Delivered', image: 'assets/delivered.gif' },
        ];
    
        const statusInfo = statusOptions[status];
    
        return statusInfo || { label: 'Unknown', image: '' };   
    }
    
    

    // Execute the following code after the page has completely loaded
    window.onload = function () {
        // Disable caching to ensure fresh data
        requestData();
        startPolling();
    };
