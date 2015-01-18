<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;



    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction()
    {
        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $all = $comments->findAll();

        $this->views->add('comment/comments', [
            'comments' => $all,
        ]);
    }



    public function editAction() 
    {
        $id = $this->request->getPost('id');
        
        if (!isset($id)) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $comment = $comments->find($id);

        // Add the ID to the comment array
        $comment['id'] = $id;
        
                  $status = $this->di->StatusMessage;
        $status->addSuccessMessage("Kommentaren ändrades.");

        // redirects to the edit from with the $comment as param
        $this->views->add('comment/edit', $comment);
    }



    public function saveAction()
    {
        $isPost = $this->request->getPost('doSave');
        if(!$isPost) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $id = $this->request->getPost('id');
        $comment = $comments->find($id);

        $comment['content'] = $this->request->getPost('content');
        $comment['name'] = $this->request->getPost('name');
        $comment['web'] = $this->request->getPost('web');
        $comment['mail'] = $this->request->getPost('mail');

        $comments->save($id, $comment);
        
                  $status = $this->di->StatusMessage;
        $status->addSuccessMessage("Kommentaren sparades.");

        $this->response->redirect($this->request->getPost('redirect') . $this->request->getPost('page'));

    }



    /**
     * Add a comment.
     *
     * @return void
     */
    public function addAction()
    {
        $isPosted = $this->request->getPost('doCreate');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comment = [
            'page'      => $this->request->getPost('page'),
            'content'   => $this->request->getPost('content'),
            'name'      => $this->request->getPost('name'),
            'web'       => $this->request->getPost('web'),
            'mail'      => $this->request->getPost('mail'),
            'timestamp' => time(),
            'ip'        => $this->request->getServer('REMOTE_ADDR'),
        ];

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $comments->add($comment);
        
                $status = $this->di->StatusMessage;
        $status->addSuccessMessage("Kommentaren lades till.");

        $this->response->redirect($this->request->getPost('redirect') . $this->request->getPost('page'));
    }



    /**
     * Remove one comment.
     *
     * @return void
     */
    public function removeAction()
    {

        $id = $this->request->getPost('id');
        
        if (!isset($id)) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $comments->delete($id);
        
                $status = $this->di->StatusMessage;
        $status->addSuccessMessage("Kommentaren är borttagen.");

        $this->response->redirect($this->request->getPost('redirect') . $this->request->getPost('page'));
    }



    /**
     * Remove all comments.
     *
     * @return void
     */
    public function removeAllAction()
    {
        $isPosted = $this->request->getPost('doRemoveAll');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $comments->deleteAll();

        $this->response->redirect($this->request->getPost('redirect') . $this->request->getPost('page'));
    }
}
