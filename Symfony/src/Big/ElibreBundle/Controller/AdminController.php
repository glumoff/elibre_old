<?php

namespace Big\ElibreBundle\Controller;

/**
 * Description of AdminController
 *
 * @author Alexander Glumoff <glumoff at gmail.com>
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Big\ElibreBundle\Entity\Theme;
use Big\ElibreBundle\Entity\Document;

class AdminController extends Controller {

  var $rootDir = "/mnt/hd/work/www/elibre_data";

  public function indexAction($mode, $action) {
//    $str = "<pre>";
    switch ($mode) {
      case 'themes':
        if (($action == 'edit') || ($action == 'save') || ($action == 'active') || ($action == 'delete')) {
          $request = $this->getRequest();
          $dbm = $this->getDoctrine()->getManager();
          $formArr = $request->request->get('form');
          $themeID = $formArr["id"] ? $formArr["id"] : $request->query->get('theme');
//          $str .= "themeID: " . var_export($request->request, TRUE) . "\n";
//          $str .= "themeID: " . var_export($themeID, TRUE) . "\n";
          if ($themeID) {
            $theme = $dbm->getRepository("BigElibreBundle:Theme")->find($themeID);
//            $str .= "found in DB: " . var_export($theme, TRUE) . "\n";
          } else {
            $theme = new Theme();
            $theme->setTitle('New theme');
          }
          /* @var $form \Symfony\Component\Form\Form */
          $form = $this->createFormBuilder($theme)
                  ->setAction($this->generateUrl('big_elibre_admin', array('mode' => $mode,
                              'action' => 'save')))
                  ->add('id', 'hidden')
                  ->add('parent_id', 'hidden')
                  ->add('title', 'text')
                  ->add('code', 'text')
                  ->add('descr', 'textarea', array('required' => FALSE))
//                  ->add('show_order', 'hidden')
                  ->add('save', 'submit')
                  ->getForm();

          if ($action == 'edit') {
            $response = $this->render('BigElibreBundle:admin:editTheme.html.twig', array(
                'editForm' => $form->createView()
                    )
            );
          } elseif ($action == 'save') {
//            $str = "<pre>before handleRequest: " . var_export($theme, TRUE) . "\n";
            $parentPath = $this->rootDir . $this->getThemeFullDirName($theme->getParentId());
            $oldDirName = $parentPath . '/' . $theme->getDirName();
            $form->handleRequest($request);
            $newDirName = $parentPath . '/' . $theme->getDirName();

            $this->updateThemeDir($oldDirName, $newDirName);

//            if ($form->isValid()) {
//            $str .= "after handleRequest: " . var_export($theme, TRUE) . "\n";
//            $response = new \Symfony\Component\HttpFoundation\Response($str);
            $res = $dbm->persist($theme);
            $dbm->flush();
            return $this->redirect($this->generateUrl('big_elibre_admin', array('mode' => $mode,
                                'action' => 'view')));
          } elseif ($action == 'active') {
            $theme->setIsActive(!$theme->isActive());
            $res = $dbm->persist($theme);
            $dbm->flush();
            return $this->redirect($this->generateUrl('big_elibre_admin', array('mode' => $mode,
                                'action' => 'view')));
          } elseif ($action == 'delete') {
            $dbm->remove($theme);
            $dbm->flush();
            return $this->redirect($this->generateUrl('big_elibre_admin', array('mode' => $mode,
                                'action' => 'view')));
          }
        } elseif ($action == 'savebatch') {
          //$str = "_POST = " . var_export($_POST, true);
          $request = $this->getRequest();
          $actions = $request->request->get('actions');
          $str = $this->saveBatch($actions);
          $response = new \Symfony\Component\HttpFoundation\Response($mode . '/' . $action . '\n' . $str);
        } else {
          $request = $this->getRequest();
          $response = $this->render('BigElibreBundle:admin:themes.html.twig', array(
              'themesJSON' => $this->getThemesJSON(),
              'isAjax' => $request->isXmlHttpRequest()
//              'isAjax' => FALSE
                  )
          );
        }
        break;

      case 'docs':
        $request = $this->getRequest();
        $dbm = $this->getDoctrine()->getManager();
        $formArr = $request->request->get('form');
        $docID = $formArr["id"] ? $formArr["id"] : $request->query->get('doc');
//          $str .= "themeID: " . var_export($request->request, TRUE) . "\n";
//          $str .= "themeID: " . var_export($themeID, TRUE) . "\n";
        if ($docID) {
          $doc = $dbm->getRepository("BigElibreBundle:Theme")->find($docID);
//            $str .= "found in DB: " . var_export($theme, TRUE) . "\n";
        } else {
          $doc = new Document();
          $doc->setTitle('New document');
        }

        /* @var $form \Symfony\Component\Form\Form */
        $form = $this->createFormBuilder($doc)
                ->setAction($this->generateUrl('big_elibre_admin', array('mode' => $mode,
                            'action' => 'add')))
                ->add('id', 'text')
                ->add('title', 'text')
                ->add('path', 'text')
                ->add('annotation', 'textarea', array('required' => FALSE))
                ->add('tags', 'text')
