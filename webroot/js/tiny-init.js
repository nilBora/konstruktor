$(function() {
    tinymce.init({
        selector: '.tiny-mce',
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code cloudfilemanager'
        ],
        relative_urls: false,
        image_advtab: true,
        external_filemanager_path:"/Cloud/index",
        filemanager_title: "File manager" ,
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link cloudfilemanager'
    });
});