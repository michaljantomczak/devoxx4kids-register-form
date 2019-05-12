<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Babysitter;
use AppBundle\Entity\Event;
use AppBundle\Entity\Member;
use AppBundle\Entity\MemberGroup;
use AppBundle\Entity\Stage;
use AppBundle\Form\BabysitterType;
use AppBundle\Repository\MemberRepository;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/event-{event}", name="homepage")
     * @ParamConverter("event", class="AppBundle:Event", options={"mapping": {"event": "slug"}})
     * @param Event $event
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws NoResultException
     */
    public function indexAction(Event $event, Request $request)
    {
        if (!$event->isEnabled()) {
            return $this->render('default/index.html.twig', [
                'event' => $event,
                'active' => false,
            ]);
        }

        $stageRepository = $this->get('doctrine')->getRepository(Stage::class);

        try {
            $stage = $stageRepository->findLastActiveStage($event);
        } catch (NoResultException $e) {
            return $this->render('default/index.html.twig', [
                'event' => $event,
                'active' => false,
            ]);
        }
        /**
         * @var MemberRepository $memberRepository
         */
        $memberRepository = $this->get('doctrine')->getRepository(Member::class);
        $countMember = $memberRepository->countMaxMemberInGroup($event);

        if ($stage->getCountRequiredMembers() <= $countMember) {
            return $this->render('default/index.html.twig', [
                'event' => $event,
                'active' => false,
            ]);
        }

        $babysitter = new Babysitter();
        $babysitter->setEvent($event);
        $form = $this->createForm(BabysitterType::class, $babysitter);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render('default/index.html.twig', [
                'form' => $form->createView(),
                'event' => $event,
                'active' => true,
            ]);
        }

        $data = $form->getData();
        $em = $this->get('doctrine.orm.entity_manager');

        $this->fillGroups($babysitter->getMembers());

        $em->persist($data);
        $em->flush();

        $this->addFlash('email', $babysitter->getEmail());

        $this->sendConfirmEmail($babysitter, $this->renderView('default/email.html.twig',['token'=>$babysitter->getToken()]));

        return $this->redirectToRoute('info');
    }

    /**
     *
     * @Route("/info", name="info")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function infoAction(Request $request)
    {
        /**
         * @var Session $session
         */
        $session = $request->getSession();
        $email = $session->getFlashBag()->get('email');
        if (!$email) {
            throw new NotFoundHttpException();
        }
        return $this->render('default/info.html.twig', [
            'email' => $email[0],
        ]);
    }

    /**
     * @param string $firstName
     * @return string
     */
    private function detectGender($firstName)
    {
        $char = substr($firstName, -1, 1);
        return $char == 'a' ? 'f' : 'm';
    }

    /**
     * @param Babysitter $babysitter
     * @param string $message
     */
    private function sendConfirmEmail(Babysitter $babysitter, $message)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Rejestracja Czarodzieje Kodu')//FIXME add translations
            ->setFrom($this->getParameter('mailer_from'))
            ->setTo($babysitter->getEmail())
            ->setBody($message,
                'text/html'
            );
        $this->get('mailer')->send($message);
    }

    /**
     * @param Member[] $members
     * @throws NoResultException
     */
    private function fillGroups($members)
    {
        $groupRepository = $this->get('doctrine')->getRepository(MemberGroup::class);
        $now = new \DateTime();
        $currentYeat = $now->format('Y');
        foreach ($members as $member) {
            $year = $member->getBornAt()->format('Y');
            $memberYear = $currentYeat - $year;
            $group = $groupRepository->findByOld($memberYear);
            $member->setGroup($group);
        }
    }
}
