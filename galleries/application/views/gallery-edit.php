<form id="gllry-form" class="edit-gallery has-droparea" action="<?php echo App::$router->generate('ajax_save_gallery'); ?>" method="post">
  <input type="hidden" name="gllry-id" id="gllry-id" value="">
  <h1>Новая галерея</h1>  
  
  <section class="gllry-title">    
    <input class="lg" type="text" id="gllry-title" name="gllry-title" value="" placeholder="Название галереи">
    <input type="submit" class="btn-std lg bright" value="Coхранить">
  </section>
  
  <section class="arts droparea">
    <div class="invite">
      <p>Нажмите или перетащите сюда файл</p>
      <input type="file" name="img" multiple> 
    </div>
    <div class="arts admin-table" data-save-art="<?php echo App::$router->generate('ajax_save_art'); ?>">      
    </div>       
  </section>    
</form>    
  