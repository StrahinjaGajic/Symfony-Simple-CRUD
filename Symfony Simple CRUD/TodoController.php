<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class TodoController extends Controller
{
    /**
     * @Route("/todo", name="todo_list")
     */
    public function listAction()
    {
        $todos = $this->getDoctrine()->getRepository('AppBundle:Todo')->findAll();

        // replace this example code with whatever you need
        return $this->render('todo/index.html.twig', [
            'todos'=>$todos
        ]);
    }
    /**
     * @Route("/todo/create", name="todo_create")
     */
    public function createAction(Request $request)
    {
        $todos = new Todo();

        $form = $this->createFormBuilder($todos)
        ->add('name',TextType::class, array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px;')))
        ->add('category',TextType::class, array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px;')))
        ->add('description',TextareaType::class, array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px;')))
        ->add('priority',ChoiceType::class,array('choices' => array('Low' => 'Low', 'Normal' => 'Normal', 'High' => 'High'),'attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px;')))
        ->add('dueDate',DateTimeType::class, array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px;')))
            ->add('save',SubmitType::class,array('label' => 'Create Todo','attr'=>array('class'=>'btn btn-primary','style'=>'margin-bottom:15px;')))

            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $dueDate = $form['dueDate']->getData();

            $now = new\DateTime('now');

            $todos->setCategory($category);
            $todos->setDescription($description);
            $todos->setDueDate($dueDate);
            $todos->setPriority($priority);
            $todos->setName($name);
            $todos->setCreateDate($now);

            $em = $this->getDoctrine()->getManager();
            $em->persist($todos);
            $em->flush();

            $this->addFlash(
                'notice','Succesfully Registered'
            );

            return $this->redirectToRoute('todo_list');
        }
        // replace this example code with whatever you need
        return $this->render('todo/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/todo/edit/{id}", name="todo_edit")
     */
    public function editAction($id, Request $request)
    {

        $todo = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($id);


        $form = $this->createFormBuilder($todo)
            ->add('name',TextType::class, array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px;')))
            ->add('category',TextType::class, array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px;')))
            ->add('description',TextareaType::class, array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px;')))
            ->add('priority',ChoiceType::class,array('choices' => array('Low' => 'Low', 'Normal' => 'Normal', 'High' => 'High'), 'attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px;')))
            ->add('dueDate',DateTimeType::class, array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px;')))
            ->add('save',SubmitType::class,array('label' => 'Create Todo','attr'=>array('class'=>'btn btn-primary','style'=>'margin-bottom:15px;')))

            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $dueDate = $form['dueDate']->getData();

            $now = new\DateTime('now');

            $em = $this->getDoctrine()->getManager();
            $todo = $em
                ->getRepository('AppBundle:Todo')
                ->find($id);

            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setDueDate($dueDate);
            $todo->setPriority($priority);
            $todo->setName($name);
            $todo->setCreateDate($now);

            $em->flush();

            $this->addFlash(
                'notice','Succesfully Edited'
            );

            return $this->redirectToRoute('todo_list');
        }

        // replace this example code with whatever you need
        return $this->render('todo/edit.html.twig', [
            'todo'=>$todo,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/todo/details/{id}", name="todo_details")
     */
    public function detailsAction($id)
    {
        $todo = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($id);

        dump($todo);

        // replace this example code with whatever you need
        return $this->render('todo/details.html.twig', [
            'todo'=>$todo
        ]);
    }
    /**
     * @Route("/todo/delete/{id}", name="todo_delete")
     */
    public function deletesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $todo = $em
            ->getRepository('AppBundle:Todo')
            ->find($id);

        $em->remove($todo);

        $em->flush();

        $this->addFlash('notice','Todo '. $todo->getName() .' Removed');

        // replace this example code with whatever you need
        return $this->redirectToRoute('todo_list');
    }


}