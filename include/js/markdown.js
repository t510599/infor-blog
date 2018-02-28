var converter = new showdown.Converter();
var element = $("div#markdown");

element.each(function(i,e) {
    var txt = e.textContent;
    var md = ""
    txt = txt.split("\n");
    txt.forEach(str => {
        md += str.trim() + "\n";
    });
    e.innerHTML = converter.makeHtml(md);
});

function txtTrim() {
    e = $("textarea#markdown")[0];
    var txt = e.value;
    var md = ""
    txt = txt.split("\n");
    txt.forEach(str => {
        md += str.trim() + "\n";
    });
    e.value = md;
}