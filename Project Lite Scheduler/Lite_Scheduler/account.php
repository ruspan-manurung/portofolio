<!DOCTYPE html>
<html>

<head>
   <!-- Basic -->
   <meta charset="utf-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <!-- Mobile Metas -->
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
   <!-- Site Metas -->
   <meta name="keywords" content="" />
   <meta name="description" content="" />
   <meta name="author" content="" />
   <link rel="shortcut icon" href="images/favicon.png" type="">
   <title>Design - Design Interaction</title>
   <!-- bootstrap core css -->
   <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
   <!-- font awesome style -->
   <link href="css/font-awesome.min.css" rel="stylesheet" />
   <!-- Custom styles for this template -->
   <link href="css/style.css" rel="stylesheet" />
   <!-- responsive style -->
   <link href="css/responsive.css" rel="stylesheet" />
</head>

<body>
   <div class="hero_area">
      <!-- header section strats -->
      <header class="header_section">
         <div class="container">
            <nav class="navbar navbar-expand-lg custom_nav-container ">
               <a class="navbar-brand" href="index.html"><img width="105" src="images/logo.png" alt="#" /></a>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                  aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class=""> </span>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav">
                     <li class="nav-item">
                        <a class="nav-link" href="index.html">Home <span class="sr-only">(current)</span></a>
                     </li>
                     <li class="nav-item active">
                        <a class="nav-link" href="account.php">Account</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="group_member.php">Group Member</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="group.php">Group</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="task.php">Task</a>
                     </li>
                  </ul>
               </div>
            </nav>
         </div>
      </header>
      <!-- end header section -->
<!-- inner page section -->
    <section class="inner_page_head">
        <div class="container_fuild">
            <div class="row">
                <div class="col-md-12">
                    <div class="full">
                        <h3>Account Management</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end inner page section -->

    <!-- Account Management section -->
    <section class="why_section layout_padding">
        <div class="container">
            <div class="row">
              <div class="col-lg-6">

<div class="card">
  <div class="card-body">
    <h5 class="card-title">Account Management</h5>

<!-- General Form Elements -->
<form method="post" action="account_management.php">
  <div class="row mb-3">
    <label for="user_id" class="col-sm-2 col-form-label">User ID</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="user_id" name="user_id" placeholder="User ID" required>
    </div>
  </div>
  <div class="row mb-3">
    <label for="username" class="col-sm-2 col-form-label">Username</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
    </div>
  </div>
  <div class="row mb-3">
    <label for="pass_id" class="col-sm-2 col-form-label">Password ID</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="pass_id" name="pass_id" placeholder="Password ID" required>
    </div>
  </div>
  <div class="row mb-3">
    <label for="task_id" class="col-sm-2 col-form-label">Task ID</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="task_id" name="task_id" placeholder="Task ID">
    </div>
  </div>
  <div class="row mb-3">
    <label for="role_id" class="col-sm-2 col-form-label">Role ID</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="role_id" name="role_id" placeholder="Role ID">
    </div>
  </div>
  <div class="row mb-3">
    <label for="group_id" class="col-sm-2 col-form-label">Group ID</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="group_id" name="group_id" placeholder="Group ID">
    </div>
  </div>
  <div class="row mb-3">
    <div class="col-sm-10 offset-sm-2">
      <button type="submit" class="btn btn-primary" name="submit">Create Account</button>
    </div>
  </div>
</form><!-- End General Form Elements -->

  </div>
</div>


    <!-- end Account Management section -->
    
   <!-- footer start -->
   <footer style="background-color: #252525; color: #fff; padding: 40px 0; font-family: Arial, sans-serif;">
      <div class="container">
         <div class="row">
            <!-- Logo and Text -->
            <div class="col-md-4">
               <a href="index.html"><img width="120" src="images/logo.png" alt="Lite Logo" /></a>
               <p>Your scheduler and to-do-list.</p>
            </div>
            <!-- Contact Us -->
            <div class="col-md-4">
               <h3>Contact Us</h3>
               <p><strong>Address:</strong> Jl. Ki Hajar Dewantara, Kota Jababeka, Cikarang Baru, Bekasi 17550 -
                  Indonesia</p>
               <p><strong>Email:</strong> Lite@gmail.com</p>
               <p><strong>Phone:</strong> +1 00 11 01 100</p>
            </div>
            <!-- Social Media Icons -->
         </div>
      </div>
   </footer>
   <!-- footer end -->
   <div class="cpy_">
      <p class="mx-auto">© 2025 info loker

      </p>
   </div>
   <!-- jQery -->
   <script src="js/jquery-3.4.1.min.js"></script>
   <!-- popper js -->
   <script src="js/popper.min.js"></script>
   <!-- bootstrap js -->
   <script src="js/bootstrap.js"></script>
   <!-- custom js -->
   <script src="js/custom.js"></script>
</body>

</html>