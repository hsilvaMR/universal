tinymce.init({
  selector: '.editorTexto',
  menubar: false,
  relative_urls : false,
  plugins: [
    'advlist autolink lists link pagebreak',
    'searchreplace wordcount visualblocks visualchars code',
    'nonbreaking save table contextmenu directionality',
    'emoticons textcolor colorpicker textpattern toc help'
  ],
  toolbar1: 'undo redo | bold italic | bullist numlist outdent indent | table link | forecolor emoticons | code help',
  setup: function (editor) {
        editor.on('change', function () {
            tinymce.triggerSave();
        });
    }
});

/*
tinymce.init({
    selector: "textarea",
    setup: function (editor) {
        editor.on('change', function () {
            tinymce.triggerSave();
        });
    }
});
*/

/*
tinymce.init({
  selector:'.editorTexto',
    menubar: false,
    toolbar: 'undo redo | bold italic fontsizeselect forecolor | nanospell',// | alignleft aligncenter alignright
    //content_css: '{ { asset("js/tinymce/tinymce.css") }}',
    statusbar: true,
    elementpath: false,
    plugins: "paste textcolor",
    textcolor_map: [
      "000000", "Black",
      "993300", "Burnt orange",
      "333300", "Dark olive",
      "003300", "Dark green",
      "003366", "Dark azure",
      "000080", "Navy Blue",
      "333399", "Indigo",
      "333333", "Very dark gray",
      "800000", "Maroon",
      "FF6600", "Orange",
      "808000", "Olive",
      "008000", "Green",
      "008080", "Teal",
      "0000FF", "Blue",
      "666699", "Grayish blue",
      "808080", "Gray",
      "FF0000", "Red",
      "FF9900", "Amber",
      "99CC00", "Yellow green",
      "339966", "Sea green",
      "33CCCC", "Turquoise",
      "3366FF", "Royal blue",
      "800080", "Purple",
      "999999", "Medium gray"
    ],
    fontsize_formats: "10px 12px 14px 16px",
    paste_data_images: false,
    paste_as_text: true,
    theme_advanced_resizing : true,
    theme_advanced_resize_horizontal : false,
    force_br_newlines : true,
    force_p_newlines : false,
    forced_root_block : false,
    external_plugins: {"nanospell": "{ {  asset('vendor/nanospell/plugin.js') }}"},
    nanospell_server: "php",
    nanospell_dictionary: "{ { \Session::get('locale') }}",
    nanospell_autostart: true,
    nanospell_ignore_words_with_numerals: true,
    nanospell_ignore_block_caps: false,
    nanospell_compact_menu: false,
    auto_focus:true
});
*/