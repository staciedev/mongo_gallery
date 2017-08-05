/*jshint esversion: 6 */
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

