//    $('#openFMBtn').on('click', function(e) {
//      openFileManager(e, $('#form_path'));
//      e.preventDefault();
//    });

//alert('included');

function openFileManager(e, target, fmURL) {
//  alert('openFileManager');
//  alert(Routing.generate('big_elibre_fm_actions'));

//  
//  alert(target);
  if (target) {
    $.fn.custombox({
      effect: 'fadein',
      zIndex: 20000,
//      overlayClose: false,
      url: fmURL,
//      overlay: false,
//      eClose: 'closeBtn',
      complete: function() {
        new FileManager().setTargetControl(target);
        new FileManager().parentBox = this;
      }
    });
  }
  e.preventDefault();

//  alert(tree.get_selected());
//  


//  return;
//  $.fn.custombox(sender, {

}