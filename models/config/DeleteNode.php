<?php

use NestedSet;

class DeleteNode extends NestedSet {

    public function ViewIndex($silent = false) {
        $node = $this->deleteNode($this->getNodeById($this->nodeId));
    }

    /**
     * delete node
     * @param PDO object|bool $node
     * @return PDO object|boolean delete node or false, if node not found
     */
    private function deleteNode($node) {
        if (empty($node->id)) {
            return false;
        }

        Db::test('DELETE FROM categories WHERE lft>=? AND rgt<=?', [$node->lft, $node->rgt]);
        Db::test('UPDATE categories SET rgt=rgt-(?-?+1) WHERE rgt>? AND lft<?', [
            $node->rgt,
            $node->lft,
            $node->rgt,
            $node->lft,
        ]);
        Db::test('UPDATE categories SET lft=lft-(?-?+1), rgt=rgt-(?-?+1) WHERE lft>?', [
            $node->rgt,
            $node->lft,
            $node->rgt,
            $node->lft,
            $node->rgt,
        ]);

        return $node;
    }

}
