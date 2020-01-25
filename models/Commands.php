<?php

class Commands {

    /**
     * delete node with all it childs
     * @param PDO object $node
     * @return boolean
     */
    protected function deleteNode($node) {
        $delete = new DeleteNode(['', '', $node->id]);
        $delete->ViewIndex(true);

        return true;
    }

    /**
     * add nodes tree to node with $parentNodeId
     * @param array $nodesArray
     * @param int $parentNodeId
     * @return PDO object new node
     */
    protected function addNodesTree($nodesArray, $parentNodeId) {
        foreach ($nodesArray as $node) {
            $add = new AddNode(['', '', $node['title'], $parentNodeId]);
            $newNode = $add->run(true);

            if (count($node['children'])) {
                $this->addNodesTree($node['children'], $newNode->id);
            }
        }

        return $newNode;
    }

}
