// code highlight font
$(document.head).append('<style>code {font-family: Menlo, Monaco, Consolas, monospace !important;}</style>');

var converter = new showdown.Converter();
converter.setOption('simpleLineBreaks', true);
converter.setOption('strikethrough', true);
converter.setOption('tables', true);
converter.setOption('tasklists', true);

markdownParse();
tablePrase();
highlight();

function markdownParse() {
    var targets = $("div#markdown");
    targets.each((i,e) => {
        var content = e.textContent;
        e.innerHTML = converter.makeHtml(txtTrim(content));
    });
}

function tablePrase() { // parse all tables into tocas-ui style
    var tables = $("table");
    tables.each((i,e) => {
        $(e).addClass("ts celled collapsing table");
    });
}

function highlight() {
    // code highlight
    var codes = $('pre code');
    codes.each((i,e) => {
        $(e).addClass("unstyled"); // use highlightjs style instead of tocas-ui style
        hljs.highlightBlock(e);
    });
}

function txtTrim(txt) {
    var md = "";
    txt = txt.split("\n");
    txt.forEach((str) => {
        md += str.trim() + "\n";
    });
    return md;
}