<?php

use NestedSet;

class UpdateNode extends NestedSet {

    private $nodeNewTitle;

    private function updateNode($node) {
        if (empty($node->id)) {
            return false;
        }

        Db::q('UPDATE category SET title=? WHERE id=?', [$this->nodeNewTitle, $node->id]);
        return Db::q('SELECT * FROM category WHERE id=?', [$node->id])->fetchObject();
    }

}
