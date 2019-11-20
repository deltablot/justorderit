<?php
namespace AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Swift_Attachment;

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
        // get all orders that are not sent yet
        $criteria = new \Doctrine\Common\Collections\Criteria();
        $criteria->where($criteria->expr()->eq('sent', 0));
        $indents = $this->em->getRepository('AppBundle:Indent')->matching($criteria);

        $ordersToProcess = count($indents);

        if ($ordersToProcess > 0) {
            $message = (new \Swift_Message('Orders to process'))
            ->setFrom($this->getParameter('mailer_from'))
            ->setTo($this->getParameter('mailer_to'))
            ->setBody($this->renderView('cron/cron.text.twig', array('indents' => $indents)), 'text/plain');

            // check if we need to attach a pdf
            foreach ($indents as $indent) {
                if ($indent->getProduct()->getQuote()) {
                    // Create the attachment
                    $fileName = $indent->getProduct()->getQuote();
                    $folder = substr($fileName, 0, 3);
                    $message->attach(
                        Swift_Attachment::fromPath(
                            $this->getParameter('quotes_directory') . '/' . $folder . '/' . $fileName
                        )
                        ->setFilename('quote-' . $indent->getProduct()->getName() . '.pdf')
                    );
                }
            }

            // add CC if config is here
            if ($this->getParameter('mailer_cc')) {
                $message->addCc($this->getParameter('mailer_cc'));
            }

            // send the mail
            $this->get('mailer')->send($message);

            // now update the sent column for indents that have been sent
            foreach ($indents as $indent) {
                $indent->setSent(true);
            }
            $this->em->flush();
        }

        return new Response($ordersToProcess);
    }
}
