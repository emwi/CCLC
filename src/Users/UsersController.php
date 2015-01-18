<?php

namespace Anax\Users;
 
/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
    }

    /**
     * List all users.
     *
     * @return void
     */
    public function listAction()
    {
        $this->initialize();
     
        $all = $this->users->findAll();

        $active = $this->users->query()
            ->where('active IS NOT NULL')
            ->andWhere('deleted IS NULL')
            ->execute();

        $deleted = $this->users->query()
            ->where('deleted IS NOT NULL')
            ->execute();

        $inactive = $this->users->query()
            ->where('active IS NULL')
            ->andWhere('deleted IS NULL')
            ->execute();
     
        $this->theme->setTitle("Användare");
        $this->views->add('users/list-all', [
            'users' => $all,
            'activeUsers' => $active,
            'inActiveUsers' => $inactive,
            'deletedUsers' => $deleted,
            'title' => "Användare",
        ]);

        $status = $this->di->StatusMessage;
        $this->views->addString($status->messagesHtml(), 'status');
    }

    /**
     * List user with id.
     *
     * @param int $id of user to display
     *
     * @return void
     */
    public function idAction($id = null)
    {
        $this->initialize();
     
        $user = $this->users->find($id);
     
        $this->theme->setTitle("Visa en användare");
        $this->views->add('users/view', [
            'user' => $user,
        ]);
    }

    /**
     * Add new user.
     *
     * @param string $acronym of user to add.
     *
     * @return void
     */
    public function addAction($acronym = null)
    {
        $this->theme->setTitle('Lägg till användare');

        $session = $this->di->session();
        $session->name("add_user");

        $form = $this->form->create(
            [
                'id' => 'add_user',
                'class' => 'user-add-form',
            ],
            ['username' => [
                'type'        => 'text',
                'label'       => 'Användarnamn:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'name' => [
                'type'        => 'text',
                'label'       => 'Namn:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'email' => [
                'type'        => 'text',
                'label'       => 'Email:',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
            ],
             'submit' => [
                'type'      => 'submit',
                'callback'  => function($form) {
                    $form->saveInSession = true;
                    return true;
                }
            ],
        ]);

        $status = $form->check();

        if ($status === true) {

            $now = date("Y-m-d H:i:s");
                    
            $this->users->save([
                'acronym' => $form->Value('username'),
                'email' => $form->Value('email'),
                'name' => $form->Value('name'),
                'password' => password_hash($form->Value('password'), PASSWORD_DEFAULT),
                'created' => $now,
                'active' => $now,
                           ]);

            $form->saveInSession = false;
            
                                         $status = $this->di->StatusMessage;
        $status->addSuccessMessage("Användaren skapades.");

            // Without this there is errors in CForm.
            unset($_SESSION['form-save']);

            $url = $this->url->create('users/id/' . $this->users->id);
            $this->response->redirect($url);
            
        } else if ($status === false) {
            $this->response->redirect($this->di->request->getCurrentUrl());
        }

        $this->di->views->add('me/page', [
            'title' => "Lägg till användare",
            'content' => $form->getHTML(),
            'byline' => "<a href='" . $this->url->create('users/list') . "'>Tillbaka</a>",
        ]);

    }

    /**
     * Add new user.
     *
     * @param string $acronym of user to add.
     *
     * @return void
     */
    public function editAction($id = null)
    {
        $this->theme->setTitle('Ändra en användare');

        $session = $this->di->session();
        $session->name("edit_user");

        $user = $this->users->find($id);

        $user = $user->getProperties();

        $form = $this->form->create(
            [
                'id' => 'edit_user',
                'class' => 'user-edit-form',
            ],
            ['username' => [
                'type'        => 'text',
                'label'       => 'Användarnamn:',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => $user['acronym'],
            ],
            'name' => [
                'type'        => 'text',
                'label'       => 'Namn:',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => $user['name'],
            ],
            'email' => [
                'type'        => 'text',
                'label'       => 'Email:',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'       => $user['email'],
            ],
            'id' => [
                'type'        => 'hidden',
                'value'       => $user['id'],
            ],
            'submit' => [
                'type'      => 'submit',
                'callback'  => function($form) {
                    $form->saveInSession = false;
                    return true;
                }
            ],
        ]);

        $status = $form->check();

        // Form was submitted and the callback method returned true
        if ($status === true) {

            $now = date("Y-m-d H:i:s");
         
            $this->users->save([
                'id' => $form->Value('id'),
                'acronym' => $form->Value('username'),
                'email' => $form->Value('email'),
                'name' => $form->Value('name'),
                'updated' => $now,
            ]);

            $url = $this->url->create('users/id/' . $this->users->id);
            $this->response->redirect($url);

        } else if ($status === false) {
            $this->response->redirect($this->di->request->getCurrentUrl());
        }

        $this->di->views->add('me/page', [
            'title' => "Ändra en användare",
            'content' => $form->getHTML(),
            'byline' => "<a href='" . $this->url->create('users/id') . "/" . $this->users->id . "'>Tillbaka</a>",
        ]);

    }

    /**
     * Delete user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function deleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
     
        $res = $this->users->delete($id);

        $status = $this->di->StatusMessage;
        $status->addSuccessMessage("Användaren borttagen.");
     
        $url = $this->url->create('users/list');
        $this->response->redirect($url);
    }

    /**
     * Delete (soft) user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function softDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
     
        $now = date("Y-m-d H:i:s");
     
        $user = $this->users->find($id);
     
        $user->deleted = $now;
        $user->save();

        $status = $this->di->StatusMessage;
        $status->addSuccessMessage("Användaren är flyttad till papperskorgen.");
     
        $url = $this->url->create('users/list');
        $this->response->redirect($url);
    }

    /**
     * Undo soft delete
     *
     * @param integer $id of user to undo soft remove for.
     *
     * @return void
     */
    public function undoDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
      $now = date("Y-m-d H:i:s");
     
        $user = $this->users->find($id);
     
        $user->deleted = NULL;
        $user->inactive = $now;
        $user->save();

        $status = $this->di->StatusMessage;
        $status->addSuccessMessage("Ångrade borttagning av användaren.");
     
        $url = $this->url->create('users/list');
        $this->response->redirect($url);
    }

    /**
     * Inactivate user.
     *
     * @param integer $id of user to inactivate.
     *
     * @return void
     */
    public function inactivateAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
     
        $user = $this->users->find($id);
     
        $user->active = NULL;
        $user->save();

        
        $status = $this->di->StatusMessage;
        $status->addSuccessMessage("Användaren är inaktiverad.");
     
        $url = $this->url->create('users/list');
        $this->response->redirect($url);
    }

    /**
     * Activate user.
     *
     * @param integer $id of user to activate.
     *
     * @return void
     */
    public function activateAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $now = date("Y-m-d H:i:s");
     
        $user = $this->users->find($id);
     
        $user->active = $now;
        $user->save();

        $status = $this->di->StatusMessage;
        $status->addSuccessMessage("Användaren är aktiverad.");
     
        $url = $this->url->create('users/list');
        $this->response->redirect($url);
    }

    /**
     * List all active and not deleted users.
     *
     * @return void
     */
    public function activeAction()
    {
        $all = $this->users->query()
            ->where('active IS NOT NULL')
            ->andWhere('deleted is NULL')
            ->execute();
     
        $this->theme->setTitle("Aktiva användare");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Aktiva användare",
        ]);
    }
 
} 
