<?php

namespace Big\ElibreBundle\Model;

/**
 * Description of File
 *
 * @author Alexander Glumoff <glumoff at gmail.com>
 */
class File {

  private $fpath;

  public function __construct($fpath = NULL) {
    $this->setFilePath($fpath);
  }

  public function getFilePath() {
    return $this->fpath;
  }

  public function setFilePath($fpath) {
    $this->fpath = $fpath;
    return $this;
  }

  public function getFileName() {
    return basename($this->fpath);
  }
  
  public function getType() {
    return 'file';
  }

  public function toArray() {
    return array(
        'fpath' => $this->getFilePath(),
        'fname' => $this->getFileName(),
        'type' => $this->getType()
    );
//    return (array) $this;
  }
  
}
