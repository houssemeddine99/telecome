<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Rental Dashboard - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- Chart.js Library -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 

  <style>
    .rental-form {
      background-color: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
    }

    .rental-form input, .rental-form select {
      margin-bottom: 15px;
      padding: 12px;
      width: 100%;
      border: 1px solid #ddd;
      border-radius: 6px;
      box-sizing: border-box;
      font-size: 1em;
    }

    .rental-form input[type="submit"] {
      background-color: #4154f1;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 1em;
      border-radius: 6px;
      transition: background-color 0.3s ease;
    }

    .rental-form input[type="submit"]:hover {
      background-color: #5969f3;
    }

    .chart {
      margin-bottom: 30px;
    }
  </style>
</head>

<body>
<?php
// Configuration for the database connection
$dsn = 'mysql:host=localhost;dbname=bibliotheque';
$username = 'root';
$password = '';

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define the user ID (assuming it's 1 for the example)
    $user_id = 1;

    // Handle rental deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_rental'])) {
        $delete_rental_id = $_POST['delete_rental_id'];

        // Verify the rental ID and user ID
        if (!empty($delete_rental_id) && !empty($user_id)) {
            // Delete the rental
            $delete_query = "DELETE FROM rentals WHERE id = :delete_rental_id AND user_id = :user_id";
            $delete_stmt = $pdo->prepare($delete_query);
            $delete_stmt->bindParam(':delete_rental_id', $delete_rental_id, PDO::PARAM_INT);
            $delete_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            if ($delete_stmt->execute()) {
                echo "<p>Rental ID $delete_rental_id has been deleted successfully.</p>";
            } else {
                echo "<p>Failed to delete Rental ID $delete_rental_id.</p>";
            }
        } else {
            echo "<p>Invalid rental ID or user ID.</p>";
        }
    }

    // Handle new rental submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_rental'])) {
        $duration = $_POST['duration'];
        $rental_date = date('Y-m-d');
        $return_date = date('Y-m-d', strtotime("+$duration days"));
        $status = 'Active';

        // Insert new rental
        $insert_query = "INSERT INTO rentals (user_id, rental_date, return_date, status) 
                         VALUES (:user_id, :rental_date, :return_date, :status)";
        $insert_stmt = $pdo->prepare($insert_query);
        $insert_stmt->execute([
            ':user_id' => $user_id,
            ':rental_date' => $rental_date,
            ':return_date' => $return_date,
            ':status' => $status
        ]);

        $new_rental_id = $pdo->lastInsertId();

        echo "<p>New rental added successfully! Rental ID: $new_rental_id</p>";
    }

    // Fetch rent history for the logged-in user
    $search_title = isset($_GET['search_title']) ? $_GET['search_title'] : '';
    $search_status = isset($_GET['search_status']) ? $_GET['search_status'] : '';
    $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'rental_date';
    $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'DESC';

    $query = "SELECT r.*, DATEDIFF(r.return_date, r.rental_date) AS rent_duration 
              FROM rentals r
              WHERE r.user_id = :user_id";

    // Add search conditions
    if (!empty($search_title)) {
        $query .= " AND r.id LIKE :search_title";
    }
    if (!empty($search_status)) {
        $query .= " AND r.status = :search_status";
    }

    // Add sorting
    $query .= " ORDER BY $sort_by $sort_order";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    if (!empty($search_title)) {
        $stmt->bindValue(':search_title', "%$search_title%", PDO::PARAM_STR);
    }
    if (!empty($search_status)) {
        $stmt->bindParam(':search_status', $search_status, PDO::PARAM_STR);
    }
    $stmt->execute();
    $rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for statistics
    $status_counts = array_count_values(array_column($rentals, 'status'));
    $duration_counts = array_count_values(array_column($rentals, 'rent_duration'));

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>My Rent History</h1>
    </div>

    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Rent History</h5>

              <!-- Search and filter form -->
              <form method='get' class='mb-3'>
                <div class="row g-3">
                  <div class="col-md-3">
                    <input type='text' class="form-control" name='search_title' placeholder='Search by rental ID' value='<?php echo htmlspecialchars($search_title); ?>'>
                  </div>
                  <div class="col-md-3">
                    <select name='search_status' class="form-select">
                      <option value=''>All Statuses</option>
                      <option value='Active' <?php echo ($search_status == 'Active' ? "selected" : ""); ?>>Active</option>
                      <option value='Returned' <?php echo ($search_status == 'Returned' ? "selected" : ""); ?>>Returned</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <select name='sort_by' class="form-select">
                      <option value='rental_date' <?php echo ($sort_by == 'rental_date' ? "selected" : ""); ?>>Rental Date</option>
                      <option value='return_date' <?php echo ($sort_by == 'return_date' ? "selected" : ""); ?>>Return Date</option>
                      <option value='rent_duration' <?php echo ($sort_by == 'rent_duration' ? "selected" : ""); ?>>Rent Duration</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <select name='sort_order' class="form-select">
                      <option value='DESC' <?php echo ($sort_order == 'DESC' ? "selected" : ""); ?>>Descending</option>
                      <option value='ASC' <?php echo ($sort_order == 'ASC' ? "selected" : ""); ?>>Ascending</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search & Sort</button>
                  </div>
                </div>
              </form>

              <!-- Rent history table -->
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Rental ID</th>
                    <th>Rental Date</th>
                    <th>Return Date</th>
                    <th>Rent Duration</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
    <?php foreach ($rentals as $rental): ?>
        <tr>
            <td><?php echo htmlspecialchars($rental['id']); ?></td>
            <td><?php echo htmlspecialchars($rental['rental_date']); ?></td>
            <td><?php echo htmlspecialchars($rental['return_date']); ?></td>
            <td><?php echo htmlspecialchars($rental['rent_duration']); ?> days</td>
            <td><?php echo htmlspecialchars($rental['status']); ?></td>
            <td>
                <form method="post" onsubmit="return confirm('Are you sure you want to delete this rental?');">
                    <input type="hidden" name="delete_rental_id" value="<?php echo htmlspecialchars($rental['id']); ?>">
                    <button type="submit" class="btn btn-danger btn-sm" name="delete_rental">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Rental Status Distribution</h5>
              <canvas id="statusChart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Rental Duration Distribution</h5>
              <canvas id="durationChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <script>
