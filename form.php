<!DOCTYPE html>
<html>
<head>
    <title>PHP SMS</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css" />
</head>
<body>

    <h1>PHP SMS</h1>
    <form method="post" action="send.php">
        <label for="number">Number</label>
        <input type="text" name="number" id="number" required />
        <label for="message">Message</label>
        <textarea name="message" id="message" required></textarea>
        <fieldset>
            <legend>Provider</legend>
            <button type="button" onclick="selectProvider('infobip')">Infobip</button>
        </fieldset>
        <input type="hidden" id="selectedProvider" name="provider" value="infobip" />
        <button type="submit">Send</button>
    </form>

    <script>
        function selectProvider(provider) {
            document.getElementById('selectedProvider').value = provider;
            console.log('Selected provider:', provider);
        }
    </script>
</body>
</html>
