<!DOCTYPE html>
<?php include_once "config.php"; ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercure SSE with JWT</title>
</head>
<body>
<h1>Live Updates</h1>
<div id="events"></div>

<script>
    async function getJWT() {
        const response = await fetch("jwt.php");
        const data = await response.json();

        // The below, is to set the cookie up, so you can use it in the EventSource withCredentials.
        // This is NOT needed, if you're sending token in the query string.
        document.cookie = `mercureAuthorization=${data.token}; path=/; secure; samesite=strict`;

        return data.token;
    }

    async function startListening() {
       const token =  await getJWT(); // Set the cookie

        console.log('token:', token);
        const eventSource = new EventSource(`<?php echo MERCURE_HUB_URL; ?>?topic=https://example.com/news&authorization=${token}`);

        //const eventSource = new EventSource(`<?php //echo MERCURE_HUB_URL; ?>//?topic=https://example.com/news`, {
        //     withCredentials: true
        // });

        eventSource.onmessage = function(event) {
            const data = JSON.parse(event.data);
            const eventDiv = document.getElementById("events");
            eventDiv.innerHTML += `<p>${data.timestamp}: ${data.message}</p>`;
        };

        eventSource.onerror = function(event) {
            console.error("SSE Error", event);
        };
    }

    startListening();
</script>
</body>
</html>
