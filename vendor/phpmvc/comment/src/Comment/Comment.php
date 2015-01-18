<?php

namespace Phpmvc\Comment;
 
/**
 * Model for comments.
 *
 */
class Comment extends \Anax\MVC\CDatabaseModel
{

    /**
     * Find all comments for the given page.
     *
     * @return array
     */
    public function findComments($page)
    {
        $this->db->select()
                 ->from($this->getSource())
                 ->where('page = '.$page);
     
        $this->db->execute();
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }
 
} 
