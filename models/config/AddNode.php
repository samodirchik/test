<?php

use NestedSet;

class AddNode extends NestedSet{

    private $parentNodeId, $nodeTitle;

    /**
     * add new node
     * @return PDO object or false if node was not added
     */
    private function addNode() {
        if (empty($this->parentNodeId)) {
            return $this->addRootNode();
        }

        if ($this->parentNodeId) {
            $parentNode = $this->getNodeById($this->parentNodeId);


            if ($parentNode === false) {
                return false;
            }
        }

        Db::test('UPDATE categories SET lft=lft+2, rgt=rgt+2 WHERE lft>?', [$parentNode->rgt]);
        Db::test('UPDATE categories SET rgt=rgt+2 WHERE rgt>=? AND lft<?', [$parentNode->rgt, $parentNode->rgt]);
        Db::test('INSERT INTO categories SET title=?, lft=?, rgt=?+1, level=?+1', [
            $this->nodeTitle,
            $parentNode->rgt,
            $parentNode->rgt,
            $parentNode->level,
        ]);
        $id = Db::$pdo->lastInsertId();

        return $this->getNodeById($id);
    }

    /**
     * add root node
     * @return PDO object
     */
    private function addRootNode() {
        $maxRight = $this->getMaxRight();

        if ($maxRight === false) {
            Db::test('INSERT INTO categories SET title=?, lft=?, rgt=?, level=?', [$this->nodeTitle, 1, 2, 1]);
        } else {
            Db::test('INSERT INTO categories SET title=?, lft=?, rgt=?, level=?', [$this->nodeTitle, $maxRight + 1, $maxRight + 2, 1]);
        }

        return $this->getNodeById(Db::$pdo->lastInsertId());
    }

}
