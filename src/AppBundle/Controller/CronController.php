<?php
namespace AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * The cron controller
 *
 * @Route("cron")
 */
class CronController extends Controller
{
    /*
     * Inject the entitymanager. If this is not here calling from console will not work!
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Send an email with a recap of the orders
     *
     * @Route("/")
     * @Method("GET")
     * @return int the number of orders sent
     */
    public function cronAction()
    {
     //   var_dump($this);die;
        // get all orders that are not sent yet
        $criteria = new \Doctrine\Common\Collections\Criteria();
        $criteria->where($criteria->expr()->eq('sent', 0));
        $indents = $this->em->getRepository('AppBundle:Indent')->matching($criteria);

        $ordersToProcess = count($indents);

        //var_dump($this->get('parameter')->getParameter('mailer_to'));die;
        if ($ordersToProcess > 0) {
            $message = (new \Swift_Message('Orders to process'))
            ->setFrom($this->getParameter('mailer_from'))
            ->setTo($this->getParameter('mailer_to'))
            ->setBody($this->renderView('cron/cron.text.twig', array('indents' => $indents)), 'text/plain');

            $result = $this->get('mailer')->send($message);

            // now update the sent column for indents that have been sent
            foreach ($indents as $indent) {
                $indent->setSent(true);
            }
            $this->em->flush();
        }

        //return $ordersToProcess;
        return new Response($ordersToProcess);
    }
}
