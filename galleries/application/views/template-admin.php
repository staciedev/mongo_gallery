<!DOCTYPE html>
<html>
  <head>
    <title>Galleries</title>
    
    <link href="https://fonts.googleapis.com/css?family=Comfortaa|Kurale|Poiret+One" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="<?php echo App::$complete_url; ?>/assets/fonts/font-awesome/css/font-awesome.min.css" />   
    <link rel="stylesheet" type="text/css" href="<?php echo App::$complete_url; ?>/assets/css/admin.css" />
    
  </head>
  <body>
    <header id="header">
      <p class="site-logo">Galleries</p> 
      <p class="subtitle">панель управления</p>     
    </header>
    <main>
      <div class="container">
        <?php include $content_view; ?>
      </div>      
    </main>
    <footer>
    
    </footer>
    
    <script type="text/javascript" src="<?php echo App::$complete_url; ?>/assets/js/admin.js"></script>
  </body>
</html>