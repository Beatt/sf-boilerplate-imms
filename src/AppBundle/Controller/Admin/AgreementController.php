<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Convenio;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Delegacion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use EasyCorp\Bundle\EasyAdminBundle\Form\Util\LegacyFormHelper;
use Symfony\Component\Validator\Constraints\File;

class AgreementController extends BaseAdminController
{

  const HEADERS = ['nombre', 'sector', 'tipo', 'ciclo', 'grado',
    'carrera', 'institucion', 'vigencia', 'delegacion', 'numero'];

  public function cargaAction()
  {
    $request = $this->request;
    $em = $this->em;
    $cm = $this->get('AppBundle\Service\ConvenioManagerInterface');
    $validator = $this->get('validator');

    $entity = new Convenio();
    $easyadmin = $this->request->attributes->get('easyadmin');
    $easyadmin['item'] = $entity;
    $this->request->attributes->set('easyadmin', $easyadmin);
    $fields = $this->entity['new']['fields'];

    $form = $this->createFormCargaConvenio();
    $data = [];
    $agregados = 0;

    // Check if we are posting stuff
    if ($request->getMethod('post') == 'POST') {
      // Bind request to the form
      $form->handleRequest($request);

      // If form is valid
      if ($form->isSubmitted() && $form->isValid()) {

        $nameFile = $form->get('submitFile')->getData();
        $dataCSV = $this->processFileCSV($nameFile);

        $i = 0;
        foreach ($dataCSV as $row) {
          $conv = $cm->processDataCSV($row);
          $errsConv = $validator->validate($conv);
          $messages = "";
          if (count($errsConv) > 0) {
            foreach ($errsConv as $violation) {
              $messages .=
                $violation->getPropertyPath() . ":" .
                $violation->getMessage() . ";";
            }
          } else {
            $em->persist($conv);
            $em->flush();
            $agregados++;
          }
          $data [] = array(
            'ind' => ++$i,
            'conv' => $conv,
            'row' => $row,
            'error' => $messages,
          );
        }
      }
    }

    $parameters = array(
      'form' => $form->createView(),
      'entity_fields' => $fields,
      'entity' => $entity,
      'data' => $data,
      'agregados' => $agregados,
      'headers' => self::HEADERS
    );

    return $this->render('easy_admin/agreement/carga.html.twig',
      $parameters
    );

  }

  protected function createFormCargaConvenio() {
    return $this->createFormBuilder()
      ->add('submitFile', FileType::class,
        array(
          'label' => 'Archivo CSV con Convenios a cargar',
          'attr' => array('accept' => "text/csv"),
          'constraints' => [
            new File([
              'mimeTypes' => ["text/csv", 'text/plain']
            ])
          ],
        ))
      ->add('submit', LegacyFormHelper::getType('submit'),
        array('label' => 'Cargar Convenios'))
      ->getForm();
  }

  protected function processFileCSV($nameFile) {
    $dataFile = file_get_contents($nameFile);
    $bom = pack("CCC", 0xEF, 0xBB, 0xBF);
    if (0 === strncmp($dataFile, $bom, 3)) {
      //BOM detected - file is UTF-8
      $dataFile = substr($dataFile, 3);
    }
    $checkEncoding = mb_detect_encoding($dataFile, "UTF-8, ISO-8859-1");
    if ($checkEncoding != 'UTF-8') {
      $fromEncoding = $checkEncoding ?: [];
      $dataFile = mb_convert_encoding($dataFile, 'UTF-8', $fromEncoding);
    }
    $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
    $dataCSV = $serializer->decode($dataFile, 'csv');
    return $dataCSV;
  }

}
