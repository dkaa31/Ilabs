// Jam & Tanggal Real-time
function updateClock() {
  const now = new Date();
  document.getElementById("clock")?.textContent = now.toLocaleTimeString('id-ID', { hour12: false }) + ' WIB';
  document.getElementById("date")?.textContent = now.toLocaleDateString('id-ID', { 
    weekday: 'long', 
    day: '2-digit', 
    month: 'long', 
    year: 'numeric' 
  });
}
updateClock();
setInterval(updateClock, 1000);