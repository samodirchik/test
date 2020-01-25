<?php

class View {

    public function ViewIndex() {
        $query = Database::test('SELECT * FROM category ORDER BY lft');
        while ($row = $query->fetchObject()) {
            echo str_repeat('   ', $row->level - 1) . ' ' . $row->title . " id={$row->id}" . " ({$row->lft}, {$row->rgt})";
        }
    }

}
