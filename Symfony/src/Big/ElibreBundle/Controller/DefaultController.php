<?php

namespace Big\ElibreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//require_once '/mnt/hd/work/www/elibre/Symfony/src/Big/ElibreBundle/db/ElibreDBDelegate.php';
//require_once '../Model/Theme.php';
use Big\ElibreBundle\db\ElibreDBDelegate;
use Big\ElibreBundle\Model\Theme;

class DefaultController extends Controller {

  public function indexAction() {
    return $this->render('BigElibreBundle:Default:index.html.twig', array());
  }

  public function themeAction($action, $theme) {
    //var_dump($action);
    //sf();
    $db = new ElibreDBDelegate();
    $th = new Theme();
    
    $themesList = $db->getThemes();
   
//    echo '<pre>';
//    var_export($themesList->getThemesArray());
//    echo '</pre>';
    
    //return new \Symfony\Component\HttpFoundation\Response($th->getTitle());
    
    return $this->render('BigElibreBundle:Default:theme.html.twig', array('action' => $action, 'theme' => $theme, 'themes' => $themesList->getThemesArray()));
  }

}
