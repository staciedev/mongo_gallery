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


;/*jshint esversion: 6 */
class DropArea {
  constructor( el ) {
    this.el = el;
    this.files = null;    
    
    // elements
    let inputs = this.el.getElementsByTagName('input');
    let artsEl = this.el.getElementsByClassName('arts');
    let dropEl = this.el.getElementsByClassName('invite');
    
    if( inputs.length < 1 || artsEl.length < 1 || dropEl.length < 1 ) 
      console.log( 'Some of the necessary elements missing' );
    else {
      this.input = inputs[0];
      this.artsEl = artsEl[0];
      this.dropEl = dropEl[0];      
    } 
    
    // custom events
    this.eventAdded = new CustomEvent( 'docsAdded', {} );
    
    // event listeners
    this.dropEl.addEventListener( 'click', () => this.input.click() );    
    this.input.addEventListener( 'change', () => this.addArts() );
  }
  
  addArts() {
    //// Note: using a helper variable because otherwise there is a bug:
    //// all tempIDs will be empty if a breakpoint is set after loop    
    var files = [];
    
    for ( let i = 0; i < this.input.files.length; i++ ) { 
      files.push( this.input.files[i] );
      // setting a temporary ID to sync the html with the response
      files[i].tempID = i;      
      // adding a new row to arts table         
      this.artsEl.insertBefore( this.artHTML( files[i] ), this.artsEl.firstChild );            
    }    
    
    this.files = files;
    // trigger a custom event to make sure we pass files AFTER all necessary changes
    this.el.dispatchEvent( this.eventAdded );    
  }
  
  artHTML( file ) {    
    let row = document.createElement('div');
    row.className = 'table-row';
    row.setAttribute( 'data-temp-id', file.tempID );
    row.innerHTML =     
      '<span class="cell title">' + file.name + '</span>'
    ;    
    return row;    
  }  
}

;/*jshint esversion: 6 */
class GllryForm {
  
  constructor( el ) {
    // el is a form
    this.el = el;
    this.droparea = null;
    this.url = this.el.getAttribute( 'action' );
    this.data = new FormData();
    this.inputs = this.el.getElementsByTagName('input');
    this.portionSize = 3; // number of arts sent per time
    
    // initialize droparea    
    let dropareas = document.getElementsByClassName( 'droparea' );
    if ( dropareas.length > 0 ) this.droparea = new DropArea( dropareas[0] );
    
    // event listeners
    this.el.addEventListener( 'submit', ( e ) => { e.preventDefault(); this.sendGallery(); } );
    this.droparea.el.addEventListener( 'docsAdded', () => this.sendArts() );
  }
  
  // helper function that returns input value by name
  inputVal( inputName, val ) {
    for (var input of this.inputs) {
      if( input.getAttribute('name') == inputName ) {
        if( val ) input.value = val;
        return input.value;
      }      
    }
    return false;
  }
  
  
  // sends this.data to server
  sendAJAX( callback ) {    
    let xhr = new XMLHttpRequest();
    xhr.open( 'POST', this.url, true );    
    
    var self = this; 
    
    xhr.onreadystatechange = function() {
      if ( xhr.readyState == XMLHttpRequest.DONE ) {
        var response = JSON.parse( xhr.response );     
        console.log( response );
        
        if( self.inputVal('gllry-id') == "" && response.data.galleryID != undefined )
          self.inputVal('gllry-id', response.data.galleryID);
        
        if( callback !== undefined ) callback();
      }
    };
    
    xhr.send( this.data );    
  }
  
  // sends all gallery data except arts
  sendGallery() {    
    this.data.set( 'gllry-title', this.inputVal( 'gllry-title' ) );
    this.data.set( 'gllry-id', this.inputVal( 'gllry-id' ) );
    
    this.sendAJAX();    
  }
  
  // only sends arts and gallery ID
  sendArts( iteration ) {        
    
    let data = new FormData();
    data.set( 'gllry-id', this.inputVal( 'gllry-id' ) ); 
    data.set( 'action', 'update_arts' );        
    
    let files = this.droparea.files;
    
    if( files.length == 0 ) return false;
    
    if( iteration === undefined ) iteration = 0;
    let startInd = iteration * this.portionSize,
        endInd = startInd + this.portionSize - 1;
    
    for ( let i = startInd; i < files.length && i <= endInd; i++ ) {        
      data.append( 'gllry-arts[' + files[i].tempID + ']', files[i] );
    } 
    
    this.data = data;           
    
    var callback = null, self = this;    
    if( endInd < files.length - 1 ) 
      callback = function() { self.sendArts( iteration + 1 ); };  
    else callback = function() {};
    
    this.sendAJAX( callback );
    
  }  
}

var gllryForm = document.getElementById( 'gllry-form' );
if( gllryForm ) new GllryForm( gllryForm );
