<?php

class NestedSet {

    /**
     * get node by given id
     * @param integer $id
     * @return PDO object | false if nothing found
     */
    protected function getNodeById($id) {
        return Db::test('SELECT * FROM categories WHERE id=?', [$id])->fetchObject();
    }

    /**
     * get max right key in db
     * @return int|bool
     */
    protected function getMaxRight() {
        $maxRight = Db::test('SELECT MAX(rgt) max_right FROM categories')->fetchObject();
        return $maxRight->max_right ?: false;
    }

    /**
     * get parent node
     * @param PDO object $node
     * @return PDO object|boolean return node object or false, if parent not exist
     */
    protected function getParentNode($node) {
        return Db::test('SELECT * FROM categories WHERE lft<=? AND rgt>=? AND level=?-1', [$node->lft, $node->rgt, $node->level])->fetchObject();
    }

    /**
     * get $node & all it child nodes
     * @param PDO object $node
     * @return PDO object
     */
    protected function getNodeWithChilds($node) {
        return Db::test('SELECT * FROM categories WHERE lft>=? AND rgt<=? ORDER BY lft', [$node->lft, $node->rgt])->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * convert $nodes to hierarchical array
     * @param type $nodes
     * @return array
     */
    protected function getTreeAsArray($nodes) {
        $stack = $nodesAsArray = [];

        foreach ($nodes as $node) {
            $stackSize = count($stack);
            while ($stackSize > 0 && $stack[$stackSize - 1]['rgt'] < $node->lft) {
                array_pop($stack);
                $stackSize--;
            }

            $link = &$nodesAsArray;
            for ($i = 0; $i < $stackSize; $i++) {
                $link = &$link[$stack[$i]['id']]['children'];
            }

            $tmp = array_push($link, ['title' => $node->title, 'children' => []]);
            $stack[] = ['id' => $tmp - 1, 'rgt' => $node->rgt];
        }

        return $nodesAsArray;
    }

}
