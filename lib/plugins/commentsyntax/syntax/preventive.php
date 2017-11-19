<?php
/**
 * Comment Syntax support for DokuWiki; preventive syntax component
 * preventive macro comment in Wiki source text.
 * 
 * Assume invalid macro directive caused by a typo error as a source comment
 * in the page, eg. "~~NO CACHE~~" (correct sytax is "~~NOCACHE~~").
 * You may disable the directive temporary by intentional typo.
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_commentsyntax_preventive extends DokuWiki_Syntax_Plugin {

    protected $mode;
    protected $match_pattern = '~~[^\r\n]+?~~';

    public function __construct() {
        $this->mode = substr(get_class($this), 7); // drop 'syntax_'
    }

    public function getType(){ return 'substition'; }
    public function getSort(){ return 9999; } // very low priority

    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern($this->match_pattern, $mode, $this->mode);
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        global $ID, $ACT;
        if ($ACT == 'preview') {
            return array($state, $match);
        } else if ($this->getConf('log_invalid_macro')) {
            error_log($this->mode.': match='.$match.' |'.$ID);
        }
        return '';
    }

    public function render($format, Doku_Renderer $renderer, $data) {
        global $ACT;
        if ($format == 'xhtml' && $ACT == 'preview') {
            list($state, $match) = $data;
            $renderer->doc .= $renderer->_xmlEntities($match);
        }
        return true;
    }
}
