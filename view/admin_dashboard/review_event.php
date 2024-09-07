<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Insurance Claim</title>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
  <meta name="viewport" content="width=device-width" />

  <!-- Bootstrap core CSS     -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

  <!--  CSS     -->
  <link href="assets/css/style.css" rel="stylesheet" />

  <!--  Light Bootstrap Table core CSS    -->
  <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet" />

  <!--     Fonts and icons     -->
  <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
  <link href="http://fonts.googleapis.com/css?family=Roboto:400,700,300" rel="stylesheet" type="text/css" />
  <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
</head>

<body>
  <div class="wrapper">
    <!-- Side Bar -->
    <div class="sidebar" data-color="blue">
      <div class="sidebar-wrapper">
        <div class="logo">
          <a href="#" class="simple-text"> Event management system </a>
        </div>

        <ul class="nav">
          <li>
            <a href="dashboard.html">
              <i class="pe-7s-graph"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li>
            <a href="user.html">
              <i class="pe-7s-user"></i>
              <p>My Profile</p>
            </a>
          </li>
          <li>
            <a href="leave.html">
              <i class="pe-7s-note2"></i>
              <p>Leave Submissions</p>
            </a>
          </li>
          <li class="active">
            <a href="claim.html">
              <i class="pe-7s-news-paper"></i>
              <p>Insurance Claim</p>
            </a>
          </li>
          <li>
            <a href="notifications.html">
              <i class="pe-7s-bell"></i>
              <p>Notifications</p>
            </a>
          </li>
          <li>
            <a href="community.html">
              <i class="pe-7s-users"></i>
              <p>Community Forum</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <!-- Side Bar -->

    <div class="main-panel">
      <!-- Nab Bar -->
      <nav class="navbar navbar-default navbar-fixed">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Review Events</a>
          </div>
          <div class="collapse navbar-collapse">

            <ul class="nav navbar-nav navbar-right">
              <li>
                <a href="#" onclick="logout();">
                  <p>Log out</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- Nab Bar -->

      <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
      <?php endif; ?>

      <!-- Content -->
      <div id="item-list">
        <?php if (count($pendingEvents) > 0): ?>
          <ul>
            <?php foreach ($pendingEvents as $event): ?>
              <li>
                <h3><?php echo $event['title']; ?></h3>
                <p><?php echo $event['description']; ?></p>
                <p>Start Date: <?php echo $event['start_date']; ?></p>
                <p>End Date: <?php echo $event['end_date']; ?></p>
                <p>Location: <?php echo $event['location']; ?></p>

                <form method="post" action="/admin/review-events">
                  <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                  <button type="submit" name="approve_event">Approve</button>
                  <button type="submit" name="reject_event">Reject</button>
                </form>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>No pending events to review.</p>
        <?php endif; ?>

      </div>
      <!-- Content -->
    </div>
  </div>

  <script src="assets/js/script.js" type="text/javascript"></script>
</body>

<!--   Core JS Files   -->
<script src="assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

</html>