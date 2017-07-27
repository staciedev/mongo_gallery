<section id="123" class="galleries">
  
  <?php if( empty( $data ) ): ?>
    
    <p>Пока ни одной галереи. <a href="#">Создать новую</a></p>
    
  <?php else: ?>
    <h1>Галереи</h1>
    <a class="add-new" href="#">+ Добавить новую</a>
    <div class="admin-table">      
    
    <?php foreach ( $data as $gallery ): ?>
      
      <div class="table-row">
        <span class="cell title"><?php echo $gallery['name']; ?></span>   
        <a class="cell edit" href="#"><span class="fa fa-pencil"></span>Редактировать</a>
        <a class="cell delete" href="#"><span class="fa fa-trash-o"></span>Удалить</a>        
      </div>
      
    <?php endforeach; ?>
    
    </div><!-- .admin-table -->
  <?php endif; ?>      
    
</section>    
  