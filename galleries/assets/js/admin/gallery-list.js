/*jshint esversion: 6 */

class AdminRow {
  constructor( el ) {
    this.el = el;
    this.timeout = 1000;
    
    this.el.addEventListener( 'click', (e) => this.clickRouter(e) );       
  }
  
  clickRouter( e ) {
    e.preventDefault();
    var className = " " + e.target.className + " ";
    
    if( className.includes( ' delete-link ' ) ) 
      this.openDeleteDialog( e.target.parentNode, e.target.getAttribute( 'href' ) ); 
    
    if( className.includes( ' btn-cancel ' ) )
      this.closeDeleteDialog();
      
    if( className.includes( ' btn-delete ' ) )
      this.send( 'DELETE', e.target.getAttribute( 'data-url' ) );        
  }
  
  
  openDeleteDialog( container, url ) { 
    
    if( !container || !url ) return;
    
    var dialog =  
      '<div class ="dialog-delete">' +       
      '<button class="btn-delete btn-std outlined" data-url="' + url + '">Удалить</button>' +
      '<button class="btn-cancel btn-std bright">Отмена</button>' +
      '</div>'
    ;     
    
    this.htmlBackup = container.innerHTML;
    
    container.innerHTML = dialog;  
  }
  
  
  closeDeleteDialog( withMessage ) {
    
    var dialog = this.el.getElementsByClassName('dialog-delete');
    if( dialog.length < 1 ) return;
    
    dialog = dialog[0];
    var parent = dialog.parentNode;       
    
    if( typeof( withMessage ) == 'string' ) { // show message first
      parent.innerHTML = '<p class ="cell-msg sm">' + withMessage + '</p>';
      setTimeout( 
        () => parent.innerHTML = this.htmlBackup,
        this.timeout
      );   
    } 
      
    else {
      parent.innerHTML = this.htmlBackup;
    }
    
  }
  
  send( method, url ) {
    
    let xhr = new XMLHttpRequest();
    xhr.open( method, url, true );    
    
    var self = this; 
    
    xhr.onreadystatechange = function() {
      
      if ( xhr.readyState == XMLHttpRequest.DONE ) {
        var response = JSON.parse( xhr.response );     
        // console.log( response );
        
        if( response.success ) {
          self.closeDeleteDialog( 'Успешно' );
          setTimeout(
            () => self.el.parentNode.removeChild( self.el ),
            self.timeout
          );          
        }
        else {
          self.closeDeleteDialog( 'Ошибка' );
        }   
           
      }
    };
    
    xhr.send();  
  }
  
}


class AdminTable {
  constructor( el ) {
    
    this.el = el;    
    
    let rows = this.el.getElementsByClassName('table-row');
    this.rows = [];
    for ( var i = 0; i < rows.length; i++ ) {
      this.rows.push( new AdminRow( rows[i] ) );
    }
    
    document.addEventListener( 'click', (e) => this.closeDialogs(e) );    
  }
  
  closeDialogs( e ) {
    
    var rowClicked = null;
    
    for ( let i = 0; i < e.path.length; i++ ) {
      var className = " " + e.path[i].className + " ";
      if( className.includes( ' table-row ' ) ) {
        rowClicked = e.path[i]; 
        break;
      }      
    }
    
    for ( let i = 0; i < this.rows.length; i++ ) {
      if( rowClicked !== this.rows[i].el )
        this.rows[i].closeDeleteDialog();
    }    
  }  
}

var adminTable = document.getElementsByClassName( 'admin-table' );
if( adminTable.length > 0 ) adminTable = new AdminTable( adminTable[0] );