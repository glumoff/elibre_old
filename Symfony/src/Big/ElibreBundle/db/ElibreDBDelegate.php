<?php

/**
 * Description of ElibreDBDelegate
 *
 * @author glumoff
 */

namespace Big\ElibreBundle\db;

use Big\ElibreBundle\db\DBDelegateA;
use Big\ElibreBundle\Model\ThemesTree;
use Big\ElibreBundle\Model\Theme;
use Big\ElibreBundle\Model\Document;
use Big\ElibreBundle\Model\DocumentsList;

class ElibreDBDelegate extends DBDelegateA {

  public function getConn() {
    return new ElibreDBConnection();
  }

  public function getThemes($level = NULL) {
    $res = false;
    $query = 'SELECT  *,
                      (SELECT COUNT(*) 
                       FROM documents
                       WHERE documents.theme_id = themes.id) AS docsCount
              FROM themes
              ORDER BY parent_id, id';
    $dbm = $this->getDBM();
    $res = $dbm->selectQuery($query);
    $tl = new ThemesTree();
    $tl->setMaxLevel($level);
    $tl->buildFromArray($res);

    return $tl;
  }

  public function getSubThemes($themeCode) {
    //$res = false;
    $query = "SELECT t2.*
              FROM themes t1
              INNER JOIN themes t2
                      ON t2.parent_id = t1.id
              WHERE t1.code = '" . $themeCode . "'
              ORDER BY title, code";
    $dbm = $this->getDBM();
    $res = $dbm->selectQuery($query, FALSE);
    $tl = new ThemesTree();
    if ($res && (is_array($res))) {
      foreach ($res as $v) {
        $t = new Theme();
        $t->setID($v['id']);
        $t->setCode($v['code']);
        $t->setTitle($v['title']);
        $t->setDescription($v['descr']);
//        $t->setParent($v['parent_id']);
        $tl->addTheme($t);
      }
    }
    return $tl;
  }

  public function getTheme($themeID) {
    $t = NULL;
    $query = "SELECT *
              FROM themes
              WHERE id = '" . $themeID. "'
              ORDER BY title, code";
    $dbm = $this->getDBM();
    $res = $dbm->selectQuery($query);
    if ($res) {
      $t = new Theme();
      $t->setID($res['id']);
      $t->setCode($res['code']);
      $t->setTitle($res['title']);
      $t->setDescription($res['descr']);
    }
    return $t;
  }
  
  public function getThemeByCode($themeCode) {
    $t = NULL;
    $query = "SELECT *
              FROM themes
              WHERE code = '" . $themeCode . "'
              ORDER BY title, code";
//    echo $query;
//    exit;
    $dbm = $this->getDBM();
    $res = $dbm->selectQuery($query);
    if ($res) {
      $t = new Theme();
      $t->setID($res['id']);
      $t->setCode($res['code']);
      $t->setTitle($res['title']);
      $t->setDescription($res['descr']);
//        $t->setParent($v['parent_id']);
    }
    return $t;
  }

  public function getDocuments($themeID) {
    $res = false;
    $query = 'SELECT * 
              FROM documents
              WHERE theme_id=' . $themeID . '
              ORDER BY title, id';
    $dbm = $this->getDBM();
    $res = $dbm->selectQuery($query, FALSE);

    $dl = new DocumentsList();
    if ($res && (is_array($res))) {
      foreach ($res as $v) {
        $d = new Document($v);
        $dl->addDoc($d);
      }
    }
    return $dl;
  }

  /**
   * 
   * @param int $docID
   * @return \Big\ElibreBundle\Model\Document
   */
  public function getDocument($docID) {
    $d = NULL;
    $query = 'SELECT * 
              FROM documents
              WHERE id=' . $docID . '
              ORDER BY title, id';
    $dbm = $this->getDBM();
    $res = $dbm->selectQuery($query, FALSE);

    if ($res && (is_array($res))) {
      $d = new Document($res[0]);
    }
    return $d;
  }

}

class ElibreDBConnection {

  var $host = 'localhost';
  var $user = 'elibre';
  var $pwd = 'MoisBeag';
  var $dataBase = 'elibre';

}