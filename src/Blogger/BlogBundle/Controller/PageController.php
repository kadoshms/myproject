<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 6/25/14
 * Time: 11:57 AM
 */

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// Import new namespaces
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function indexAction()
    {
        return $this->render('BloggerBlogBundle:Page:index.html.twig');
    }

    public function aboutAction()
    {
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }


    public function contactAction(Request $request)
    {

        $enquiry = new Enquiry();
        $form = $this->createFormBuilder($enquiry)
            ->add('name')
            ->add('email', 'email')
            ->add('subject')
            ->add('body', 'textarea')
            ->getForm();
       /*// $form->handleRequest($request);
        $form->submit($request->request->get($form->getName()));
            echo $request->getMethod();
            echo "m:".$request->isMethod('POST');
        if ($request->isMethod('POST')) {
            echo "hello world";

            if ($form->isValid()) {

                echo "valid is fuck";
                return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));
            }
        }*/

        if ($request->isMethod('POST')) {
            echo "success";
            $form->submit($request);

            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact enquiry from symblog')
                    ->setFrom('enquiries@symblog.co.uk')
                    ->setTo('email@email.com')
                    ->setBody($this->renderView('BloggerBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));
                $this->get('mailer')->send($message);
                $this->get('session')->setFlash('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');

               // return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));
            }else{
                echo "Error";
            }
        }




        return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
            'form' => $form->createView()
        ));


    }
}

?>