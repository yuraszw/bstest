<?php

namespace Convertor\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Convertor\Form\ConvertorForm;
use Convertor\Model\dataHolder;

/**
 * Convertor Controller Class, has single action.
 */
class ConvertorController extends AbstractActionController
{
    public function indexAction()
    {
        $form = new ConvertorForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $files = $request->getFiles()->toArray();
            $dh = new dataHolder();

	    // Get contents of uploaded file
            $fileContents = file_get_contents($files['input-file']['tmp_name']);
            $inputFile = $files['input-file']['name'];
            // Input filename extension
            $inputExt = end(explode('.', $inputFile));
	    // Method name that read data of given type
            $readFunc = 'read'.strtoupper($inputExt);
            try {
                $dh->{$readFunc}($fileContents);
            } catch (\Exception $e) {
                return array('form' => $form,'error' => 'Error processing input file:<br>'.$e->getMessage());
            }

            $outputExt = $post['type'];
            $outputFile = substr($inputFile, 0, strlen($inputFile)-strlen($inputExt)).strtolower($outputExt);
            $response = $this->getResponse();
            $getFunc = 'get'.strtoupper($outputExt);
	    $ctFunc = 'get'.strtoupper($outputExt).'_CT';
            try {
                $strOutput = $dh->{$getFunc}($fileContents);
		$contentType = $dh->{$ctFunc}();
            } catch (\Exception $e) {
                return array('form' => $form,'error' => 'Error writing result:<br>'.$e->getMessage());
            }
            $response->setContent($strOutput);

            $headers = $response->getHeaders();
            $headers->clearHeaders()
                 ->addHeaderLine('Content-Type', $contentType)
                 ->addHeaderLine('Content-Disposition', 'attachment; filename="'.$outputFile.'"')
                 ->addHeaderLine('Content-Length', strlen($strOutput));

            return $this->response;
        }

        return array('form' => $form);
    }
}
