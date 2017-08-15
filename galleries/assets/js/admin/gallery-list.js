/*jshint esversion: 6 */
class AdminTable {
  constructor( el ) {
    this.el = el;
    this.rows = this.el.getElementsByClassName('table-row');
    this.dialogs = [];
    
    for (var i = 0; i < this.rows.length; i++) {
      let delLink = this.rows[i].getElementsByClassName('delete-link');
      if( delLink.length > 0 ) delLink = delLink[0];
      
      this.dialogs.push( new DialogDelete( delLink ) );
    }
    
    document.addEventListener( 'click', (e) => this.closeDialogs(e) );
    
  }
  
  showDeleteDialog( e ) {
    
         // TODO
    
  }
  
  
  
  delete() {
     // TODO
  }
  
  
}

var adminTable = document.getElementsByClassName( 'admin-table' );
for (var i = 0; i < adminTable.length; i++) {
  new AdminTable( adminTable[i] );
}

// TODO
class DialogDelete {
  
  constructor( el ) {
    this.switch = el;
    this.container = el.parentNode;
    this.class = 'dialog-delete';    
    
    this.switch.addEventListener( 'click', (e) => this.open(e) );
    
    document.addEventListener( 'click', (e) => this.close(e) );
  }
  
  open() {
    e.preventDefault();
    console.log( e.target ); 
     
    
    var dialog =  
      '<div class ="dialog-delete">' +   
      '<p class="">Удалить?</p>' +
      '<button class="btn-delete">Удалить</button>' +
      '<button class="btn-cancel">Отмена</button>' +
      '</div>'
    ;    
    this.htmlBackup = parent.innerHTML;
    
    this.container.innerHTML = dialog;  
    
  }
  
  close(e) {
    this.container.innerHTML = this.htmlBackup;
  }
  
}


