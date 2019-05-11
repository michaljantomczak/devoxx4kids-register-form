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
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MemberRejectedSendCommand extends ContainerAwareCommand
{

    public function configure()
    {
        $this->setName('member:rejected:send')
            ->setDescription('Email send to rejected members')
            ->addArgument('city slug',InputArgument::OPTIONAL,'Send only to members from city.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $city=null;
        if($input->hasArgument('city')){
            $city=$this->getCity($input->getArgument('city'));
        }
        $mails=$this->getMessages($city);

        foreach($mails as $mail){
            /**
             * @var Babysitter $babysitter
             */
            $babysitter=$mail['babysitter'];
            $this->sendMail($babysitter->getEmail(),$mail['members']);
        }
    }

    /**
     * @param $city
     * @return mixed[]
     */
    private function getMessages(Event $city=null)
    {
        $results=[];
        $em=$this->getContainer()->get('doctrine')->getManager();
        $memberRepository=$em->getRepository(Member::class);

        $conditions=[];
        $conditions['confirmedAt']=null;

        $members=$memberRepository->findBy($conditions);
        foreach($members as $member){
            $babysitter=$member->getBabysitter();
            if(null!==$city && $babysitter->getCity()!==$city){
                continue;
            }

            if(!isset($results[$babysitter->getId()])){
                $results[$babysitter->getid()]=['babysitter'=>$babysitter,'members'=>[]];
            }
            $results[$babysitter->getId()]['members'][]=$member;
        }

        return $results;
    }

    /**
     * @param string $slug
     * @return Event
     * @throws EventNotFoundException
     */
    private function getCity($slug)
    {
        $em=$this->getContainer()->get('doctrine')->getManager();
        $cityRepository=$em->getRepository(Event::class);
        $city=$cityRepository->findOneBy(['slug'=>$slug]);
        if(!$city){
            throw new EventNotFoundException($slug);
        }

        return $city;
    }

    private function sendMail($mail,$members)
    {
        $textMessage=$this->getContainer()->get('twig')->render('mail/rejected.txt.twig',['members'=>$members]);
        $message = \Swift_Message::newInstance()
            ->setSubject('Rejestracja Czarodzieje Kodu') //FIXME add translations
            ->setFrom($this->getContainer()->getParameter('mailer_from'))
            ->setTo($mail)
            ->setBody($textMessage,
                'text/plain'
            );
        $this->getContainer()->get('mailer')->send($message);
    }
}