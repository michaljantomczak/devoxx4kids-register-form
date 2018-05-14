<?php
/**
 * Created by PhpStorm.
 * User: mtomczak
 * Date: 22/05/2017
 * Time: 17:24
 */

namespace AppBundle\Command;


use AppBundle\Entity\Babysitter;
use AppBundle\Entity\Event;
use AppBundle\Entity\Member;
use AppBundle\Exception\EventNotFoundException;
use AppBundle\Repository\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MemberConfirmSendCommand extends ContainerAwareCommand
{

    public function configure()
    {
        $this->setName('member:confirm:send')
            ->setDescription('Email send to confirm members')
            ->addArgument('event', InputArgument::OPTIONAL, 'Send only to members from event.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws EventNotFoundException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $city = null;
        if ($input->hasArgument('city')) {
            $city = $this->getEvent($input->getArgument('city'));
        }

        $members = $this->getMessages($city);

        foreach ($members as $member) {
            /**
             * @var Babysitter $babysitter
             */
            $babysitter = $member->getBabysitter();
            $this->sendMail($babysitter->getEmail(), $member);
        }
    }

    /**
     * @param $event
     * @return Member[]
     */
    private function getMessages(Event $event = null)
    {
        $results = [];
        $em = $this->getContainer()->get('doctrine')->getManager();
        /**
         * @var MemberRepository $memberRepository
         */
        $memberRepository = $em->getRepository(Member::class);

        $members = $memberRepository->findConfirm();
        foreach ($members as $member) {
            $babysitter = $member->getBabysitter();
            if (null !== $event && $babysitter->getEvent() !== $event) {
                continue;
            }
            $results[] = $member;

        }

        return $results;
    }

    /**
     * @param string $slug
     * @return Event
     * @throws EventNotFoundException
     */
    private function getEvent($slug)
    {

        $em = $this->getContainer()->get('doctrine')->getManager();
        $cityRepository = $em->getRepository(Event::class);
        $event = $cityRepository->findOneBy(['slug' => $slug]);
        if (!$event) {
            throw new EventNotFoundException($slug);
        }

        return $event;
    }

    private function sendMail($mail, Member $member)
    {
        $textMessage = $this->getContainer()->get('twig')->render('mail/confirm.html.twig', ['member' => $member,'hash'=>md5($member->getId())]);
        $message = \Swift_Message::newInstance()
            ->setSubject('Devoxx4Kids 2018 - potwierdzenie uczestnictwa')//FIXME add translations
            ->setFrom($this->getContainer()->getParameter('mailer_from'))
            ->setTo($mail)
            ->setBody($textMessage,
                'text/html'
            );
        $this->getContainer()->get('mailer')->send($message);
        $member->setStatusWaitingOnConfirm();
        $this->getContainer()->get('doctrine')->getManager()->flush();
    }
}