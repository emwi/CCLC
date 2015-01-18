<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentsInSession implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;



    /**
     * Add a new comment.
     *
     * @param array $comment with all details.
     * 
     * @return void
     */
    public function add($comment)
    {
        $comments = $this->session->get('comments', []);
        $comments[] = $comment;
        $this->session->set('comments', $comments);
    }



   /**
     * Save changes to a comment.
     */
    public function save($id, $comment)
    {
        $comments = $this->session->get('comments', []);
        $comments[$id] = $comment;
        $this->session->set('comments', $comments);

    }



    /**
     * Find and return all comments.
     *
     * @return array with all comments.
     */
    public function findAll()
    {
        $comments = $this->session->get('comments', []);

        if(is_null($comments)) {
            return $comments;
        }

        foreach ($comments as $id => $comment) :
            if($this->url->getPage() != $comment['page']) {
                unset($comments[$id]);
            }
        endforeach;
        
        return $comments;
    }


    /**
     * Find and return the comment.
     *
     * @return The comment. 
     */
    public function find($id)
    {
        $comments = $this->session->get('comments', []);

        $comment = $comments[$id];

        return $comment;
    }



    /**
     * Delete one comment.
     *
     * @return void
     */
    public function delete($id)
    {
        $comments = $this->session->get('comments', []);
        unset($comments[$id]);
        $this->session->set('comments', $comments);

    }



    /**
     * Delete all comments.
     *
     * @return void
     */
    public function deleteAll()
    {
        $this->session->set('comments', []);
    }
}
