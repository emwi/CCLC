<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction()
    {
        $comments = new \Phpmvc\Comment\Comment();
        $comments->setDI($this->di);

        $all = $comments->findAll();

        // Filtering out comments not belonging to this page.
        // Should probably do this in SQL and not here.
        foreach ($all as $id => $comment) :
            $comment = $comment->getProperties();
            if($this->url->getPage() != $comment['page']) {
                unset($all[$id]);
            }
        endforeach;

        $this->di->session();

        $form = $this->form->create(
            [
                'id' => 'add_comment',
                'class' => 'comment-form',
            ],
            ['content' => [
                'type'        => 'textarea',
                'label'       => 'Kommentar:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'name' => [
                'type'        => 'text',
                'label'       => 'Namn:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'web' => [
                'type'        => 'text',
                'label'       => 'Hemsida:',
            ],
            'mail' => [
                'type'        => 'text',
                'label'       => 'Email:',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
            ],
            'page' => [
                'type'        => 'hidden',
                'value'       => $this->url->getPage(),
            ],
            'redirect' => [
                'type'        => 'hidden',
                'value'       => $this->url->create(''),
            ],
            'submit' => [
                'type'      => 'submit',
                'value'     => 'Skicka',
                'callback'  => function($form) {
                    return true;
                }
            ],
            
             'reset' => [
                'type'      => 'reset',
                'value'     => 'Töm alla fält',
                'callback'  => function($form) {
                    return true;
                }
            ],
            
        ]);

        $status = $form->check();

        if ($status === true) {

            $comment = [
                'page'      => $form->Value('page'),
                'content'   => $form->Value('content'),
                'name'      => $form->Value('name'),
                'web'       => $form->Value('web'),
                'mail'      => $form->Value('mail'),
                'timestamp' => date("Y-m-d H:m:s", time()),
                'ip'        => $this->request->getServer('REMOTE_ADDR'),
            ];

            $comments = new \Phpmvc\Comment\Comment();
            $comments->setDI($this->di);

            $comments->save($comment);
            
            $this->response->redirect($form->Value('redirect') . $form->Value('page'));

        } else if ($status === false) {

            $form->AddOutput("<p><i>Status === false</i></p>");
            $this->response->redirect($this->di->request->getCurrentUrl());

        }

        $this->di->views->add('comment/comments', [
            'comments' => $all,
        ]);

        $this->di->views->add('comment/cform_form', [
        //$this->di->views->add('comment/form', [
            'title' => 'Lämna en kommentar',
            'form' => $form->getHTML(),
        ]);

    }

    public function editAction($id) 
    {
        $comments = new \Phpmvc\Comment\Comment();
        $comments->setDI($this->di);

        $comment = $comments->find($id);
        $comment = $comment->getProperties();

        $this->di->session();

        $form = $this->form->create(
            [
                'id' => 'edit_comment',
                'class' => 'comment-edit-form',
            ],
            ['content' => [
                'type'        => 'textarea',
                'label'       => 'Kommentar:',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => $comment['content'],
            ],
            'name' => [
                'type'        => 'text',
                'label'       => 'Namn:',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => $comment['name'],
            ],
            'web' => [
                'type'        => 'text',
                'label'       => 'Hemsida:',
                'value'       => $comment['web'],
            ],
            'mail' => [
                'type'        => 'text',
                'label'       => 'Email:',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'       => $comment['mail'],
            ],
            'page' => [
                'type'        => 'hidden',
                'value'       => $comment['page'],
            ],
            'id' => [
                'type'        => 'hidden',
                'value'       => $comment['id'],
            ],
            'redirect' => [
                'type'        => 'hidden',
                'value'       => $this->url->create(''),
            ],
            'submit' => [
                'type'      => 'submit',
                'value'     => 'Spara',
                'callback'  => function($form) {
                    return true;
                }
            ],
        ]);

        $status = $form->check();

        if ($status === true) {

            $comment['id']      = $form->Value('id');
            $comment['content'] = $form->Value('content');
            $comment['name']    = $form->Value('name');
            $comment['web']     = $form->Value('web');
            $comment['mail']    = $form->Value('mail');

            $comments->save($comment);
            
            $status = $this->di->StatusMessage;
        $status->addSuccessMessage("Kommentaren sparades.");

            $this->response->redirect($form->Value('redirect') . $form->Value('page'));

        } else if ($status === false) {

            $form->AddOutput("<p><i>Status === false</i></p>");
            $this->response->redirect($this->di->request->getCurrentUrl());

        }

        $this->di->views->add('comment/edit', [
            'title' => 'Ändra kommentaren',
            'form' => $form->getHTML(),
        ]);
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
            'timestamp' => date("Y-m-d H:m:s", time()),
            'ip'        => $this->request->getServer('REMOTE_ADDR'),
        ];

        $comments = new \Phpmvc\Comment\Comment();
        $comments->setDI($this->di);

        $comments->save($comment);
        
        $status = $this->di->StatusMessage;
        $status->addSuccessMessage("Kommentaren lades till.");

        $this->response->redirect($this->request->getPost('redirect') . $this->request->getPost('page'));
    }

    /**
     * Remove one comment.
     *
     * @return void
     */
    public function removeAction($id)
    {

        $comments   = new \Phpmvc\Comment\Comment();
        $comments->setDI($this->di);

        $comment    = $comments->find($id);
        $comment    = $comment->getProperties();

        $redirect   = $this->url->create('');
        $page       = $comment['page'];

        $comments->delete($id);
        
        $status = $this->di->StatusMessage;
        $status->addSuccessMessage("Kommentaren är borttagen.");
               
        $this->response->redirect($redirect . $page);
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
