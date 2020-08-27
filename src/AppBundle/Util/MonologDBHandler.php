<?php
namespace AppBundle\Util;

use AppBundle\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Symfony\Component\Security\Core\Security;

class MonologDBHandler extends AbstractProcessingHandler
{
  /**
   * @var EntityManagerInterface
   */
  protected $em;

  protected $security;

  /**
   * MonologDBHandler constructor.
   * @param EntityManagerInterface $em
   * @param Security $security
   * @param RequestStack $request
   */
  public function __construct(EntityManagerInterface $em, Security $security)
  {
    parent::__construct();
    $this->em = $em;
    $this->security = $security;
  }

  /**
   * Called when writing to our database
   * @param array $record
   */
  protected function write(array $record)
  {
    $logEntry = new Log();
    $user = $this->security->getUser();

    $logEntry->setMessage($record['message']);
    $logEntry->setLevel($record['level']);
    $logEntry->setLevelName($record['level_name']);
    $logEntry->setExtra($record['extra']);
    $logEntry->setContext($record['context']);
    $logEntry->setUser($user);

    $this->em->persist($logEntry);
    $this->em->flush();
  }

}