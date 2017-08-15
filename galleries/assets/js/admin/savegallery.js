/*jshint esversion: 6 */
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
