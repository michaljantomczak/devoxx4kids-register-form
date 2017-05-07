<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Babysitter;
use AppBundle\Entity\City;
use AppBundle\Entity\Member;
use AppBundle\Form\BabysitterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DefaultController extends Controller
{
    /**
     * @Route("/{city}", name="homepage")
     * @ParamConverter("city", class="AppBundle:City", options={"mapping": {"city": "slug"}})
     * @param City $city
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(City $city,Request $request)
    {
        $babysitter=new Babysitter();
        $babysitter->setCity($city);
        $form=$this->createForm(BabysitterType::class,$babysitter);
        $form->handleRequest($request);
        if(!$form->isValid()){
            return $this->render('default/index.html.twig', [
                'form' => $form->createView(),
                'city'=>$city,
            ]);
        }

        $data=$form->getData();
        $em=$this->get('doctrine.orm.entity_manager');
        $em->persist($data);
        $em->flush();

        return $this->redirect('form_confirm');
        //TODO
    }

    /**
     *
     * @Route("/confirm", name="form_confirm")
     */
    public function confirmAction(){
        return $this->render('default/confirm.html.twig');
    }
}
