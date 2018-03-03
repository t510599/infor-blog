$(document.head).append('<style>.editor-preview { background-color: #fcfcfc !important;}</style>')
var textarea = $("textarea#markdown")[0];

var editor = new Editor();
editor.render();

$('.icon-preview').click((i,e) => {
    setTimeout("markdownPreview();",5);
})

function markdownPreview() {
    var previewer = $(".editor-preview")[0];
    var md = editor.codemirror.getValue();
    previewer.innerHTML = converter.makeHtml(txtTrim(md));
    highlight();
}

// set value for form posting
function post() {
    textarea.value = editor.codemirror.getValue();
    document.edit.submit();
}