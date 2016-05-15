function editor(str) {
    tinymce.init({
        selector: str,
        theme: "modern",
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor importcss colorpicker textpattern"
        ],
        relative_urls: true,
        document_base_url: 'http://localhost/desktopdeli/admin',
        image_advtab: true,
        external_filemanager_path: "js/editor/tinymce/filemanager/",
        filemanager_title: "Filemanager",
        external_plugins: {
            "filemanager": "filemanager/plugin.min.js"
        },
        relative_urls: true,
                document_base_url: 'http://localhost/desktopdeli/admin',
//        content_css: "css/development.css",

                add_unload_trigger: false,
        autosave_ask_before_unload: false,
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
        style_formats: [
            {title: 'Bold text', format: 'h1'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],
        spellchecker_callback: function (method, data, success) {
            if (method == "spellcheck") {
                var words = data.match(this.getWordCharPattern());
                var suggestions = {};

                for (var i = 0; i < words.length; i++) {
                    suggestions[words[i]] = ["First", "second"];
                }

                success({words: suggestions, dictionary: true});
            }

            if (method == "addToDictionary") {
                success();
            }
        }
    });
}

function fulleditor(str) {
    tinymce.init({
        selector: str,
        theme: "modern",
        plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker"
        ],
        relative_urls: true,
        document_base_url: 'http://localhost/desktopdeli/admin',
        image_advtab: true,
        external_filemanager_path: "js/editor/tinymce/filemanager/",
        filemanager_title: "Filemanager",
        external_plugins: {
            "filemanager": "filemanager/plugin.min.js"
        },
        relative_urls: true,
                document_base_url: 'http://localhost/desktopdeli/admin',
//        content_css: "css/development.css",
                add_unload_trigger: false,
        autosave_ask_before_unload: false,
        toolbar1: "save newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
        toolbar2: "cut copy paste pastetext | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media help code | insertdatetime preview | forecolor backcolor",
        toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft | insertfile insertimage",
        menubar: false,
        toolbar_items_size: 'small',
        style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],
        templates: [
            {title: 'My template 1', description: 'Some fancy template 1', content: 'My html'},
            {title: 'My template 2', description: 'Some fancy template 2', url: 'development.html'}
        ],
        spellchecker_callback: function (method, data, success) {
            if (method == "spellcheck") {
                var words = data.match(this.getWordCharPattern());
                var suggestions = {};

                for (var i = 0; i < words.length; i++) {
                    suggestions[words[i]] = ["First", "second"];
                }

                success({words: suggestions, dictionary: true});
            }

            if (method == "addToDictionary") {
                success();
            }
        }
    });

}



function texteditor(str) {
    tinymce.init({
        selector: str,
        theme: "modern",
//        plugins: [
//            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
//            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
//            "save table contextmenu directionality emoticons template paste textcolor importcss colorpicker textpattern"
//        ],
        plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker"
        ],
        menubar: true,
        add_unload_trigger: false,
        autosave_ask_before_unload: false,
        toolbar: "insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
        verify_html: false,
        valid_elements: '*[*]',
        spellchecker_callback: function (method, data, success) {
            if (method == "spellcheck") {
                var words = data.match(this.getWordCharPattern());
                var suggestions = {};

                for (var i = 0; i < words.length; i++) {
                    suggestions[words[i]] = ["First", "second"];
                }

                success({words: suggestions, dictionary: true});
            }

            if (method == "addToDictionary") {
                success();
            }
        }
    });
}