//                  ->add('show_order', 'hidden')
                ->add('save', 'submit')
                ->getForm();

        if ($action == 'add') {
          $response = $this->render('BigElibreBundle:admin:addDoc.html.twig', array(
              'themesJSON' => $this->getThemesJSON(),
              'isAjax' => $request->isXmlHttpRequest(),
              'addDocForm' => $form->createView()
//            'isAjax' => FALSE
                  )
          );
        } else {
          $response = $this->render('BigElibreBundle:admin:index.html.twig', array('content' => $mode));
        }
        break;

      default :
        $response = $this->render('BigElibreBundle:admin:index.html.twig', array('content' => '<-- choose menu item'));
    }
    //$response = new \Symfony\Component\HttpFoundation\Response("Mode: " . $mode);
    return $response;
  }

  private function getThemesJSON() {
    $dbm = $this->getDoctrine()->getManager();

//    $themes = $dbm->getRepository("BigElibreBundle:Theme")->findAll();
    $themes = $dbm->getRepository("BigElibreBundle:Theme")->findBy(array(), array('parentId' => 'ASC',
        'showOrder' => 'ASC',
        'id' => 'ASC'));

    $themes_arr = array();
    if (is_array($themes)) {
      /* @var $theme Theme */
      foreach ($themes as $theme) {
        $themes_arr[] = array('id' => $theme->getId(),
            'parent' => ($theme->getParentId()) ? $theme->getParentId() : '#',
            'text' => $theme->getTitle(),
            'code' => $theme->getCode(),
            'active' => $theme->isActive(),
        );
      }
    }

    return json_encode($themes_arr);
  }

  private function saveBatch($actions) {
    $res = "";
    if (is_array($actions)) {
      $dbm = $this->getDoctrine()->getManager();
      foreach ($actions as $act) {
        $res .= var_export($act, true);
        $themeID = $act['modifiedThemeID'];
        $newParentID = ($act['moveNewParentID'] != '#') ? $act['moveNewParentID'] : 0;
        $oldParentID = ($act['moveOldParentID'] != '#') ? $act['moveOldParentID'] : 0;
        $newOrder = $act['moveNewOrder'];
        $res .= "---newParentID=" . $newParentID;
        $res .= "---themeID=" . $themeID;
//        echo "---newParentID=".$newParentID;
        if ($themeID) {
//          $dbm->flush();

          if ($newParentID != $oldParentID) {
            /* @var $theme Theme */
            $theme = $dbm->getRepository("BigElibreBundle:Theme")->find($themeID);
            $theme->setParentId($newParentID);
            $theme->setShowOrder($newOrder);
          }

          $themes = $dbm->getRepository("BigElibreBundle:Theme")->findBy(
                  array('parentId' => $newParentID), array('showOrder' => 'ASC', 'id' => 'ASC'));

          if (is_array($themes)) {
            $i = 0;
            foreach ($themes as $cur_theme) {
              $dbm->persist($cur_theme);
//              echo "---".$cur_theme->getTitle()." i=" . $i;
              /* @var $cur_theme Theme */
              if ($cur_theme->getId() == $themeID) {
                $cur_theme->setParentId($newParentID);
                $cur_theme->setShowOrder($newOrder);
//              $dbm->persist($cur_theme);
//                echo "---".$cur_theme->getTitle()." newOrder=" . $newOrder;
//                $dbm->persist($cur_theme);
                continue;
              }
              if ($i == $newOrder) {
                $i++;
              }
//              echo "---".$cur_theme->getTitle()." i=" . $i;
              $cur_theme->setShowOrder($i);
              $i++;
            }
          }
        }
        // apply changes to filesystem
        /* @var $theme Theme */
        $theme = $dbm->getRepository("BigElibreBundle:Theme")->find($themeID);
        if ($theme) {
          $oldParentPath = $this->rootDir . $this->getThemeFullDirName($oldParentID);
          $newParentPath = $this->rootDir . $this->getThemeFullDirName($newParentID);
          $this->updateThemeDir($oldParentPath . '/' . $theme->getDirName(), $newParentPath . '/' . $theme->getDirName());
        }
      }
      $dbm->flush();
    }
    return $res;
  }

  /**
   * 
   * @param string $oldDirName
   * @param string $newDirName
   * @param Theme $theme
   */
  private function updateThemeDir($oldDirName, $newDirName) {
//    echo __FUNCTION__ . ', line ' . __LINE__ . "<br>";
//    var_dump($this->getThemeFullDirName($theme->getId()));
//    $newFullPath = $this->rootDir . $this->getThemeFullDirName($theme->getId());
//    $parentPath = dirname($newFullPath);
//    $oldFullPath = $parentPath . $oldDirName;
//    $newFullPath = $parentPath . $newDirName;
    if (!is_dir($oldDirName)) {
      if (!is_dir($newDirName)) {
        mkdir($newDirName, 0775, TRUE);
      }
    } else {
      if (!is_dir($newDirName)) {
        $parentDir = dirname($newDirName);
        if (!is_dir($parentDir)) {
          mkdir($parentDir, 0775, TRUE);
        }
        rename($oldDirName, $newDirName);
      } else {
        // todo: exeption here 
      }
    }

//    exit;
    //if (file)
  }

  /**
   * 
   * @param integer $theme_id
   */
  private function getThemeFullDirName($theme_id) {
    $dbm = $this->getDoctrine()->getManager();
    $path = '';

    do {
      /* @var $theme Theme */
      $theme = $dbm->getRepository("BigElibreBundle:Theme")->find($theme_id);
      if (!$theme) {
        break;
      }
      $path = '/' . $theme->getDirName() . $path;
      $theme_id = $theme->getParentId();
    } while ($theme_id > 0);

    return $path;
  }

}
