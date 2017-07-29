/*jshint esversion: 6 */
class DropArea {
  constructor( el ) {
    this.el = el;    
    
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
    
    this.dropEl.addEventListener( 'click', () => { this.input.click(); } );
    
    this.input.addEventListener( 'change', () => { this.addArts(); } );
  }
  
  addArts() {
    let files = this.input.files;
    let dataobj = new FormData();
    
    for ( let i = 0; i < files.length; i++ ) {
      let file = files[i];
      
      dataobj.append( 'arts[' + i + ']', file, file.name );
      this.artsEl.insertBefore( this.artHTML( file ), this.artsEl.firstChild );
      
      console.log( dataobj.getAll('arts[0]') );
    }
    
  }
  
  artHTML( file ) {
    
    let row = document.createElement('div');
    row.className = 'table-row';
    row.innerHTML =     
      '<span class="cell title">' + file.name + '</span>'
    ;
    
    return row;
    
  }
  
  uploadArt() {
    // TODO: send the art to server and save it to folder once it is uploaded    
    
    // TODO: when the first art is loaded, save gallery as draft
  }
}

var dropareas = document.getElementsByClassName( 'droparea' );
for ( let i = 0; i < dropareas.length; i++ ) {
  new DropArea( dropareas[i] );
}