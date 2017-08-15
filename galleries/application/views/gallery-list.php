<section id="123" class="galleries">
  
  <?php if( empty( $data ) ): ?>
    
    <p>Пока ни одной галереи. <a href="#">Создать новую</a></p>
    
  <?php else: ?>
    <h1>Галереи</h1>
    <a class="add-new" href="<?php echo App::$router->generate('admin_new_gallery'); ?>">+ Добавить новую</a>
    <div class="admin-table">      
    
    <?php foreach ( $data as $gallery ): ?>
      
      <div class="table-row">
        <span class="cell title">
          <?php echo (!empty($gallery['name']) ) ? $gallery['name'] : 'Без названия'; ?>
        </span>   
        <div class="cell edit">
          <a href="#">
            <span class="fa fa-pencil"></span>Редактировать
          </a>
        </div>
        <div class="cell delete">
          <a class="delete-link" href="<?php echo App::$router->generate( 'ajax_delete_gallery', [ 'id' => $gallery['_id'] ] ); ?>">
            <span class="fa fa-trash-o"></span>Удалить
          </a>
        </div>        
      </div>
      
    <?php endforeach; ?>
    
    </div><!-- .admin-table -->
  <?php endif; ?>      
    
</section>    
  