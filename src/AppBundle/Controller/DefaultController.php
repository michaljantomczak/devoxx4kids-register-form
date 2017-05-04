<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Babysitter;
use AppBundle\Entity\City;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(City $city)
    {
        $babysitter=new Babysitter();
        $babysitter->setCity($city);
        $form=$this->createForm(BabysitterType::class,$babysitter);
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'city'=>$city,
        ]);
    }
}
