<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Babysitter;
use AppBundle\Entity\MemberGroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConfirmController extends Controller
{
    /**
     * @Route("/confirm/{token}", name="confirm")
     * @ParamConverter("babysiter", class="AppBundle:Babysitter", options={"mapping": {"token": "token"}})
     * @param Babysitter $babysitter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Babysitter $babysitter)
    {
        $connect = $this->get('doctrine')->getConnection();
        $connect->beginTransaction();

        $babysitter->setToken(null);
        $babysitter->setConfirmedMailAt(new \DateTime());
        $groupRepository = $this->getDoctrine()->getRepository(MemberGroup::class);
        $confirmed = false;
        foreach ($babysitter->getMembers() as $member) {
            $countFreePlace = $groupRepository->countFreePlace($member->getGroup());
//            echo $countFreePlace ; exit;
            if ($countFreePlace <= 0) {
                continue;
            }

            $member->setExpectant(false);
            $confirmed = true;
        }

        if ($confirmed) {
            $babysitter->setToken(md5(uniqid()));
        }
        $this->getDoctrine()->getManager()->flush();
        $connect->commit();

        $message = $this->renderView('confirm/message.html.twig', ['members' => $babysitter->getMembers()]);
        if ($confirmed) {
            $this->sendEmail($babysitter, $this->renderView('confirm/email.html.twig', [
                'message' => $message,
                'token' => $babysitter->getToken()
            ]));
        }

        return $this->render('confirm/index.html.twig', ['message' => $message]);
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
