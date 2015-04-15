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
            $tarr = explode('.', $inputFile);
            if (count($tarr>1)) {
                $inputExt = $tarr[count($tarr)-1];
            } else {
                $inputExt = '';
            }
            try {
                $dh->readData($fileContents, $inputExt);
            } catch (\Exception $e) {
                return array('form' => $form,'error' => 'Error processing input file:<br>'.$e->getMessage());
            }

            $outputExt = $post['type'];
            $outputFile = substr($inputFile, 0, strlen($inputFile)-strlen($inputExt)).strtolower($outputExt);
            $response = $this->getResponse();
            try {
                $strOutput = $dh->getData($outputExt);
                $contentType = $dh->getCT($outputExt);
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
