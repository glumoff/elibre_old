<?php

namespace Big\ElibreBundle\Controller;

/**
 * Description of DocumentController
 *
 * @author Alexander Glumoff <glumoff at gmail.com>
 */
use Big\ElibreBundle\db\ElibreDBDelegate;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends DefaultController {

  //TODO: move it to parameters
  private $docs_path = '/mnt/hd/work/www/elibre_data/';


  public function showDocAction($action) {
    //action: view|download|edit
    switch ($action) {
      case 'download':
        $this->doDownloadFile();
        break;

      default:
        return $this->render('BigElibreBundle:Default:doc.html.twig', $this->getTemplateParams());
//        break;
    }
  }

  protected function getTemplateParams() {
    if ($this->templateParams === NULL) {
      parent::getTemplateParams();

      $doc = $this->getSelectedDoc();
      if ($doc) {
        $db = new ElibreDBDelegate();
//        echo __CLASS__ . '.' . __FUNCTION__ . ' #' . __LINE__ . ": i was here<br>";
        $selectedTheme = $db->getTheme($doc->getThemeID());
        if ($selectedTheme) {
          $wholeThemesTree = $db->getThemes();
          $themePath = $wholeThemesTree->getThemePath($selectedTheme->getCode());
        }
      }

      $this->templateParams['doc'] = $doc;
      $this->templateParams['selectedTheme'] = $selectedTheme;
      if ($themePath) {
        $this->templateParams['activeThemeRoot'] = (count($themePath) > 0) ? $themePath[max(count($themePath) - 1, 0)]->getID() : '-1';
      }
    }
    return $this->templateParams;
  }

  protected function doDownloadFile() {
//    return 
//    return;    
    $doc = $this->getSelectedDoc();
    if ($doc) {
      $fname = $this->docs_path . $doc->getPath();
      if (file_exists($fname) && !is_dir($fname)) {
        $response = new Response();
        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($fname));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($fname) . '"');
        $response->headers->set('Content-length', filesize($fname));
        // Send headers before outputting anything
        $response->sendHeaders();
        $response->setContent(readfile($fname));
        return $response;
      }
      else {
        throw $this->createNotFoundException('The document does not exist');
      }
    }
    else {
      throw $this->createNotFoundException('The document does not selected');
    }
  }

  private function getSelectedDoc() {
    $request = $this->getRequest();
    $db = new ElibreDBDelegate();
    // TODO: make escaping of params
    $selectedDocID = $request->attributes->get('doc_id', 'not set');
    $doc = $db->getDocument($selectedDocID);
    return $doc;
  }

}
