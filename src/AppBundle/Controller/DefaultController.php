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
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/city-{city}", name="homepage")
     * @ParamConverter("city", class="AppBundle:City", options={"mapping": {"city": "slug"}})
     * @param City $city
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(City $city, Request $request)
    {
        $babysitter = new Babysitter();
        $babysitter->setCity($city);
        $form = $this->createForm(BabysitterType::class, $babysitter);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render('default/index.html.twig', [
                'form' => $form->createView(),
                'city' => $city,
            ]);
        }

        $data = $form->getData();
        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($data);
        $em->flush();

        $message = $this->createConfirmMessage($data);
        $this->addFlash(
            'confirm',
            $message
        );

        $this->sendEmail($babysitter,$message);

        return $this->redirectToRoute('form_confirm');
    }

    /**
     *
     * @Route("/confirm", name="form_confirm")
     */
    public function confirmAction(Request $request)
    {

        /**
         * @var Session $session
         */
        $session = $request->getSession();
        $message = $session->getFlashBag()->get('confirm');
        if (!$message) {
            throw new NotFoundHttpException();
        }
        return $this->render('default/confirm.html.twig', [
            'message' => $message[0],
        ]);
    }

    /**
     * @param Babysitter $babysitter
     * @return string
     */
    private function createConfirmMessage(Babysitter $babysitter)
    {
        $message = 'Dzień Dobry - dziękujemy za rejestrację na warsztaty Devoxx4kids w mieście ' . $babysitter->getCity()->getName() . '. Uprzejmie informujemy, że: ';
        $first = true;
        $gender = null;
        foreach ($babysitter->getMembers() as $member) {
            if ($first) {
                $first = false;
            } else {
                $message .= ', ';
            }
            $message .= $member->getFirstName() . ' ' . $member->getLastName();
            if (!$gender) {
                $gender = $this->detectGender($member->getFirstName());
            } else if ($gender != $this->detectGender($member->getFirstName())) {
                $gender = 'b';
            }
        }
        if (count($babysitter->getMembers()) === 1) {
            if ('f' === $gender) {
                $message .= ' została zapisana ';
            } else {
                $message .= ' został zapisany ';
            }
        } else {
            if ('f' === $gender) {
                $message .= ' zostały zapisane ';
            } else {
                $message .= ' zostali zapisani ';
            }
        }

        $message .= ' na listę wstępną. Ze względu na ograniczoną liczbę miejsc, nie możemy zagwarantować uczestnictwa każdego dziecka. W najbliższym czasie będziemy kontaktować się telefonicznie w celu potwierdzenia zgłoszenia.';
        return $message;
    }

    private function detectGender($firstName)
    {
        $char = substr($firstName, -1, 1);
        return $char == 'a' ? 'f' : 'm';
    }

    private function sendEmail(Babysitter $babysitter, $message)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Rejestracja Devoxx4kids') //FIXME add translations
            ->setFrom($this->getParameter('mailer_from'))
            ->setTo($babysitter->getEmail())
            ->setBody($message,
                'text/plain'
            );
        $this->get('mailer')->send($message);
    }
}
