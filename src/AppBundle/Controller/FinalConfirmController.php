<?php
/**
 * Created by PhpStorm.
 * User: mtomczak
 * Date: 14/05/2018
 * Time: 21:10
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Member;
use AppBundle\Repository\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class FinalConfirmController extends Controller
{

    /**
     * @Route("/final-confirm/{hash}", name="final_confirm")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function index($hash)
    {
        $doctrine = $this->get('doctrine');
        /**
         * @var MemberRepository $memberRepository
         */
        $memberRepository = $doctrine->getRepository(Member::class);

        $member = $memberRepository->getByHash($hash);

        $member->setStatusFinalConfirmed();

        $doctrine->getManager()->flush();
        return $this->render('final_confirm/index.html.twig');
    }
}