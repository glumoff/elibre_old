// init function
$(function() {
  //var FM = new FileManager();
  new FileManager().init();

  $("#fileuploader").uploadFile({
    url: "http://172.18.1.12:8080/app_dev.php/fm/upload",
//    fileName: "myfile",
    onSubmit: function(files) {
      this.formData.path = new FileManager().currentPath;
      this.data.path = new FileManager().currentPath;
    },
    onSuccess: function(files, data, xhr)
    {
      new FileManager().refreshFileList();
    },
    dynamicFormData: function()
    {
      var data = {path: new FileManager().currentPath};
      return data;
    },
  });
});

function FileManager() {
  // Singleton
  if (!FileManager.__instance)
    FileManager.__instance = this;
  else
    return FileManager.__instance;

  this.currentPath = '';


  this.init = function() {
    this.currentPath = $('#currentPath').text();
    this.refreshFileList();
  };

  this.refreshFileList = function() {
    var url = 'http://172.18.1.12:8080/app_dev.php/fm/getlist';
//    var thisFM = this;
    $.ajax({
      type: "GET",
      url: url,
      data: {'path': this.currentPath},
      error: function(data, textStatus, jqXHR) {
        alert('ajax data (' + textStatus + '): ' + JSON.stringify(data));
      },
      success: function(data, textStatus, jqXHR) {
        var data = eval(data);
        var curFile/*, curAction,
         ind = 1,
         curLi*/;
        $('.FMFileList ul').empty();

        // go up link
        $('.FMFileList ul').append('<li class="dirUp"><a href="#">[UP]</a>\n\
                                        <a href="#">..</a>\n\
                                      </li>\n');

        for (var i in data) {
          curFile = data[i];
          $('.FMFileList ul').append('<li class="' + curFile.type + '">\n\
                                        <input type="checkbox" value="' + curFile.fpath + '">\n\
                                        <a href="' + curFile.fpath + '">[' + curFile.type + ']</a>\n\
                                        <a href="' + curFile.fpath + '">' + curFile.fname + '</a>\n\
                                      </li>\n');
        }
        $('.FMFileList li.file a').on('click', function(e) {
          alert('select file');
          e.preventDefault();
        });
        $('.FMFileList li.dir a').on('click', function(e) {
          new FileManager().changeDir($(this).attr('href') + '/');
          e.preventDefault();
        });
        $('.FMFileList li.dirUp a').on('click', function(e) {
          new FileManager().goUp();
          e.preventDefault();
        });

      }
    });
  };
  this.changeDir = function(newPath) {
    this.currentPath = newPath;
    this.refreshFileList();
  };
  this.goUp = function() {
    var newPath;
    var p = this.currentPath.lastIndexOf('/', this.currentPath.length - 2); // -2 to cut / at the end
    if (p >= 0) {
      newPath = this.currentPath.substring(0, p) + '/';
      this.changeDir(newPath);
    }
  };
  this.uploadDialog = function() {
    alert('uploadDialog');
  };
  this.delete = function() {
    var itemsToDel = new Array(),
            itemsToDelStr = '',
            itemsToDelCount = 0;
    $('input[type=checkbox]').each(function() {
      if (this.checked) {
        itemsToDelCount++;
        itemsToDelStr += this.value + ', '
      }
    });
    if (itemsToDelCount) {
      if (confirm('You choose to delete ' + itemsToDelCount + ' item(s):\n' + itemsToDelStr + '\n\nAre you sure ?')) {
        //ajax call here
      }
    }
  };
}
