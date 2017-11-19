/*
 * Comment Syntax plugin for DokuWiki
 * a toolbar button action to toggle encomment/uncomment selected text
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 */
function addBtnActionToggleCommentBlock(btn, props, edid) {

    jQuery(btn).click(function(){
        var comment = '';
        var selection = DWgetSelection(document.getElementById('wiki__text'));
        if (selection.getLength()) {
            comment = selection.getText();
            prevchar = selection.obj.value.substring(selection.start-1,selection.start);
            nextchar = selection.obj.value.substring(selection.end,selection.end+1);
            lastchar = comment.substr(-1,1);
            if (!prevchar) prevchar = "\n";
            if (!nextchar) nextchar = "\n";
        }
        if (comment == '') {
            alert('no text selected');
            return false;
        }

        if (comment.match(/^\s*\/\*/) && comment.match(/\*\/\s*$/)) {
            // uncomment action
            if (prevchar == "\n") {
                comment = comment.replace(/^ *\/\*+ *\n?/,''); // uncomment left
            } else {
                comment = comment.replace(/^ *\/\*+ */,'');
            }
            comment = comment.replace(/ *\*+\/\s*$/,'');   // uncomment right
            document.getElementById('wiki__text').focus();
            pasteText(selection, comment);

        } else {
            // encomment action
            if (prevchar == "\n" && lastchar == "\n") {
                // Line selected
                comment = "/*\n" + comment + " */\n";
            } else {
                if (comment.match(/^\n/)) {
                    comment = comment.replace(/^\n?/,"/*\n");
                    comment = comment + " */";
                } else {
                    comment = "/* " + comment + " */";
                }
                if (!(prevchar.match(/\s/))) { comment = " " + comment; }
                if (!(nextchar.match(/\s/))) { comment = comment + " "; }
            }
            document.getElementById('wiki__text').focus();
            pasteText(selection, comment);
        }
        return true;
    });
    return false;
}
