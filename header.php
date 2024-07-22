<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="header">
            
            <div class="logo">
              
                <a href='home.php'><img src="himalaya.png" alt="College Logo"></a>
       
               
            </div>
            <div class="contact-info">
                <p>Phone: 021-590471/021-590571</p>
                <p>Email: himalayadarshan5@gmail.com</p>

                <p> <?php if (!isset($_SESSION['admin'])): ?>
                    <a class="admin-login" href="admin-login.php">Admin Login</a>
                <?php else: ?> 
                    <a class="admin-login" href="admin-dashboard.php">Admin Dashboard</a>
                <?php endif; ?>
                </p>
                
            </div>
        </div>
    
        <div class="navbar">
    <a href="home.php">Home</a>
    <a href="contact-us.php">Contact Us</a>
  
    <div class="dropdown">
        <button class="dropbtn">Syallabus
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="bhm.php">BHM</a>
            <a href="bim.php">BIM</a>
            <a href="bca.php">BCA</a>
            <a href="csit.php">CSIT</a>
            <a href="bbs.php">BBS</a>
        </div>
    </div>
    <a href="news.php">News</a>
    <a href="gallery.php">Gallery</a>
</div>        
            </div>           
        </div>
    </header>
    
