<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Babysitter;
use AppBundle\Entity\MemberGroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Date;

class RejectedController extends Controller
{
    /**
     * @Route("/rejected/{token}", name="rejected")
     * @ParamConverter("babysiter", class="AppBundle:Babysitter", options={"mapping": {"token": "token"}})
     * @param Babysitter $babysitter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Babysitter $babysitter,$token)
    {
        if(!$babysitter->getConfirmedMailAt()){
            $this->createNotFoundException();
        }

        return $this->render('rejected/answer.html.twig',['members'=>$babysitter->getMembers(),'token'=>$token]);
    }

    /**
     * @Route("/rejected/confirm/{token}", name="rejected_confirm")
     * @ParamConverter("babysiter", class="AppBundle:Babysitter", options={"mapping": {"token": "token"}})
     * @param Babysitter $babysitter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmAction(Babysitter $babysitter)
    {
        if(!$babysitter->getConfirmedMailAt()){
            $this->createNotFoundException();
        }

        $connect = $this->get('doctrine')->getConnection();
        $connect->beginTransaction();
        $babysitter->setToken(null);
        foreach($babysitter->getMembers() as $member){
            $member->setRejectedAt(new \DateTime());
        }

        $this->getDoctrine()->getManager()->flush();
        $connect->commit();

        return $this->render('rejected/confirm.html.twig');
    }


    /**
     * @param Babysitter $babysitter
     * @param string $message
     */
    private function sendEmail(Babysitter $babysitter, $message)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Rejestracja Devoxx4kids')//FIXME add translations
            ->setFrom($this->getParameter('mailer_from'))
            ->setTo($babysitter->getEmail())
            ->setBody($message,
                'text/html'
            );
        $this->get('mailer')->send($message);
    }

}
