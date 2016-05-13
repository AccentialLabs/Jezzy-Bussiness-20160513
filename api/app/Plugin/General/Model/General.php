<?php

class General extends AppModel {

    var $useTable = false;

    //General query function
    public function executQuery($query) {
        return($this->query($query));
    }

}
