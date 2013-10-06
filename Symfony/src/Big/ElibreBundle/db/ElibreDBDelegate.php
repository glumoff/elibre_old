<?php

/**
 * Description of ElibreDBDelegate
 *
 * @author glumoff
 */

namespace Big\ElibreBundle\db;

use Big\ElibreBundle\db\DBDelegateA;
use Big\ElibreBundle\Model\ThemesTree;

class ElibreDBDelegate extends DBDelegateA {

  public function getConn() {
    return new ElibreDBConnection();
  }

  public function getThemes() {
    $res = false;

    $query = 'SELECT * FROM themes ORDER BY parent_id, id';
    $dbm = $this->getDBM();
    //echo $query;
    $res = $dbm->selectQuery($query);
//    echo '<pre>';
//    var_dump($res);
//    echo '</pre>';

    $tl = new ThemesTree();
    $tl->buildFromArray($res);

    return $tl;
  }

}

class ElibreDBConnection {

  var $host = 'localhost';
  var $user = 'elibre';
  var $pwd = 'MoisBeag';
  var $dataBase = 'elibre';

}