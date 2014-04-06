function openFileManager(e, target) {
  if (target) {
    //alert(tree.get_selected());
    $.fn.custombox({
      effect: 'fadein',
      overlayClose: false,
      url: pageRoot + '/edit',
      complete: function() {
        $('#form_parent_id').val(tree.get_selected());
      }
    });
  }
  e.preventDefault();

//  alert(tree.get_selected());
//  


//  return;
//  $.fn.custombox(sender, {

}