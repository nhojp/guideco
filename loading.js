function showLoading(url) {
    document.getElementById('loadingScreen').style.display = 'flex';
    setTimeout(function() {
        window.location.href = url;
    }, 5000); // Adjust the delay as needed
}
