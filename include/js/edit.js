var textarea = $("textarea#markdown")[0];

function post() {
    textarea.value = editor.codemirror.getValue();
    document.edit.submit();
}