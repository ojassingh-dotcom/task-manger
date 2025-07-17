<?php
session_start();
include 'partials/new_dbconnect.php';
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: login_page.php");
    exit;
}

$allDates = [];
if (isset($_SESSION['rollno'])) {
    $rollno = $_SESSION['rollno'];
    $sql = "SELECT due_date, status FROM tasks WHERE assigned_to='$rollno'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $allDates[] = [
                'date' => date('Y-m-d', strtotime($row['due_date'])),
                'status' => $row['status']
            ];
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Task Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0f2ff, #80bfff);
      margin: 0;
    }
    .navbar-custom {
      background: #fff;
      padding: 12px 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }
    .navbar-brand {
      font-weight: 700;
      font-size: 24px;
      color: #0d6efd;
    }
    .btn-logout {
      background: #0d6efd;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 5px 12px;
      font-size: 14px;
    }
    .main-layout {
      max-width: 1200px;
      margin: 40px auto;
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
      padding: 0 20px;
    }
    .calendar-box, .right-panel {
      background: white;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .calendar-box {
      flex: 1 1 300px;
      padding: 20px;
      cursor: pointer;
    }
    .calendar-header {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 10px;
    }
    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 5px;
      font-size: 12px;
    }
    .calendar-grid div {
      background: #f4f9ff;
      border: 1px solid #dbeeff;
      text-align: center;
      padding: 10px;
      border-radius: 4px;
    }
    .day-header {
      font-weight: 600;
      color: #0d6efd;
    }
    .right-panel {
      flex: 2 1 600px;
      padding: 40px;
    }
    .btn-tile {
      background: white;
      border: 2px solid #0d6efd;
      border-radius: 15px;
      padding: 25px;
      text-align: center;
      display: block;
      color: inherit;
      text-decoration: none;
      transition: 0.3s;
    }
    .btn-tile:hover {
      background: #0d6efd;
      color: white;
      transform: translateY(-4px);
    }
    .icon {
      font-size: 40px;
    }
    .highlight-red {
      background: #ff4d4d !important;
      color: white;
      font-weight: bold;
    }
    .highlight-yellow {
      background: #ffe066 !important;
      color: #333;
      font-weight: bold;
    }
    .highlight-green {
      background: #51cf66 !important;
      color: white;
      font-weight: bold;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
  <span class="navbar-brand">Student Task Manager</span>
  <div class="d-flex align-items-center gap-3">
    <div class="text-center">
      <div style="font-size: 24px; color: #0d6efd;">👤</div>
      <div style="font-size: 13px;">@<?= isset($_SESSION['username']) ? $_SESSION['username'] : 'User' ?></div>
    </div>
    <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
  </div>
</nav>

<div class="main-layout">
  <div class="calendar-box" data-bs-toggle="modal" data-bs-target="#calendarModal">
    <div class="calendar-header">
      <h6 id="miniCalendarMonth" class="text-primary"></h6>
    </div>
    <div class="calendar-grid" id="calendarMini"></div>
  </div>
  <div class="right-panel">
    <div class="text-center mb-4">
      <h2>📚 Welcome to Your Dashboard</h2>
      <p>Choose an action to stay on top of your academic goals.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6"><a href="my_tasks.php" class="btn-tile"><div class="icon">📝</div><h4>My Tasks</h4></a></div>
      <div class="col-md-6"><a href="profile.php" class="btn-tile"><div class="icon">👤</div><h4>Profile</h4></a></div>
      <div class="col-md-6"><a href="submit_task.php" class="btn-tile"><div class="icon">📥</div><h4>Submit Tasks</h4></a></div>
      <div class="col-md-6"><a href="user_progress_bar.php" class="btn-tile"><div class="icon">📊</div><h4>Progress</h4></a></div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="calendarModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content p-3">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <button class="btn btn-sm btn-outline-primary" onclick="changeMonthInModal(-1)">←</button>
        <h5 id="calendarMonth" class="mb-0 text-primary"></h5>
        <button class="btn btn-sm btn-outline-primary" onclick="changeMonthInModal(1)">→</button>
      </div>
      <div class="calendar-grid" id="calendarFull"></div>
    </div>
  </div>
</div>

<script>
const monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
const deadlineDates = <?= json_encode($allDates) ?>;

function getStatusClass(dateStr) {
  for (const obj of deadlineDates) {
    if (obj.date === dateStr) {
      if (obj.status === "pending") return "highlight-red";
      if (obj.status === "in_progress") return "highlight-yellow";
      if (obj.status === "completed") return "highlight-green";
    }
  }
  return "";
}

function renderCalendar(month, year, gridId, titleId = null) {
  const grid = document.getElementById(gridId);
  const title = titleId ? document.getElementById(titleId) : null;
  if (!grid) return;
  grid.innerHTML = "";
  if (title) title.textContent = `${monthNames[month]} ${year}`;
  else if (gridId === "calendarMini") {
    const miniTitle = document.getElementById("miniCalendarMonth");
    if (miniTitle) miniTitle.textContent = `${monthNames[month]} ${year}`;
  }

  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"].forEach(day => {
    const el = document.createElement("div");
    el.className = "day-header";
    el.textContent = day;
    grid.appendChild(el);
  });

  for (let i = 0; i < firstDay; i++) grid.appendChild(document.createElement("div"));

  for (let d = 1; d <= daysInMonth; d++) {
    const cell = document.createElement("div");
    const dateStr = `${year}-${String(month+1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
    cell.textContent = d;
    const statusClass = getStatusClass(dateStr);
    if (statusClass) cell.classList.add(statusClass);
    grid.appendChild(cell);
  }
}

function changeMonthInModal(offset) {
  currentMonth += offset;
  if (currentMonth > 11) currentMonth = 0, currentYear++;
  else if (currentMonth < 0) currentMonth = 11, currentYear--;
  renderCalendar(currentMonth, currentYear, "calendarFull", "calendarMonth");
}

renderCalendar(currentMonth, currentYear, "calendarMini");
document.getElementById("calendarModal").addEventListener("shown.bs.modal", () => {
  renderCalendar(currentMonth, currentYear, "calendarFull", "calendarMonth");
});
</script>
</body>
</html>