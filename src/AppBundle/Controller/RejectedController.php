<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Babysitter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RejectedController extends Controller
{
    /**
     * @Route("/rejected/{token}", name="rejected")
     * @ParamConverter("babysiter", class="AppBundle:Babysitter", options={"mapping": {"token": "token"}})
     * @param Babysitter $babysitter
     * @param string $token
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Babysitter $babysitter, $token)
    {
        if (!$babysitter->getConfirmedMailAt()) {
            $this->createNotFoundException();
        }

        return $this->render('rejected/answer.html.twig', [
            'members' => $babysitter->getMembers(),
            'token' => $token
        ]);
    }

    /**
     * @Route("/rejected/confirm/{token}", name="rejected_confirm")
     * @ParamConverter("babysiter", class="AppBundle:Babysitter", options={"mapping": {"token": "token"}})
     * @param Babysitter $babysitter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmAction(Babysitter $babysitter)
    {
        if (!$babysitter->getConfirmedMailAt()) {
            $this->createNotFoundException();
        }

        $connect = $this->get('doctrine')->getConnection();
        $connect->beginTransaction();
        $babysitter->setToken(null);
        foreach ($babysitter->getMembers() as $member) {
            $member->setStatusRejected();
        }

        $this->getDoctrine()->getManager()->flush();
        $connect->commit();

        return $this->render('rejected/confirm.html.twig');
    }

}
