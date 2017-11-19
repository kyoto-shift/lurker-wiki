<?php
/**
 * <spoiler> tag for dokuwiki. Allows sections that are initially hidden, and
 * are shown when a "Show" button is clicked. The spoiler can be hidden again
 * by pressing the button.
 *
 * <spoiler>Content with default title</spoiler>
 * <spoiler |Plot details>The butler did it!</spoiler>
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Heikki Hokkanen <hoxu@users.sf.net>
 */
 
if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
 
class syntax_plugin_spoiler extends DokuWiki_Syntax_Plugin {

	function getInfo(){
		return array(
			'author' => 'Heikki Hokkanen',
			'email'  => 'hoxu@users.sf.net',
			'date'   => '2007-07-19',
			'name'   => 'spoiler plugin',
			'desc'   => 'Add <spoiler> tag, allowing sections that are initially hidden and can be made visible by clicking on a button. Another click will hide the spoiler again',
			'url'    => 'http://wiki.splitbrain.org/wiki:plugins',
		);
	}

	function getType(){
		return 'container';
	}

	function getPType() {
		return 'block';
	}

	function getAllowedTypes() {
		return array('container', 'substition', 'protected', 'disabled', 'formatting', 'paragraphs');
	}

	function getSort(){
		return 195;
	}

    // override default accepts() method to allow nesting 
    // - ie, to get the plugin accepts its own entry syntax
	function accepts($mode) {
		// ($mode == 'plugin_spoiler')
		if ($mode == substr(get_class($this), 7)) return true;

		return parent::accepts($mode);
	}

	function connectTo($mode) {
		// Lookahead assertion: check that there is an end tag.
		$this->Lexer->addEntryPattern('<spoiler[^>\r\n]*?>(?=.*?</spoiler>)', $mode, 'plugin_spoiler');
	}

	function postConnect() {
		$this->Lexer->addExitPattern('</spoiler>', 'plugin_spoiler');
	}

	function handle($match, $state, $pos, &$handler){
		switch ($state) {
			case DOKU_LEXER_ENTER : 
				// "<spoiler" options ">"
				$options = substr($match, 7, -1);

				$title = 'Spoiler';
				if (strpos($options, '|') !== False) {
					$title = substr($options, strpos($options, '|') + 1);
				}
				
				return array($state, $title);
				break;
			case DOKU_LEXER_MATCHED :
				break;
			case DOKU_LEXER_UNMATCHED :
				return array($state, $match);
				break;
			case DOKU_LEXER_EXIT :
				break;
			case DOKU_LEXER_SPECIAL :
				break;
		}
		return array($state);
	}

	function render($mode, &$renderer, $data) {
		if($mode == 'xhtml'){
			list($state, $payload) = $data;

			switch ($state) {
				case DOKU_LEXER_ENTER :
					$script = "c = this.parentNode.getElementsByTagName('div')[1]; if (this.value == 'Show') { c.style.display = ''; this.value = 'Hide' } else { c.style.display = 'none'; this.value = 'Show' }";
					$renderer->doc .= '<div class="spoiler"><div class="title">'. $payload .'</div><input type="button" class="spoilerbtn" value="Show" onClick="'. $script .'" /><div class="content" style="display: none">';
					// two open div tags
					break;
				case DOKU_LEXER_MATCHED :
					break;
				case DOKU_LEXER_UNMATCHED :
					$renderer->doc .= $renderer->_xmlEntities($payload);
					break;
				case DOKU_LEXER_EXIT :
					$renderer->doc .= '</div></div>';
					break;
			}
			
			return true;
		}
		return false;
	}
}
 
// VIM: ex: noet ts=4 sw=4 enc=utf-8 :
