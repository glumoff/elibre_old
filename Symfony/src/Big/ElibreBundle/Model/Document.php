<?php

namespace Big\ElibreBundle\Model;

/**
 * Description of Document
 *
 * @author Alexander Glumoff <glumoff at gmail.com>
 */
class Document {

    private $id;
    private $path;
    private $title;
    private $create_dt;
    private $edit_dt;
    private $annotation;
    private $tags;
    private $fileType;
    private $mimeType;

    /**
     *
     * @var FileIcon
     */
    private $icon;
    private $theme_id;

    /**
     *
     * @param array $arr
     */
    public function __construct($arr = NULL) {
        if (isset($arr))
            $this->fillFromArray($arr);
    }

    protected function fillFromArray($arr) {
        if (is_array($arr)) {
            if (isset($arr['id']))
                $this->id = $arr['id'];
            if (isset($arr['path']))
                $this->path = $arr['path'];
            if (isset($arr['title']))
                $this->title = $arr['title'];
            if (isset($arr['create_dt']))
                $this->create_dt = $arr['create_dt'];
            if (isset($arr['edit_dt']))
                $this->edit_dt = $arr['edit_dt'];
            if (isset($arr['annotation']))
                $this->annotation = $arr['annotation'];
            if (isset($arr['tags']))
                $this->tags = $arr['tags'];
            if (isset($arr['theme_id']))
                $this->theme_id = $arr['theme_id'];
        }
    }

    public function getID() {
        return $this->id;
    }

    public function getPath() {
        return $this->path;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getAnnotation() {
        return $this->annotation;
    }

    public function getThemeID() {
        return $this->theme_id;
    }

    public function getMimeType() {
        if (!isset($this->mimeType)) {

        }
        return $this->mimeType;
    }

    public function getFileType() {
        if (!isset($this->fileType)) {
            $this->fileType = pathinfo($this->path, PATHINFO_EXTENSION);
        }
        return $this->fileType;
    }

    public function getIcon() {
        if (!isset($this->icon)) {
            $this->icon = new FileIcon($this->getFileType());
        }
        return $this->icon->getIcon();
    }

    public function setID($id) {
        $this->id = $id;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setThemeID($id) {
        $this->theme_id = $id;
    }

}
