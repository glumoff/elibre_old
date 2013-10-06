<?php

/**
 * Description of ThemesList
 *
 * @author glumoff
 */

namespace Big\ElibreBundle\Model;

class ThemesTree {

  private $themes_arr;

  public function __construct($arr = NULL) {
    if (isset($arr))
//      $this->buildFromArray($arr);
      $this->themes_arr = $arr;
  }

  //TODO: make navigation with iterator    
  public function getThemesArray() {
    return $this->themes_arr;
  }

  /**
   * 
   * @param Theme $theme
   */
  public function addTheme($theme) {
    $this->themes_arr[$theme->getCode()] = $theme;
  }

  public function buildFromArray($arr) {
    $this->themes_arr = $this->parseArray($arr);
  }

  private function parseArray($arr, $root = NULL) {
//    echo 'ROOT: ' . $root . '<br>';
    $res = NULL;
    if (is_array($arr)) {
      foreach ($arr as $k => $v) {
        $t = new Theme();
        $t->setCode($v['id']);
        $t->setTitle($v['title']);
        if ($v['parent_id'] == $root) {
          unset($arr[$k]);
//          echo $k . ': ' . $v['title'] . "; parent: " . var_export($v['parent_id'], TRUE) . '<br>';
          $res[] = $t;
//          $tl = new ThemesTree();
          $res[count($res) - 1]->setChildren(new ThemesTree($this->parseArray(&$arr, $v['id'])));
        }
      }
    }
    return $res;
  }

  public function getTheme($code) {
    $res = NULL;
    foreach ($this->themes_arr as $t) {
      if ($t->code == $code) {
        $res = NULL;
        break;
      }
      elseif ($t->children) {
        $res = $t->children->getTheme();
      }
    }
    return $res;
  }

}
