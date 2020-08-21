<script src="/assets/js/tinymce/js/tinymce/tinymce.min.js"></script>
<script src="/assets/js/tinymce/js/tinymce/plugins/image/plugin.min.js"></script>
<script src="/assets/js/tinymce/js/tinymce/plugins/table/plugin.min.js"></script>
<script src="/assets/js/tinymce/js/tinymce/plugins/anchor/plugin.min.js"></script>
<script src="/assets/js/tinymce/js/tinymce/plugins/wordcount/plugin.min.js"></script>
<script src="/assets/js/tinymce/js/tinymce/plugins/code/plugin.min.js"></script>
<script src="/assets/js/tinymce/js/tinymce/plugins/preview/plugin.min.js"></script>
<script src="/assets/js/tinymce/js/tinymce/plugins/link/plugin.min.js"></script>

<script>
	$(document).ready(function () {
tinymce.init({
selector: '.rich-text', // change this value according to your HTML
plugins: 'code image preview wordcount anchor table link',
toolbar: "styleselect | bold italic | table | image | code | preview | anchor | link",
style_formats: [
{
title: 'Heading 4',
format: 'h4'
}, {
title: 'Heading 5',
format: 'h5'
}, {
title: 'Heading 6',
format: 'h6'
}, {
title: 'Normal',
block: 'p'
}
]
});
});
</script>
