<?php

namespace Big\ElibreBundle\Entity;

use Big\ElibreBundle\Model\FileIcon;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Alexander Glumoff <glumoff at gmail.com>
 */

/**
 * Document
 *
 * @ORM\Table(name="documents")
 * @ORM\Entity
 */
class Document {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="path", type="text")
     */
    private $path;
    
    /**
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;
    
//    create_dt	datetime
//    edit_dt		datetime
//    annotation	text
//    tags		varchar(200)
//    theme_id	int(11)
   
    /**
     * @ORM\Column(name="create_dt", type="datetime")
     */
    private $create_dt;

    /**
     * @ORM\Column(name="edit_dt", type="datetime")
     */
    private $edit_dt;

    /**
     * @ORM\Column(name="annotation", type="text")
     */
    private $annotation;

    /**
     * @ORM\Column(name="tags", type="string", length=200)
     */
    private $tags;
    
    private $fileType;
    private $mimeType;

    /**
     *
     * @var FileIcon
     */
    private $icon;
    
    /**
     * @ORM\Column(name="theme_id", type="integer")
     */
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

    public function getPath() {
        return $this->path;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getAnnotation() {
        return $this->annotation;
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

//    public function setID($id) {
//        $this->id = $id;
//    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function setTitle($title) {
        $this->title = $title;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set create_dt
     *
     * @param \DateTime $createDt
     * @return Document
     */
    public function setCreateDt($createDt)
    {
        $this->create_dt = $createDt;
    
        return $this;
    }

    /**
     * Get create_dt
     *
     * @return \DateTime 
     */
    public function getCreateDt()
    {
        return $this->create_dt;
    }

    /**
     * Set edit_dt
     *
     * @param \DateTime $editDt
     * @return Document
     */
    public function setEditDt($editDt)
    {
        $this->edit_dt = $editDt;
    
        return $this;
    }

    /**
     * Get edit_dt
     *
     * @return \DateTime 
     */
    public function getEditDt()
    {
        return $this->edit_dt;
    }

    /**
     * Set annotation
     *
     * @param string $annotation
     * @return Document
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;
    
        return $this;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return Document
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    
        return $this;
    }

    /**
     * Get tags
     *
     * @return string 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set theme_id
     *
     * @param integer $themeId
     * @return Document
     */
    public function setThemeId($themeId)
    {
        $this->theme_id = $themeId;
    
        return $this;
    }

    /**
     * Get theme_id
     *
     * @return integer 
     */
    public function getThemeId()
    {
        return $this->theme_id;
    }
}