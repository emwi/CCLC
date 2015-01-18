<?php

namespace Anax\Flashingmessage;

/**
 * Class for display different type of status updates/messages in session.
 * The messages are styled in four different styles for variuos message.
 * You can display several massages at the same time. 
 *
 */
class CStatusMessage
{
    use \Anax\DI\TInjectable;

    // Storing variables
    private $sessionVariable = 'emwi_StatusMessage';
    private $messageTypes = ['debug', 'error', 'success', 'warning'];

    // Variable for the anax session object
    private $session = null;
    
    // All messages will be stored in $allMessages when instace created
    private $allMessages = null;

    
    public function __construct($di) 
    {
        $this->di = $di;
        $this->session = $this->di->session();

        if($this->session->has($this->sessionVariable))
        {
            $this->retrieveMessages();
        }
    }

    /**
     * Adds message to session.
     * 
     */
 private function addMessage($type = 'debug', $message)
    {
        $statusMessage = ['type' => $type, 'message' => $message];

        if(is_null($this->allMessages))
        {
            $this->allMessages = array();
        }

        array_push($this->allMessages, $statusMessage);

        $this->session->set($this->sessionVariable, $this->allMessages);
    }


    /**
     * Adds debug message to session.
     * 
     */
     public function addDebugMessage($message)
    {
        $this->addMessage('debug', $message);
    }




    /**
     * Adds error message to session.
     * 
     */
    public function addErrorMessage($message)
    {
        $this->addMessage('error', $message);
    }
    
    
    /**
     * Adds success message to session.
     * 
     */
    public function addSuccessMessage($message)
    {
        $this->addMessage('success', $message);
    }
    
    /**
     * Adds warning message to session.
     * 
     */
    public function addWarningMessage($message)
    {
        $this->addMessage('warning', $message);
    }


    /**
     * Checks if $allMessages variable is empty or not.
     * 
     */
    public function isEmpty() 
    {
        if(is_null($this->allMessages))
        {
            return true;
        }
        return false;
    }
    
        /**
     * Clears all messages from session.
     * 
     */
    public function clearMessages() 
    {
        $this->session->set($this->sessionVariable, null);
    }
    
    /**
     * Retrieves array of messages from session. Stores them in the $allMessages variable.
     * 
     */
    public function retrieveMessages()
    {
        $this->allMessages = $this->session->get($this->sessionVariable);
    }


        /**
     * Returns html-div with messages.
     *
     */
 public function messagesHtml()
    {
        $msgHtml = "";

        if(is_null($this->allMessages))
            return $msgHtml;

        foreach ($this->allMessages as $message) {
            $type = $message['type'];
            $message = $message['message'];

            $msgHtml .= "<div class='message-".$type."'>".$message."</div>";
        }

        $this->clearMessages();

        return $msgHtml;
    }
    
} 
