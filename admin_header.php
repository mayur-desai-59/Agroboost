<!-- admin_header.php -->
<style>
.topbar {
    margin-left: 200px; /* Same width as sidebar */
    height: 80px;
    background-color: #28a745;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    color: white;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 999;
}

.topbar .welcome {
    font-size: 22px;
    font-weight: bold;
    text-align: center;
    flex-grow: 1;
}

.topbar .datetime {
    text-align: right;
    font-size: 14px;
}

.topbar .datetime div {
    margin: 2px 0;
}
</style>

<div class="topbar">
    <div class="welcome">SAI AGRO FERTILIZERS</div>

    <div class="datetime">
        <div id="date"><?php echo date('l d M, Y'); ?></div>
        <div id="clock"><?php echo date('h:i:s A'); ?></div>
    </div>
</div>

<script>
// Live Clock Script
function updateClock() {
    const now = new Date();

    const date = now.toLocaleDateString('en-GB', {
        weekday: 'long',
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });

    const time = now.toLocaleTimeString('en-IN', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
    });

    document.getElementById('date').innerText = date;
    document.getElementById('clock').innerText = time;
}

setInterval(updateClock, 1000);
updateClock();
</script>
