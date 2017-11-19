<?php
/**
 * Comment Syntax plugin for DokuWiki; action component
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class action_plugin_commentsyntax extends DokuWiki_Action_Plugin {

    /**
     * register the eventhandlers
     */
    public function register(Doku_Event_Handler $controller){
        if ($this->getConf('toolbar_button')) {
            $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'handleToolbar');
        }
    }

    public function handleToolbar(Doku_Event $event, $param) {
        $event->data[] = array (
            'type' => 'toggleCommentBlock',
            'title' => $this->getLang('toolbar_title'),
            'icon' => DOKU_REL.'lib/plugins/commentsyntax/images/comment.png',
        );
    }
}