document.addEventListener('DOMContentLoaded', (event) => {
    // Status Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode(array_keys($status_counts)); ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($status_counts)); ?>,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Rental Status Distribution'
                }
            }
        }
    });

    // Duration Chart
    new Chart(document.getElementById('durationChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_keys($duration_counts)); ?>,
            datasets: [{
                label: 'Number of Rentals',
                data: <?php echo json_encode(array_values($duration_counts)); ?>,
                backgroundColor: '#4BC0C0'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Rental Duration Distribution'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">NiceAdmin</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Atque rerum nesciunt</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sit rerum fuga</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>Dicta reprehenderit</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>4 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">3</span>
          </a><!-- End Messages Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 3 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-1.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Maria Hudson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>4 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-2.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Anna Nelson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>6 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-3.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>David Muldon</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>8 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>

          </ul><!-- End Messages Dropdown Items -->

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/yuki.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">Mr. Abdel Al</span>
            </a><!-- End Profile Iamge Icon -->

<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
  <li class="dropdown-header">
  <h6>Houssem Eddine Abdel Al</h6>
  <span>Web developper</span>
  </li>
  <li>
    <hr class="dropdown-divider">
  </li>

  <li>
    <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
      <i class="bi bi-person"></i>
      <span>My Profile</span>
    </a>
  </li>
  <li>
    <hr class="dropdown-divider">
  </li>

  <li>
    <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
      <i class="bi bi-gear"></i>
      <span>Account Settings</span>
    </a>
  </li>
  <li>
    <hr class="dropdown-divider">
  </li>

  <li>
    <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
      <i class="bi bi-question-circle"></i>
      <span>Need Help?</span>
    </a>
  </li>
  <li>
    <hr class="dropdown-divider">
  </li>

  <li>
    <a class="dropdown-item d-flex align-items-center" href="#">
      <i class="bi bi-box-arrow-right"></i>
      <span>Sign Out</span>
    </a>
  </li>

</ul><!-- End Profile Dropdown Items -->
</li><!-- End Profile Nav -->

</ul>
</nav><!-- End Icons Navigation -->

</header><!-- End Header -->

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

<li class="nav-item">
<a class="nav-link " href="index.html">
<i class="bi bi-grid"></i>
<span>Dashboard</span>
</a>
</li><!-- End Dashboard Nav -->

<li class="nav-item">
<a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
<i class="bi bi-menu-button-wide"></i><span>Components</span><i class="bi bi-chevron-down ms-auto"></i>
</a>
<ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
<li>
  <a href="components-alerts.html">
    <i class="bi bi-circle"></i><span>Alerts</span>
  </a>
</li>
<li>
  <a href="components-accordion.html">
    <i class="bi bi-circle"></i><span>Accordion</span>
  </a>
</li>
<li>
  <a href="components-badges.html">
    <i class="bi bi-circle"></i><span>Badges</span>
  </a>
</li>
<li>
  <a href="components-breadcrumbs.html">
    <i class="bi bi-circle"></i><span>Breadcrumbs</span>
  </a>
</li>
<li>
  <a href="components-buttons.html">
    <i class="bi bi-circle"></i><span>Buttons</span>
  </a>
</li>
<li>
  <a href="components-cards.html">
    <i class="bi bi-circle"></i><span>Cards</span>
  </a>
</li>
<li>
  <a href="components-carousel.html">
    <i class="bi bi-circle"></i><span>Carousel</span>
  </a>
</li>
<li>
  <a href="components-list-group.html">
    <i class="bi bi-circle"></i><span>List group</span>
  </a>
</li>
<li>
  <a href="components-modal.html">
    <i class="bi bi-circle"></i><span>Modal</span>
  </a>
</li>
<li>
  <a href="components-tabs.html">
    <i class="bi bi-circle"></i><span>Tabs</span>
  </a>
</li>
<li>
  <a href="components-pagination.html">
    <i class="bi bi-circle"></i><span>Pagination</span>
  </a>
</li>
<li>
  <a href="components-progress.html">
    <i class="bi bi-circle"></i><span>Progress</span>
  </a>
</li>
<li>
  <a href="components-spinners.html">
    <i class="bi bi-circle"></i><span>Spinners</span>
  </a>
</li>
<li>
  <a href="components-tooltips.html">
    <i class="bi bi-circle"></i><span>Tooltips</span>
  </a>
</li>
</ul>
</li><!-- End Components Nav -->
<li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Forms</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="forms-elements.html">
              <i class="bi bi-circle"></i><span>Form Elements</span>
            </a>
          </li>
          <li>
            <a href="forms-layouts.html">
              <i class="bi bi-circle"></i><span>Form Layouts</span>
            </a>
          </li>
          <li>
            <a href="forms-editors.html">
              <i class="bi bi-circle"></i><span>Form Editors</span>
            </a>
          </li>
          <li>
            <a href="forms-validation.html">
              <i class="bi bi-circle"></i><span>Form Validation</span>
            </a>
          </li>
        </ul>
      </li><!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Tables</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tables-general.php">
              <i class="bi bi-circle"></i><span>gerer les documents</span>
            </a>
          </li>
          <li>
            <a href="tables-data.html">
              <i class="bi bi-circle"></i><span>Data Tables</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Charts</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="charts-chartjs.php">
              <i class="bi bi-circle"></i><span>Chart.js</span>
            </a>
          </li>
          <li>
            <a href="charts-apexcharts.html">
              <i class="bi bi-circle"></i><span>ApexCharts</span>
            </a>
          </li>
          <li>
            <a href="charts-echarts.html">
              <i class="bi bi-circle"></i><span>ECharts</span>
            </a>
          </li>
        </ul>
      </li><!-- End Charts Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gem"></i><span>Icons</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="icons-bootstrap.html">
              <i class="bi bi-circle"></i><span>Bootstrap Icons</span>
            </a>
          </li>
          <li>
            <a href="icons-remix.html">
              <i class="bi bi-circle"></i><span>Remix Icons</span>
            </a>
          </li>
          <li>
            <a href="icons-boxicons.html">
              <i class="bi bi-circle"></i><span>Boxicons</span>
            </a>
          </li>
        </ul>
      </li><!-- End Icons Nav -->

      <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.html">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-faq.html">
          <i class="bi bi-question-circle"></i>
          <span>F.A.Q</span>
        </a>
      </li><!-- End F.A.Q Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-contact.html">
          <i class="bi bi-envelope"></i>
          <span>Contact</span>
        </a>
      </li><!-- End Contact Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-register.html">
          <i class="bi bi-card-list"></i>
          <span>Register</span>
        </a>
      </li><!-- End Register Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-login.html">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Login</span>
        </a>
      </li><!-- End Login Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-error-404.html">
          <i class="bi bi-dash-circle"></i>
          <span>Error 404</span>
        </a>
      </li><!-- End Error 404 Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="../index.php">
          <i class="bi bi-file-earmark"></i>
          <span>home</span>
        </a>
      </li><!-- End Blank Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->
