<?php

/**
 * Description of Theme
 *
 * @author glumoff
 */

namespace Big\ElibreBundle\Model;

class Theme {

  private $code;
  private $title;
  private $description;
  private $parentCode;
  private $children;

  public function getCode() {
    return $this->code;
  }

  public function getTitle() {
    return $this->title;
  }

  public function getDescription() {
    return $this->description;
  }

  public function getParentCode() {
    return $this->parentCode;
  }

  public function getChildren() {
    return $this->children;
  }

  public function setCode($code) {
    $this->code = $code;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function setDescription($descr) {
    $this->description = $descr;
  }

  public function setChildren($children) {
    $this->children = $children;
  }

}
