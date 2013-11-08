<?php

namespace Big\ElibreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;
//require_once '/mnt/hd/work/www/elibre/Symfony/src/Big/ElibreBundle/db/ElibreDBDelegate.php';
//require_once '../Model/Theme.php';
use Big\ElibreBundle\db\ElibreDBDelegate;

//use Big\ElibreBundle\Model\Theme;

class DefaultController extends Controller {

  protected $templateParams = NULL;

  public function indexAction() {
    return $this->render('BigElibreBundle:Default:index.html.twig', $this->getTemplateParams());
  }

  protected function getTemplateParams() {
    $this->get('translator')->setLocale($this->getRequest()->getPreferredLanguage());

    if ($this->templateParams === NULL) {
      $this->templateParams = array();
      $this->templateParams['menuThemes'] = $this->getThemesMenuArray();
    }
    return $this->templateParams;
  }

  protected function getThemesMenuArray() {
    $db = new ElibreDBDelegate();
    $themesList = $db->getThemes(2);
    return $themesList->getThemesArray();
  }

}
