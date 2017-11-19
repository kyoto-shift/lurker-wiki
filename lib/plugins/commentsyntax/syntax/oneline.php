<?php
/**
 * Comment Syntax plugin for DokuWiki; oneline syntax component
 * One-line comments syntax component
 * Supports "one-line" comment syntax in Wiki source text.
 * The comment does not appear in the page, but visible when you edit the page.
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
class syntax_plugin_commentsyntax_oneline extends DokuWiki_Syntax_Plugin {

    protected $mode;
    protected $match_pattern = '\s//(?:[^/\n]*|[^/\n]*/[^/\n]*)(?=\n)';

    public function __construct() {
        $this->mode = substr(get_class($this), 7); // drop 'syntax_'
    }

    public function getType(){ return 'substition'; }
    public function getSort(){ return 8; } // precedence of Doku_Parser_Mode_listblock priority (=10)

    public function connectTo($mode) {
        if ($this->getConf('use_oneline_style')) {
            $this->Lexer->addSpecialPattern($this->match_pattern, $mode, $this->mode);
        }
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) { return ''; }
    public function render($format, Doku_Renderer $renderer, $data) { return true; }
}
