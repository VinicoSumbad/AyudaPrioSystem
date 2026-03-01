<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>APS Dashboard</title>

<style>

/* RESET + GLOBAL */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Segoe UI', sans-serif;
}

body {
  background-color: #f3f4f6;
}

/* NAVBAR */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 18px 60px;
  background-color: #e5e7eb;
}

.logo {
  font-size: 14px;
  font-weight: 500;
  letter-spacing: 1px;
}

.navbar nav a {
  text-decoration: none;
  margin-left: 20px;
  color: #333;
  font-size: 14px;
}

/* CONTAINER */
.container {
  padding: 60px 80px;
}

/* SECTION TITLE */
.section-title {
  font-size: 28px;
  color: #2e2b5f;
  margin-bottom: 30px;
}

/* SUMMARY CARDS */
.cards {
  display: flex;
  gap: 25px;
  flex-wrap: wrap;
  margin-bottom: 50px;
}

.card {
  background: white;
  padding: 25px;
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
  flex: 1;
  min-width: 200px;
  transition: 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
}

.card h3 {
  font-size: 14px;
  color: #777;
  margin-bottom: 10px;
}

.card p {
  font-size: 26px;
  font-weight: bold;
  color: #2e2b5f;
}

/* PRIORITY COLORS */
.red { color: #e53935; }
.orange { color: #fb8c00; }
.yellow { color: #fbc02d; }

/* SECTION BOX */
.section {
  background: white;
  padding: 30px;
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
  margin-bottom: 50px;
}

/* FLEX GRID */
.flex {
  display: flex;
  gap: 40px;
  flex-wrap: wrap;
}

.box {
  flex: 1;
  min-width: 250px;
}

/* TABLE */
table {
  width: 100%;
  border-collapse: collapse;
}

th {
  text-align: left;
  font-size: 14px;
  color: #666;
  padding-bottom: 15px;
}

td {
  padding: 15px 0;
  border-top: 1px solid #eee;
  font-size: 14px;
  color: #444;
}

/* BADGES */
.badge {
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.badge-red {
  background: #fdecea;
  color: #e53935;
}

.badge-orange {
  background: #fff3e0;
  color: #fb8c00;
}

.badge-yellow {
  background: #fffde7;
  color: #fbc02d;
}

.status {
  background: #e3f2fd;
  color: #1e88e5;
}

/* FOOTER */
.footer {
  text-align: center;
  padding: 20px;
  color: #777;
  font-size: 13px;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
  <div class="logo">AYUDA PRIORITIZATION SYSTEM</div>
  <nav>
    <a href="#">Dashboard</a>
    <a href="#">Households</a>
    <a href="#">Reports</a>
    <a href="#">Logout</a>
  </nav>
</div>

<div class="container">

<h1 class="section-title">Disaster Response Dashboard</h1>

<!-- SUMMARY CARDS -->
<div class="cards">
  <div class="card">
    <h3>Total Households</h3>
    <p>150</p>
  </div>

  <div class="card">
    <h3>Most Priority</h3>
    <p class="red">45</p>
  </div>

  <div class="card">
    <h3>Moderate Priority</h3>
    <p class="orange">60</p>
  </div>

  <div class="card">
    <h3>Least Priority</h3>
    <p class="yellow">45</p>
  </div>
</div>

<!-- DISASTER OVERVIEW -->
<div class="section">
  <h2 class="section-title">Disaster Overview</h2>
  <div class="flex">
    <div class="box">
      <p><strong>Type:</strong> Typhoon</p>
      <p><strong>Destroyed Houses:</strong> 20</p>
      <p><strong>Major Damage:</strong> 35</p>
    </div>
    <div class="box">
      <p><strong>Evacuation Center Population:</strong> 120</p>
      <p><strong>Last Updated:</strong> Feb 24, 2026</p>
    </div>
  </div>
</div>

<!-- RANK TABLE -->
<div class="section">
  <h2 class="section-title">Ranked Households</h2>
  <table>
    <tr>
      <th>Rank</th>
      <th>Head</th>
      <th>Score</th>
      <th>Category</th>
      <th>Status</th>
    </tr>

    <tr>
      <td>1</td>
      <td>Juan Dela Cruz</td>
      <td>85%</td>
      <td><span class="badge badge-red">Most Priority</span></td>
      <td><span class="badge status">AIDED</span></td>
    </tr>

    <tr>
      <td>2</td>
      <td>Maria Santos</td>
      <td>72%</td>
      <td><span class="badge badge-red">Most Priority</span></td>
      <td>Pending</td>
    </tr>

    <tr>
      <td>3</td>
      <td>Pedro Reyes</td>
      <td>63%</td>
      <td><span class="badge badge-orange">Moderate</span></td>
      <td>Pending</td>
    </tr>

  </table>
</div>

</div>

<div class="footer">
© 2026 APS | San Nicolas LGU
</div>

</body>
</html>